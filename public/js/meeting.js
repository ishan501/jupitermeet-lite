(function () {
  "use strict";

  const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  const isOnIOS =
    navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPhone/i);
  const eventName = isOnIOS ? "pagehide" : "beforeunload";

  const audioInputSelect = document.querySelector("select#audioSource");
  const videoInputSelect = document.querySelector("select#videoSource");
  const selectors = [audioInputSelect, videoInputSelect];

  let socket;
  let constraints;
  let localStream;
  let meetingType;
  let currentMeetingTime;
  let layoutContainer = document.getElementById("videos");
  let layout = initLayoutContainer(layoutContainer).layout;
  let chatBotName;
  let recorder;
  let screenStream;
  let speechEvents;
  let wakeLock;
  let displayFileUrl;
  let resizeTimeout;
  let messageCount = 0;
  let lastTimestamp = 0;
  let avatars = [];
  let usernames = [];
  let connections = [];
  let messageQueue = [];
  let screenConnections = [];
  let settings = {};
  let configuration = {};
  let allMuted = false;
  let audioMuted = false;
  let videoMuted = false;
  let initiated = false;
  let screenShared = false;
  let isModerator = moderator;
  let timer = new easytimer.Timer();
  let notificationTone = new Audio("/sounds/notification.mp3");
  let audioCtx, audioDest;
  let audioNodes = {};
  let fakeVideo = null;
  const userId = userInfo.meetingId + "--" + crypto.randomUUID();

  //get the details
  (function () {
    $.post({
      url: "/get-details",
    })
      .done(function (data) {
        data = JSON.parse(data);

        if (data.success) {
          settings = data.data;

          initializeSocket(settings.signalingURL);

          configuration = {
            iceServers: [
              {
                urls: settings.stunUrl,
              },
              {
                urls: settings.turnUrl,
                username: settings.turnUsername,
                credential: settings.turnPassword,
              },
            ],
          };
        } else {
          showInfo(languages.no_session, false);
        }
      })
      .catch(function () {
        showInfo(languages.no_session, false);
      });
  })();

  //connect to the signaling server and add listeners
  function initializeSocket(signalingURL) {
    let socketTimeout;

    socket = io.connect(signalingURL, {
      autoConnect: true,
      transports: ["websocket", "polling"],
      reconnection: true,
      reconnectionAttempts: Infinity,
      reconnectionDelay: 1000,
      reconnectionDelayMax: 5000,
      randomizationFactor: 0.5,
      timeout: 20000,
      forceNew: false,
      query: `userId=${userId}&meetingId=${userInfo.meetingId}&isModerator=${isModerator}`,
    });

    //show the error message and disable the join button
    socket.on("connect_error", function () {
      $("#joinMeeting").attr("disabled", true);
      $("#error").show();
    });

    //hide the error message and enable the join button
    socket.on("connect", function () {
      $("#joinMeeting span").text(languages.start);
      $("#joinMeeting").attr("disabled", false);
      $("#error").hide();

      if (initiated) {
        showInfo(languages.online, false);
        enableButtons();
      }

      if (socketTimeout) {
        clearTimeout(socketTimeout);
      }
    });

    socket.on("disconnect", function (reason) {
      if (reason !== "io client disconnect") {
        showInfo(languages.offline, false);
      }

      disableButtons();

      socketTimeout = setTimeout(() => {
        reload(0);
      }, 60 * 1000);
    });

    //handle socket reconnect and get missed messages
    socket.io.on("reconnect", (attemptNumber) => {
      if (messageQueue.length > 0) {
        messageQueue.forEach((msg) => {
          sendMessage(msg);
        });

        messageQueue = [];
      }

      sendMessage({
        type: "getMissedMessages",
        since: lastTimestamp,
        userId,
      });

      if (!initiated) {
        //clear the info toastr - for join request reconnection
        clearToaster();
      }
    });

    //listen for socket message event and handle it
    socket.on("message", function (data) {
      handleSocketMessages(data);
    });

    //load media devices
    loadMediaDevice(true);

    //get item from localStorage and set to html
    if (!userInfo.username) {
      userInfo.username = username.value =
        localStorage.getItem("username") ||
        htmlEscape(settings.defaultUsername);
    }
  }

  async function loadMediaDevice(setValue) {
    let devices = await navigator.mediaDevices.enumerateDevices();
    gotDevices(devices);

    if (!setValue) return;

    //get item from localStorage and set to html
    videoQualitySelect.value = localStorage.getItem("videoQuality") || "VGA";
    videoSource.value = localStorage.getItem("videoSource") || "";

    setMediaPreview();
  }

  //enable buttons
  function enableButtons() {
    $(
      "#videoQualitySelect, #audioSource, #videoSource, #videoObjectFit, #whiteboard, #toggleMic, #toggleVideo, #screenShare, .recording, .muteAll, .camera-admin, .mic-admin, .kick, .make-moderator",
    ).attr("disabled", false);
  }

  //disable buttons
  function disableButtons() {
    $(
      "#videoQualitySelect, #audioSource, #videoSource, #videoObjectFit, #whiteboard, #toggleMic, #toggleVideo, #screenShare, .recording, .muteAll, .camera-admin, .mic-admin, .kick, .make-moderator",
    ).attr("disabled", true);
  }

  // disable video source select input when the camera is turned OFF
  function disableVideoSelect() {
    $("#videoSource, #videoQualitySelect").prop("disabled", true);
    $("#videoSource, #videoQualitySelect").addClass("videoSourceOpacity");
  }

  // enable video source select input when the camera is turned ON
  function enableVideoSelect() {
    $("#videoSource, #videoQualitySelect").prop("disabled", false);
    $("#videoSource, #videoQualitySelect").removeClass("videoSourceOpacity");
  }

  function disableAudioSelect() {
    $("#audioSource").prop("disabled", true);
    $("#audioSource").addClass("videoSourceOpacity");
  }

  function enableAudioSelect() {
    $("#audioSource").prop("disabled", false);
    $("#audioSource").removeClass("videoSourceOpacity");
  }

  //handle socket messages
  function handleSocketMessages(data) {
    if (data.timestamp) {
      lastTimestamp = Math.max(lastTimestamp, data.timestamp);
    }

    switch (data.type) {
      case "join":
        handleJoin(data);
        break;
      case "offer":
        handleOffer(data);
        break;
      case "answer":
        handleAnswer(data);
        break;
      case "candidate":
        handleCandidate(data);
        break;
      case "leave":
        handleLeave(data.userId, data.isModerator);
        break;
      case "checkMeetingResult":
      case "permissionResult":
        checkMeetingResult(data);
        break;
      case "meetingMessage":
        handlemeetingMessage(data);
        break;
      case "permission":
        handlePermission(data);
        break;
      case "info":
        showInfo(languages.please_wait, true);
        break;
      case "kick":
        showInfo(languages.kicked, false);
        reload(0);
        break;
      case "raiseHand":
        showInfo(languages.hand_raised + ": " + data.username, false);
        break;
      case "currentTime":
        //update the timer if the user joins an existing room
        timer.stop();
        timer.start({
          precision: "seconds",
          startValues: {
            seconds: data.currentTime,
          },
          target: {
            seconds: timeLimit * 60 - 60,
          },
        });
        break;
      case "speaking":
        handleSpeaking(data);
        break;
      case "mic-admin":
        handleMicAdmin(data.value);
        break;
      case "camera-admin":
        handleCameraAdmin(data.value);
        break;
      case "micToggled":
        handleMicToggled(data.fromUserId, data.audioMuted);
        break;
      case "cameraToggled":
        handleCameraToggled(data.fromUserId, data.videoMuted);
        break;
      case "moderatorButtons":
        handleModeRatorButtons(data);
        break;
      case "messageHistory":
        handleMessageHistory(data.messages);
        break;
      case "notifyMediaStatus":
        handleMediaStatus(data);
        break;
    }
  }

  //handle message history
  function handleMessageHistory(messages) {
    messages.forEach((message) => {
      handleSocketMessages(message);
    });
  }

  //get media stream and set video preview, show the error if any
  async function setMediaPreview() {
    $("#toggleCameraPreview").addClass("disabled");
    document.getElementById("overlay").style.display = "block";

    //the room has space, get the media and initiate the meeting
    constraints = {
      audio: getAudioConstraints(),
      video: getVideoConstraints(),
    };

    try {
      setTimeout(() => {
        document.getElementById("overlayText").innerHTML =
          languages.click_allow;
      }, 1000);

      //get user media
      localStream = await navigator.mediaDevices.getUserMedia(constraints);
    } catch (e) {
      //show an error if the media device is not available
      $(".text-show").text(languages.no_device + e);
      showInfo(languages.no_device + e.name, false);
      if (e.name == "OverconstrainedError") {
        $("#videoQualitySelect").val("VGA").trigger("change");
        localStorage.setItem("videoQuality", "VGA");
        setMediaPreview();
      }
      $("#toggleCameraPreview").removeClass("disabled");
    }

    document.getElementById("overlay").style.display = "none";

    if (localStream) {
      previewVideo.srcObject = localStream;
      previewVideo.style.zIndex = 2;
      $(".text-show").text();
      $("#toggleCameraPreview")
        .html(
          `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-video" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2zm11.5 5.175 3.5 1.556V4.269l-3.5 1.556zM2 4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1z"/>
</svg>`,
        )
        .removeClass("disabled");
      meetingType = "video";
    }
  }

  //toggle video preview
  $("#toggleCameraPreview").on("click", function () {
    if (localStream && localStream.getVideoTracks().length) {
      disableVideoSelect();
      localStream.getVideoTracks().forEach((track) => track.stop());
      localStream.removeTrack(localStream.getVideoTracks()[0]);
      previewVideo.srcObject = null;
      previewVideo.style.zIndex = 0;
      $("#toggleCameraPreview").html(`
    <svg xmlns="http://www.w3.org/2000/svg" 
         width="16" 
         height="16" 
         fill="currentColor" 
         class="bi bi-camera-video-off" 
         viewBox="0 0 16 16">
         
        <path fill-rule="evenodd" d="M10.961 12.365a2 2 0 0 0 .522-1.103l3.11 1.382A1 1 0 0 0 16 11.731V4.269a1 1 0 0 0-1.406-.913l-3.111 1.382A2 2 0 0 0 9.5 3H4.272l.714 1H9.5a1 1 0 0 1 1 1v6a1 1 0 0 1-.144.518zM1.428 4.18A1 1 0 0 0 1 5v6a1 1 0 0 0 1 1h5.014l.714 1H2a2 2 0 0 1-2-2V5c0-.675.334-1.272.847-1.634zM15 11.73l-3.5-1.555v-4.35L15 4.269zm-4.407 3.56-10-14 .814-.58 10 14z"/>
         
    </svg>
`);
      meetingType = "audio";
    } else {
      enableVideoSelect();
      meetingType = "";
      setMediaPreview();
    }
  });

  //listen for timer update event and display during the meeting
  timer.addEventListener("secondsUpdated", function () {
    currentMeetingTime =
      timer.getTimeValues().minutes * 60 + timer.getTimeValues().seconds;
    $(".timer").html(getCurrentTime());
  });

  //start the timer for last one minute and end the meeting after that
  timer.addEventListener("targetAchieved", function () {
    $(".timer").css("color", "red");
    timer.stop();
    timer.start({
      precision: "seconds",
      startValues: {
        seconds: currentMeetingTime,
      },
    });
    showInfo(languages.meeting_ending, true);

    setTimeout(function () {
      showInfo(languages.meeting_ended, true);
      reload(1);
    }, 60 * 1000);
  });

  //ajax call to check password, continue to meeting if valid
  $("#passwordCheck").on("submit", function (e) {
    e.preventDefault();

    if (!localStream) return;
    $("#joinMeeting").attr("disabled", true);
    $("s#toggleCameraPreview").addClass("disabled");

    //show an error if the signaling server is not connected
    if (!socket.connected) {
      showInfo(languages.cant_connect, false);
      $("#joinMeeting").attr("disabled", false);
      $("#toggleCameraPreview").removeClass("disabled");
      return;
    }

    if (passwordRequired) {
      $.ajax({
        url: "/check-meeting-password",
        data: $(this).serialize(),
        type: "post",
      })
        .done(function (data) {
          data = JSON.parse(data);
          $("#joinMeeting").attr("disabled", false);
          $("#toggleCameraPreview").removeClass("disabled");

          if (data.success) {
            continueToMeeting();
          } else {
            showInfo(languages.invalid_password, false);
          }
        })
        .catch(function () {
          showInfo("", false);
          $("#joinMeeting").attr("disabled", false);
          $("#toggleCameraPreview").removeClass("disabled");
        });
    } else {
      continueToMeeting();
    }
  });

  //set details into localStorage and notify server to check meeting status
  function continueToMeeting() {
    if (
      !window.JM_SYSTEM_READY
    ) {
      showInfo(languages.system_error, true);
      return false;
    }
    //set username
    userInfo.username = username.value || htmlEscape(settings.defaultUsername);
    localStorage.setItem("username", userInfo.username);

    //check if the meeting is full or not
    sendMessage({
      type: "checkMeeting",
      username: userInfo.username,
      meetingId: userInfo.meetingId,
      moderator: isModerator,
      authMode: settings.authMode,
      moderatorRights: settings.moderatorRights,
      userLimit,
      userId,
    });
  }

  //stringify the data and send it to opponent
  function sendMessage(data) {
    socket
      .timeout(3000)
      .emit("message", JSON.stringify(data), (err, response) => {
        if (err && data.type != "speaking") {
          // server didn't ack within 3s, treat as failed
          messageQueue.push(data);
        }
      });
  }

  //get current meeting time in readable format
  function getCurrentTime() {
    return timer.getTimeValues().toString(["hours", "minutes", "seconds"]);
  }

  //reload after a specific seconds
  function reload(seconds) {
    setTimeout(function () {
      window.location.reload();
    }, seconds * 1000);
  }

  //show info
  function showInfo(message = languages.error_occurred, sticky) {
    //sticky toastr
    const toast =
      $(`<div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header">
                  <strong class="me-auto">${siteName.value}</strong>
                  <small>${languages.just_now}</small>
                  <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
              </div>`);

    $("#toastContainer").append(toast);

    // change toastr theme to dark when meeting is initated
    if (initiated) {
      $("#toastContainer").attr("data-bs-theme", "dark");
      $(".toast-body").attr("style", "color: var(--tblr-white);");
    }

    if (!sticky) {
      setTimeout(() => {
        toast.fadeOut();
        toast.remove();
      }, 3000);
    }
  }

  //check meeting request
  async function checkMeetingResult(data) {
    clearToaster();

    //init the meeting if result is true and media is available
    if (data.result && localStream) {
      if (data.type == "permissionResult" && isModerator) {
        isModerator = false;
      }

      if (isModerator) $(".muteAll").css("display", "flex");

      init();

      chatBotName = settings.aiChatbot;

      if (chatBotName) {
        const lowerCaseChatBotName = chatBotName.toLowerCase();
        const messageString = `message_${lowerCaseChatBotName}`;

        $("#chatGPTChat .card-title").html(
          '<img src="/images/' +
          lowerCaseChatBotName +
          '-logo.png" width="30" alt="' +
          chatBotName +
          '" /> ' +
          chatBotName,
        );
      }

    } else {
      //there is an error, show it to the user
      showInfo(languages[data.message], false);
      $("#joinMeeting").attr("disabled", false);
      $("#toggleCameraPreview").removeClass("disabled");
    }
  }

  //notify the moderator for new join request
  function handlePermission(data) {
    notificationTone.play();

    const toast = `<div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header">
                  <strong class="me-auto">${siteName.value}</strong>
                  <small>${languages.just_now}</small>
                </div>
                <div class="toast-body">
                  ${languages.request_join_meeting + data.username}
                  <div class="mt-2 pt-2 border-top">
                    <a class="btn btn-success btn-sm p-2 approve" data-socketid="${data.fromSocketId}">${languages.approve}</a>
                    <a class="btn btn-danger btn-sm p-2 decline" data-socketid="${data.fromSocketId}">${languages.decline}</a>
                  </div>
                </div>
              </div>`;

    $("#toastContainer").append(toast);

    // change toastr theme to dark when meeting is initated
    if (initiated) {
      $("#toastContainer").attr("data-bs-theme", "dark");
      $(".toast-body").attr("style", "color: var(--tblr-white);");
    }
  }

  //notify participant about the request approval
  $(document).on("click", ".approve", function () {
    const selector = $(this).closest(".toast");

    if (selector) {
      selector.remove();
    }

    const socketId = $(this).data("socketid");

    sendMessage({
      type: "permissionResult",
      result: true,
      socketId,
      allMuted: allMuted,
    });
  });

  //notify participant about the request rejection
  $(document).on("click", ".decline", function () {
    const selector = $(this).closest(".toast");

    if (selector) {
      selector.remove();
    }

    const socketId = $(this).data("socketid");

    sendMessage({
      type: "permissionResult",
      result: false,
      socketId,
      message: "request_declined",
    });
  });

  //initiate the meeting
  async function init() {
    try {
      wakeLock = await navigator.wakeLock.request("screen");
    } catch (e) {
      console.log("Wake lock failed");
    }

    initMeetingLog();

    $(".meeting-details, .navbar, footer").hide();
    $(".meeting-section").removeClass("d-none");

    //set object fit property from local storage if available
    if (localStorage.getItem("objectFit")) {
      $(".cam").css("object-fit", localStorage.getItem("objectFit"));
      $("#videoObjectFit").val(localStorage.getItem("objectFit"));
    }

    localVideo.srcObject = localStream;
    previewVideo.srcObject = null;
    layout();
    sendMessage({
      type: "join",
      username: userInfo.username,
      meetingId: userInfo.meetingId,
      isModerator,
      screen: false,
      avatar: userInfo.avatar,
      mic: audioMuted,
      camera: videoMuted,
      userId,
    });

    $(".meeting-id").html(meetingTitle);

    //start with a time limit for limited time meeting
    timer.start({
      precision: "seconds",
      startValues: { seconds: 0 },
      target: { seconds: timeLimit * 60 - 60 },
    });
    manageOptions();
    if (meetingType === "audio") $("#toggleVideo").remove();
    if (!isMobile) $("#screenShare").show();
    initKeyShortcuts();
    $(".showParticipantList").addClass("number").attr("data-content", 1);

    if (!audioMuted) {
      initHark();
    }

    $(".jm-call-start-avatar .avatar").text(userInfo.username[0]);

    initiated = true;
  }

  //active speaker: speech detection - hark
  function initHark() {
    speechEvents = hark(localStream, {});
    speechEvents.on("speaking", sendSpeakingIndication);
    speechEvents.on("stopped_speaking", sendSpeakingIndication);
  }

  //send speaking indication to the server
  function sendSpeakingIndication() {
    document
      .getElementById("selfContainer")
      .classList.toggle("speaking-shadow");

    sendMessage({
      type: "speaking",
      userId,
    });
  }

  //stop hark
  function stopHark() {
    if (speechEvents) {
      speechEvents.stop();
    }
  }

  //handle speaking
  function handleSpeaking(data) {
    const selector = document.getElementById("container-" + data.userId);

    if (selector) {
      selector.classList.toggle("speaking-shadow");
    }
  }

  //hide/show certain meeting related details
  function manageOptions() {
    $(".meeting-options").show();
    $("#meetingIdInfo").text(meetingTitle);
    localStorage.setItem("videoQuality", videoQualitySelect.value);

    if (meetingType === "video") {
      $("#toggleVideo").show();
    }
  }

  //create and send an offer for newly joined user
  function handleJoin(data) {
    //stop screen sharing
    // if (data.screen && screenShared) stopScreenSharing();

    //prevent duplicate join requests
    if (screenConnections[data.userId]) return;

    const opponentUserId = data.userId;

    let options = {
      fromUserId: userId,
      toUserId: opponentUserId,
      isModerator: data.isModerator,
    };

    //initialize a new connection
    let connection = new RTCPeerConnection(configuration);

    if (data.screen) {
      screenConnections[opponentUserId] = connection;

      options.isScreen = true;
      options.username = userInfo.username + "-screen";
    } else {
      connections[opponentUserId] = connection;

      options.isScreen = false;
      options.username = userInfo.username;

      usernames[opponentUserId] = data.username;
      avatars[opponentUserId] = data.avatar;
    }

    processJoin(options, connection);

    //if screen is already shared and new user joins, then send a join message
    if (screenShared) {
      sendMessage({
        type: "join",
        username: userInfo.username + "-screen",
        meetingId: userInfo.meetingId,
        screen: true,
        userId,
      });
    }
  }

  //process join
  function processJoin(data, connection) {
    setupListeners(connection, data);

    connection
      .createOffer({
        offerToReceiveVideo: true,
      })
      .then(function (offer) {
        return connection.setLocalDescription(offer);
      })
      .then(function () {
        sendMessage({
          type: "offer",
          sdp: connection.localDescription,
          meetingId: userInfo.meetingId,
          username: data.username,
          fromUserId: userId,
          toUserId: data.toUserId,
          isModerator,
          avatar: userInfo.avatar,
          screen: data.isScreen,
          reconnection: false,
        });
      })
      .catch(function (e) {
        console.log(languages.error_message, e);
      });
  }

  //handle offer from initiator, create and send an answer
  function handleOffer(data) {
    const opponentUserId = data.fromUserId;

    // Reuse if it exists, otherwise create
    let connection = data.screen
      ? screenConnections[opponentUserId] ||
      new RTCPeerConnection(configuration)
      : connections[opponentUserId] || new RTCPeerConnection(configuration);

    let options = {
      fromUserId: userId,
      toUserId: opponentUserId,
      isModerator: data.isModerator,
      sdp: data.sdp,
      reconnection: data.reconnection,
    };

    if (data.screen) {
      screenConnections[opponentUserId] = connection;
      options.isScreen = true;
    } else {
      connections[opponentUserId] = connection;
      options.isScreen = false;

      usernames[opponentUserId] = data.username;
      avatars[opponentUserId] = data.avatar;
    }

    processOffer(options, connection);
  }

  function processOffer(data, connection) {
    const desc = data.sdp;

    // If we're mid-offer, rollback then accept their offer
    if (connection.signalingState !== "stable") {
      connection.setLocalDescription({ type: "rollback" });
    }

    try {
      connection.setRemoteDescription(desc);
    } catch (e) {
      console.log("Error while setting remote description.");
    }

    setupListeners(connection, data);

    connection
      .createAnswer()
      .then(function (answer) {
        setDescriptionAndSendAnswer(answer, connection, data);
      })
      .catch(function (e) {
        console.log(e);
      });
  }

  //set local description and send the answer
  function setDescriptionAndSendAnswer(answer, connection, data) {
    connection.setLocalDescription(answer);

    sendMessage({
      type: "answer",
      answer: answer,
      meetingId: userInfo.meetingId,
      fromUserId: userId,
      toUserId: data.toUserId,
      screen: data.isScreen,
    });
  }

  //handle answer and set remote description
  function handleAnswer(data) {
    let connection;

    if (data.screen) {
      connection = screenConnections[data.fromUserId];
    } else {
      connection = connections[data.fromUserId];
    }

    processAnswer(connection, data.answer);
  }

  //process answer and set remote description
  function processAnswer(connection, answer) {
    try {
      connection.setRemoteDescription(answer);
    } catch (e) {
      console.log("Error while setting remote description.");
    }
  }

  //handle candidate and add ice candidate
  function handleCandidate(data) {
    let connection;

    if (data.screen) {
      connection = screenConnections[data.fromUserId];
    } else {
      connection = connections[data.fromUserId];
    }

    processCandidate(connection, data.candidate);
  }

  //process ice candidates
  function processCandidate(connection, candidate) {
    if (candidate && connection) {
      try {
        connection.addIceCandidate(new RTCIceCandidate(candidate));
      } catch (e) {
        console.log("Error while adding ice candidated: ", e);
      }
    }
  }

  //change the video size on window resize
  window.onresize = function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
      layout();
    }, 20);
  };

  //add local track to the connection,
  //manage remote track,
  //ice candidate and state change event
  function setupListeners(connection, data) {
    let toUserId;
    let toUsername;
    let isScreen = data.isScreen;

    if (!data.reconnection) {
      if (isScreen) {
        if (screenStream)
          screenStream
            .getTracks()
            .forEach((track) => connection.addTrack(track, screenStream));
        toUserId = data.toUserId + "-screen";
        toUsername = usernames[data.toUserId] + "-screen";
      } else {
        localStream
          .getTracks()
          .forEach((track) => connection.addTrack(track, localStream));
        toUserId = data.toUserId;
        toUsername = usernames[data.toUserId];
      }
    }

    connection.onicecandidate = (event) => {
      if (event.candidate) {
        sendMessage({
          type: "candidate",
          candidate: event.candidate,
          meetingId: userInfo.meetingId,
          fromUserId: userId,
          toUserId: data.toUserId, //this must be data.toUserId and not only toUserId
          screen: isScreen,
        });
      }
    };

    connection.ontrack = (event) => {
      // avoid duplicates
      if (document.getElementById(`video-${toUserId}`)) return;

      // === Create container ===
      const containerDiv = document.createElement("div");
      containerDiv.id = `container-${toUserId}`;
      containerDiv.className = `videoContainer${isScreen ? " OT_big screen" : ""}`;

      // === Create video element ===
      const videoRemote = document.createElement("video");
      videoRemote.id = `video-${toUserId}`;
      videoRemote.autoplay = true;
      videoRemote.playsInline = true;
      videoRemote.srcObject = event.streams[0];

      if (!isScreen) {
        videoRemote.classList.add("cam");

        // Restore object-fit if saved in localStorage
        const objectFit = localStorage.getItem("objectFit");
        if (objectFit) {
          videoRemote.style.objectFit = objectFit;
        }
      }

      containerDiv.appendChild(videoRemote);

      // === Moderator Controls ===
      if (!isScreen && isModerator && settings.moderatorRights === "enabled") {
        const hasVideo = event.streams[0].getVideoTracks().length > 0;

        addModeratorButtons(
          containerDiv,
          false,
          !hasVideo,
          toUserId,
          !hasVideo,
        );
      }

      // === Participant List Update ===
      const moderatorIcon = `
                <svg xmlns="http://www.w3.org/2000/svg" 
                    width="16" 
                    height="16" 
                    fill="currentColor" 
                    class="moderator-icon"
                    viewBox="0 0 16 16"
                    title="${languages.moderator}"
                    ${!data.isModerator ? "style='display:none'" : ""}>
                    
                    <path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/>
                    
                </svg>
            `;

      if (!isScreen) {
        $("#participantListBody").append(`
                    <dd class="col-4 mb-3 participant-list-number" data-id="${toUserId}">
                    ${Object.keys(usernames).length + 1}
                    </dd>
                    <dd id="listId-${toUserId}" class="col-8 mb-3" data-id="${toUserId}">
                    ${usernames[toUserId]} ${moderatorIcon}
                    </dd>
                    `);

        $(".participant-count").text(Object.keys(usernames).length + 1);
      }

      // === Username Overlay ===
      const nameWrapper = document.createElement("div");
      nameWrapper.className =
        "jm-start-call-username position-absolute bottom-0 start-0 m-2";

      const nameTag = document.createElement("span");
      nameTag.className = "tag";
      nameTag.innerHTML = `${toUsername} ${moderatorIcon}`;

      nameWrapper.appendChild(nameTag);
      containerDiv.appendChild(nameWrapper);

      if (!isScreen) {
        // === Avatar Overlay ===
        const avatarWrapper = document.createElement("div");
        avatarWrapper.className =
          "jm-call-start-avatar position-absolute top-50 start-50 translate-middle";

        if (!avatars[toUserId]) {
          const avatar = document.createElement("span");
          avatar.className = "avatar avatar-xl rounded";
          avatar.innerText = toUsername[0];
          avatarWrapper.appendChild(avatar);
        }

        containerDiv.appendChild(avatarWrapper);
      }

      // === Add to videos container ===
      videos.appendChild(containerDiv);

      // === Trigger layout refresh ===
      layout();
    };

    connection.addEventListener("connectionstatechange", () => {
      if (
        connection.connectionState === "disconnected" &&
        userId > data.toUserId
      ) {
        connection.restartIce();
      }

      //userId > data.toUserId: make sure only one user creates an offer
      if (
        connection.connectionState === "failed" &&
        connection.signalingState === "stable" &&
        userId > data.toUserId
      ) {
        connection
          .createOffer({
            iceRestart: true,
          })
          .then(function (offer) {
            return connection.setLocalDescription(offer);
          })
          .then(function () {
            sendMessage({
              type: "offer",
              sdp: connection.localDescription,
              meetingId: userInfo.meetingId,
              fromUserId: userId,
              toUserId: data.toUserId,
              screen: data.isScreen,
              reconnection: true,
            });
          })
          .catch(function (e) {
            console.log(languages.error_message, e);
          });
      }

      //don't process other events for screen connection
      if (connection.connectionState === "connected" && !isScreen) {
        if (isModerator) {
          sendMessage({
            type: "currentTime",
            currentTime:
              timer.getTimeValues().minutes * 60 +
              timer.getTimeValues().seconds,
            fromUserId: userId,
            userId: toUserId,
          });
        }
      }
    });
  }

  //kick the participant out of the meeting
  $(document).on("click", ".kick", function () {
    if (confirm(languages.confirmation_kick)) {
      const toUserId = $(this).data("userid");

      $(this).addClass("disabled");

      sendMessage({
        type: "kick",
        toUserId,
        meetingId: userInfo.meetingId,
      });
    }
  });

  //manage users mic
  $(document).on("click", ".mic-admin", function () {
    const toUserId = $(this).data("userid");

    if ($(this).data("muted")) {
      //unmute mic
      $(this).show();
    } else {
      //mute mic
      $(this).hide();

      sendMessage({
        type: "mic-admin",
        toUserId,
        value: true,
        meetingId: userInfo.meetingId,
      });
    }
  });

  //manage users camera
  $(document).on("click", ".camera-admin", function () {
    const toUserId = $(this).data("userid");

    if ($(this).data("muted")) {
      //unmute camera
      $(this).show();
    } else {
      //mute camera
      $(this).hide();

      sendMessage({
        type: "camera-admin",
        toUserId,
        value: true,
        meetingId: userInfo.meetingId,
      });
    }
  });

  //handle mic admin event
  function handleMicAdmin(value) {
    audioMuted = !value;
    manageMic(true);
  }

  //handle camera admin event
  function handleCameraAdmin(value) {
    videoMuted = !value;
    manageCamera(true);
  }

  //handle mic toggle event
  function handleMicToggled(fromUserId, value) {
    const selector = $("#container-" + fromUserId + " .mic-admin");

    if (!selector) return;

    if (value) {
      selector.hide();
    } else {
      selector.show();
    }
  }

  //handle camera toggle event
  function handleCameraToggled(fromUserId, value) {
    const selector = $("#container-" + fromUserId + " .camera-admin");

    if (!selector) return;

    if (value) {
      selector.hide();
    } else {
      selector.show();
    }
  }

  //manage make moderator
  $(document).on("click", ".make-moderator", function () {
    showInfo("This feature is available in paid versions.", false);
  });

  //handle media status from participants
  function handleMediaStatus(data) {
    const { audioMuted, videoMuted, noVideo, fromUserId } = data;
    const containerDiv = document.getElementById(`container-${fromUserId}`);

    addModeratorButtons(
      containerDiv,
      audioMuted,
      videoMuted,
      fromUserId,
      noVideo,
    );
  }

  //add moderator buttons
  function addModeratorButtons(
    containerDiv,
    micMuted,
    cameraMuted,
    userId,
    noVideo,
  ) {
    let buttonsWrapper = document.createElement("div");
    buttonsWrapper.className =
      "jm-video-action d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-2 z-3";
    containerDiv.appendChild(buttonsWrapper);

    const optionsButton = `<div class="dropdown hover-options">
            <a class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
  <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
</svg>
            </a>
            <div class="dropdown-menu dropdown-menu-end" data-bs-theme="dark">
                <a class="dropdown-item meeting-option mic-admin" data-userid="${userId}" data-muted="${micMuted || allMuted}">${languages.turn_off_mic}</a>
                <a class="dropdown-item meeting-option camera-admin" data-userid="${userId}" data-muted="${cameraMuted}" ${noVideo ? "hidden" : ""}>${languages.turn_off_cam}</a>
                <a class="dropdown-item meeting-option make-moderator" data-userid="${userId}">${languages.make_moderator} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
    class="bi bi-gem" viewBox="0 0 16 16">
    <path
        d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z" />
</svg></a>
                <a class="dropdown-item text-danger meeting-option kick" data-userid="${userId}">${languages.kick_user}</a>
            </div>
        </div>`;

    buttonsWrapper.innerHTML = optionsButton;
  }

  //handle when opponent leaves the meeting
  function handleLeave(userId, isModerator) {
    if (isModerator) {
      reload(1);
    }

    let video = document.getElementById("video-" + userId);
    let container = document.getElementById("container-" + userId);

    if (video && container) {
      video.pause();
      video.srcObject = null;
      video.load();
      container.removeChild(video);
      videos.removeChild(container);
      layout();
    }

    let currentConnection = connections[userId] || screenConnections[userId];

    if (currentConnection) {
      currentConnection.close();
      currentConnection.onicecandidate = null;
      currentConnection.ontrack = null;
      delete connections[userId];
    }

    // if (isRecording) removeAudio(userId);

    const selector = $(`#participantListBody dd[data-id="${userId}"]`);

    if (selector) {
      selector.remove();
    }

    if (usernames[userId]) {
      delete usernames[userId];
    }

    $(".participant-count").text(Object.keys(usernames).length + 1);
  }

  //mute/unmute local video
  $(document).on("click", "#toggleVideo", function () {
    //notify the moderator regarding the change
    if (!isModerator && settings.moderatorRights == "enabled") {
      sendMessage({
        type: "cameraToggled",
        videoMuted: !videoMuted,
        meetingId: userInfo.meetingId,
      });
    }

    manageCamera(false);
  });

  //manage camera
  async function manageCamera(byModerator) {
    $("#toggleVideo").addClass("disabled");

    if (videoMuted) {
      enableVideoSelect();

      localStream.getVideoTracks().forEach((track) => (track.enabled = true));
      $("#toggleVideo").html(`
    <svg xmlns="http://www.w3.org/2000/svg" 
         width="16" 
         height="16" 
         fill="currentColor" 
         class="bi bi-camera-video" 
         viewBox="0 0 16 16">
         
        <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2zm11.5 5.175 3.5 1.556V4.269l-3.5 1.556zM2 4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1z"/>
        
    </svg>
`);
      videoMuted = false;
      showInfo(languages.camera_on, false);
    } else {
      disableVideoSelect();

      localStream.getVideoTracks().forEach((track) => (track.enabled = false));
      $("#toggleVideo").html(`
    <svg xmlns="http://www.w3.org/2000/svg" 
         width="16" 
         height="16" 
         fill="currentColor" 
         class="bi bi-camera-video-off" 
         viewBox="0 0 16 16">
         
        <path fill-rule="evenodd" d="M10.961 12.365a2 2 0 0 0 .522-1.103l3.11 1.382A1 1 0 0 0 16 11.731V4.269a1 1 0 0 0-1.406-.913l-3.111 1.382A2 2 0 0 0 9.5 3H4.272l.714 1H9.5a1 1 0 0 1 1 1v6a1 1 0 0 1-.144.518zM1.428 4.18A1 1 0 0 0 1 5v6a1 1 0 0 0 1 1h5.014l.714 1H2a2 2 0 0 1-2-2V5c0-.675.334-1.272.847-1.634zM15 11.73l-3.5-1.555v-4.35L15 4.269zm-4.407 3.56-10-14 .814-.58 10 14z"/>
        
    </svg>
`);
      videoMuted = true;
      showInfo(
        byModerator ? languages.camera_off_moderator : languages.camera_off,
        false,
      );
    }

    $("#toggleVideo").removeClass("disabled");
  }

  //mute/unmute local audio
  $(document).on("click", "#toggleMic", function () {
    //notify the moderator regarding the change
    if (!isModerator && settings.moderatorRights == "enabled") {
      sendMessage({
        type: "micToggled",
        audioMuted: !audioMuted,
        meetingId: userInfo.meetingId,
      });
    }

    manageMic(false);
  });

  //manage mic
  async function manageMic(byModerator) {
    $("#toggleMic").addClass("disabled");

    if (audioMuted) {
      enableAudioSelect();

      localStream.getAudioTracks().forEach((track) => (track.enabled = true));
      $("#toggleMic")
        .html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-mute" viewBox="0 0 16 16">
  <path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3"/>
  <path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z"/>
</svg>`);
      audioMuted = false;
      showInfo(languages.mic_unmute, false);

      initHark();
    } else {
      disableAudioSelect();

      localStream.getAudioTracks().forEach((track) => (track.enabled = false));
      $("#toggleMic")
        .html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-mute" viewBox="0 0 16 16">
  <path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3"/>
  <path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z"/>
</svg>`);
      audioMuted = true;
      showInfo(
        byModerator ? languages.mic_muted_moderator : languages.mic_mute,
        false,
      );

      stopHark();
    }

    $("#toggleMic").removeClass("disabled");
  }

  //leave the meeting
  $(document).on("click", "#leave", function () {
    if (!confirm(languages.confirmation)) return;

    showInfo(languages.meeting_ended, false);
    reload(0);
  });

  //warn the user if he tries to leave the page during the meeting
  window.addEventListener(eventName, function () {
    if (initiated) {
      sendLeaveBeacon("left");
    }

    socket.close();
    Object.keys(connections).forEach((key) => {
      connections[key].close();
      let video = document.getElementById("video-" + key);
      video.pause();
      video.srcObject = null;
      video.load();
      video.parentNode.removeChild(video);
    });
  });

  //enter into bigger video mode with double click on video
  $(document).on("dblclick", "video", function () {
    if (this.id == "previewVideo") return;

    let parentElement = $(this).parent();
    if (parentElement.hasClass("OT_big")) {
      parentElement.removeClass("OT_big");
    } else {
      parentElement.addClass("OT_big");
    }

    layout();
  });

  //toggle picture-in-picture mode with click on video
  $(document).on("click", "video", function () {
    if (isMobile || this.id == "previewVideo") return;

    if (document.pictureInPictureElement) {
      document.exitPictureInPicture();
    } else {
      if (this.readyState === 4 && this.srcObject.getVideoTracks().length) {
        try {
          this.requestPictureInPicture();
        } catch (e) {
          showInfo(languages.no_pip, false);
        }
      } else {
        showInfo(languages.no_video, false);
      }
    }
  });

  //toggle chat panel
  $(document).on("click", ".openChat", function () {
    openChat();
  });

  //open the group chat
  async function openChat() {
    //close ChatGPT if it's open
    if ($("#chatGPTChat").hasClass("chat-show")) {
      $("#chatGPTChat").toggleClass("chat-show chat-hide");
    }

    $("#groupChat").toggleClass("chat-show chat-hide");

    setTimeout(() => {
      layout();
    }, 500);

    if ($(".openChat").hasClass("notify")) {
      $(".openChat").removeClass("notify");
      messageCount = 0;

      $(".groupchat-count").text("").removeClass("badge bg-primary");
    }
  }

  $(document).on("click", ".openChatGPT", function () {
    // Close groupChat if it's open
    if ($("#groupChat").hasClass("chat-show")) {
      $("#groupChat").toggleClass("chat-show chat-hide");
    }

    $("#chatGPTChat").toggleClass("chat-show chat-hide");

    setTimeout(() => {
      layout();
    }, 500);

    if ($(this).hasClass("notify")) {
      $(this).removeClass("notify");
      $(".chatbot-count").text("").removeClass("badge bg-primary");
    }
  });

  //close chat panel
  $(document).on("click", ".close-panel", function () {
    $("#groupChat").toggleClass("chat-show chat-hide");

    setTimeout(() => {
      layout();
    }, 500);
  });

  //close ChatGPT panel
  $(document).on("click", ".close-panel-chatgpt", function () {
    $("#chatGPTChat").toggleClass("chat-show chat-hide");

    setTimeout(() => {
      layout();
    }, 500);
  });

  //copy/share the meeting invitation
  $(document).on("click", ".add", function () {
    let link = location.protocol + "//" + location.host + location.pathname;

    if (navigator.share) {
      try {
        navigator.share({
          title: htmlEscape(settings.appName),
          url: link,
          text: languages.inviteMessage,
        });
      } catch (e) {
        showInfo(e, false);
      }
    } else {
      let inp = document.createElement("textarea");
      inp.style.display = "hidden";
      document.body.appendChild(inp);
      inp.value = languages.inviteMessage + link;
      inp.select();
      document.execCommand("copy", false);
      inp.remove();
      showInfo(languages.link_copied, false);
    }
  });

  //submit the chat form on send message button click
  $("#sendMessage").on("click", () => {
    $("#chatForm").submit();
  });

  //listen for message form submit event and send message
  $(document).on("submit", "#chatForm", function (e) {
    e.preventDefault();

    if (!featureAvailable("text_chat")) return;

    let message = htmlEscape($("#messageInput").val().trim());

    if (message) {
      $("#messageInput").val("");
      appendMessage(message, null, true);

      sendMessage({
        type: "meetingMessage",
        message: message,
        username: userInfo.username,
      });
    }
  });

  //handle message and append it
  async function handlemeetingMessage(data) {
    if ($("#groupChat").hasClass("chat-hide")) {
      $(".openChat").addClass("notify");
      $(".groupchat-count").text(++messageCount).addClass("badge bg-primary");
      notificationTone.play();
    }
    appendMessage(data.message, data.username, false);
  }

  //append message to chat body
  function appendMessage(message, username, self) {
    if ($(".empty-chat-body")) {
      $(".empty-chat-body").remove();
    }

    let messageDiv = `<div class="chat-item">
            <div class="row align-items-end ${self ? "justify-content-end" : ""}">
                <div class="col col-lg-11">
                    <div class="chat-bubble ${self ? "chat-bubble-me" : ""}">
                        <div class="chat-bubble-title">
                            <div class="row">
                                <div class="col chat-bubble-author">${username ? username : languages.you}</div>
                                <div class="col-auto chat-bubble-date">${getTime()}</div>
                            </div>
                        </div>
                        <div class="chat-bubble-body">
                            <p>${linkify(message)}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    $("#groupChat .chat-bubbles").append(messageDiv);
    $("#groupChat .card-body").animate(
      {
        scrollTop: $("#groupChat .chat-bubbles").prop("scrollHeight"),
      },
      1000,
    );
  }

  //get usre's current time
  function getTime() {
    return new Date().toLocaleTimeString([], {
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  $(document).on("click", "#openChatGPT", function () {
    $(".chatgpt-panel").animate({
      width: "toggle",
    });

    if ($(this).hasClass("notify")) {
      $(this).removeClass("notify");
    }
  });

  //close ChatGPT panel
  $(document).on("click", ".close-chatgpt-panel", function () {
    $(".chatgpt-panel").animate({
      width: "toggle",
    });
  });

  //toggle screen share
  $(document).on("click", "#screenShare", function () {
    if (!featureAvailable("screen_share")) return;
    showInfo("This feature is available in paid versions.", false);
  });

  //open file exploler
  $(document).on("click", "#selectFile", function () {
    if (!featureAvailable("file_share")) return;
    showInfo("This feature is available in paid versions.", false);
  });

  //open device settings modal
  $(".openSettings").on("click", async function () {
    $("#settings").modal("show");
    loadMediaDevice(false);
  });

  //set devices in select input
  function gotDevices(deviceInfos) {
    const values = selectors.map((select) => select.value);
    selectors.forEach((select) => {
      while (select.firstChild) {
        select.removeChild(select.firstChild);
      }
    });
    for (let i = 0; i !== deviceInfos.length; ++i) {
      const deviceInfo = deviceInfos[i];
      const option = document.createElement("option");
      option.value = deviceInfo.deviceId;
      if (deviceInfo.kind === "audioinput") {
        option.text =
          deviceInfo.label || `microphone ${audioInputSelect.length + 1}`;
        audioInputSelect.appendChild(option);
      } else if (deviceInfo.kind === "videoinput") {
        option.text =
          deviceInfo.label || `camera ${videoInputSelect.length + 1}`;
        videoInputSelect.appendChild(option);
      }
    }
    selectors.forEach((select, selectorIndex) => {
      if (
        Array.prototype.slice
          .call(select.childNodes)
          .some((n) => n.value === values[selectorIndex])
      ) {
        select.value = values[selectorIndex];
      }
    });
  }

  //get audio constraints
  function getAudioConstraints() {
    const audioSource = audioInputSelect.value;

    return {
      deviceId: audioSource ? { exact: audioSource } : undefined,
      echoCancellation: true,
      noiseSuppression: true,
      autoGainControl: true,
      channelCount: 1,
    };
  }

  //get video constraints
  function getVideoConstraints() {
    if (meetingType == "audio") {
      return false;
    } else {
      return {
        deviceId: videoInputSelect.value
          ? { exact: videoInputSelect.value }
          : undefined,
        width: { exact: $("#" + videoQualitySelect.value).data("width") },
        height: { exact: $("#" + videoQualitySelect.value).data("height") },
      };
    }
  }

  //video input change handler
  videoQualitySelect.onchange = videoInputSelect.onchange = async function () {
    const option = videoQualitySelect.options[videoQualitySelect.selectedIndex];

    if (
      option.getAttribute("id") == "FHD" ||
      option.getAttribute("id") == "4K"
    ) {
      videoQualitySelect.value = "VGA";
      $("#settings").modal("hide");

      showInfo("This feature is available in paid versions.", false);
      return;
    }

    if (
      (features["video_quality"] == "VGA" &&
        option.getAttribute("data-width") > 640) ||
      (features["video_quality"] == "HD" &&
        option.getAttribute("data-width") > 1280) ||
      (features["video_quality"] == "FHD" &&
        option.getAttribute("data-width") > 1920)
    ) {
      videoQualitySelect.value = "VGA";
      if (isModerator) {
        showInfo(languages.feature_not_available, false);
      } else {
        showInfo(languages.premiumFeature, false);
      }
      return;
    }

    if (!(localStream && localStream.getVideoTracks().length)) return;

    constraints = {
      video: getVideoConstraints(),
    };

    try {
      localStream.getVideoTracks().forEach((track) => track.stop());
      let videoStream = await navigator.mediaDevices.getUserMedia(constraints);
      localStream.removeTrack(localStream.getVideoTracks()[0]);
      replaceMediaTrack(videoStream.getVideoTracks()[0]);

      videoSource.value = localStream
        .getVideoTracks()[0]
        .getSettings().deviceId;
      localStorage.setItem("videoQuality", videoQualitySelect.value);
      localStorage.setItem("videoSource", videoSource.value);
    } catch (e) {
      showInfo(e.name, false);
      $("#videoQualitySelect").val("VGA").trigger("change");
    }
  };

  //checks and audio input change handler
  audioSource.onchange = async function () {
    if (!localStream) return;

    constraints = {
      audio: getAudioConstraints(),
    };

    try {
      localStream.getAudioTracks().forEach((track) => track.stop());
      let audioStream = await navigator.mediaDevices.getUserMedia(constraints);
      localStream.removeTrack(localStream.getAudioTracks()[0]);
      replaceMediaTrack(audioStream.getAudioTracks()[0]);
    } catch (e) {
      console.log(languages.no_device + e.name);
    }
  };

  //replace video track and add track to the localStream
  function replaceMediaTrack(track) {
    if (localStream) localStream.addTrack(track);

    Object.values(connections).forEach((connection) => {
      let sender = connection.getSenders().find(function (s) {
        return s.track.kind === track.kind;
      });

      sender.replaceTrack(track);
    });
  }

  //detect and replace text with url
  function linkify(text) {
    var urlRegex =
      /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi;
    return text.replace(urlRegex, function (url) {
      return '<a href="' + url + '" target="_blank">' + url + "</a>";
    });
  }

  //initiate keyboard shortcuts
  function initKeyShortcuts() {
    $(document).on("keydown", function (e) {
      if (
        $("#messageInput, #chatGPTmessageInput").is(":focus") ||
        window.picker?.isPickerVisible()
      )
        return;

      switch (e.key) {
        case "C":
        case "c":
          $(".chat-panel").animate({
            width: "toggle",
          });

          if ($("#openChat").hasClass("notify")) {
            $("#openChat").removeClass("notify");
            messageCount = 0;
          }
          break;
        case "F":
        case "f":
          if ($(".chat-panel").is(":hidden")) {
            $(".chat-panel").animate({
              width: "toggle",
            });
          }
          $("#selectFile").trigger("click");
          break;
        case "A":
        case "a":
          $("#toggleMic").trigger("click");
          break;
        case "L":
        case "l":
          $("#leave").trigger("click");
          break;
        case "V":
        case "v":
          if (meetingType === "video") $("#toggleVideo").trigger("click");
          break;
        case "S":
        case "s":
          $("#screenShare").trigger("click");
          break;
      }
    });
  }

  //check if the feature is available in the current meeting plan
  function featureAvailable(feature) {
    let result = parseInt(features[feature]);
    if (!result) showInfo(languages.feature_not_available, false);
    return result;
  }

  //toggle whiteboard
  $(document).on("click", "#whiteboard", function () {
    if (!featureAvailable("whiteboard")) return;
    showInfo("This feature is available in paid versions.", false);
  });

  //notify participants about hand raise
  $(document).on("click", "#raiseHand", function () {
    if (!featureAvailable("hand_raise")) return;

    showInfo(languages.hand_raised_self, false);

    sendMessage({
      type: "raiseHand",
      username: userInfo.username,
    });
  });

  //toggle recording
  $(document).on("click", ".recording", function () {
    if (!featureAvailable("recording")) return;
    showInfo("This feature is available in paid versions.", false);
  });

  // ensure audio context is initialized
  function ensureMixer() {
    if (!audioCtx) {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      audioDest = audioCtx.createMediaStreamDestination();
    }

    if (audioCtx.state === "suspended") audioCtx.resume().catch(() => { });
  }

  //add audio to audio nodes
  function addAudio(id, stream) {
    if (!stream) return;
    const track = stream.getAudioTracks?.()[0];
    if (!track || audioNodes[id]) return;

    ensureMixer();
    const audioOnly = new MediaStream([track]);
    const node = audioCtx.createMediaStreamSource(audioOnly);
    node.connect(audioDest);
    audioNodes[id] = node;
  }

  //remove audio from audio nodes
  function removeAudio(id) {
    const node = audioNodes[id];
    if (!node) return;
    try {
      node.disconnect();
    } catch (e) { }
    delete audioNodes[id];
  }

  //get fake video track
  function getFakeVideoTrack() {
    if (fakeVideo?.track?.readyState === "live") return fakeVideo.track;

    const canvas = document.getElementById("audioOnly");
    canvas.width = 640;
    canvas.height = 360;

    const ctx = canvas.getContext("2d");

    // draw forever so the video track always has fresh frames
    let running = true;
    const draw = () => {
      if (!running) return;
      ctx.fillStyle = "#000";
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      ctx.fillStyle = "#fff";
      ctx.font = "16px Arial";
      ctx.fillText("Audio only", 20, 30);

      requestAnimationFrame(draw);
    };
    draw();

    const stream = canvas.captureStream(10);
    const track = stream.getVideoTracks()[0];

    fakeVideo = {
      stream,
      track,
      stop: () => {
        running = false;
        try {
          track.stop();
        } catch (e) { }
      },
    };

    return track;
  }

  //iPhone fix - while clicking on kick button, video got paused
  localVideo.addEventListener("pause", (event) => {
    localVideo.play();
  });

  //handle video object fit change
  $(document).on("change", "#videoObjectFit", function () {
    $(".cam").css("object-fit", this.value);
    localStorage.setItem("objectFit", this.value);
  });

  //handle emojis
  const trigger = document.querySelector("#emojiPicker");
  trigger.addEventListener("click", async function () {
    showInfo("This feature is available in paid versions.", false);
  });

  //clear the info
  function clearToaster() {
    const toast = $(".toast");
    toast.fadeOut();
    toast.remove();
  }

  //listen on mute all click
  $(document).on("click", ".muteAll", function () {
    if (!isModerator) return;
    showInfo("This feature is available in paid versions.", false);
  });

  //set date if not set
  if ($("#date").text() == "00-00-0000") {
    const options = {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    };

    $("#date").text(
      new Date().toLocaleDateString("en-IN", options).replaceAll("/", "-"),
    );
  }

  //set time if not set
  if ($("#time").text() == "00:00 00") {
    $("#time").text(
      new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }),
    );
  }

  //set timezone if not set
  if ($("#timezone").text() == "-") {
    $("#timezone").text(Intl.DateTimeFormat().resolvedOptions().timeZone);
  }

  function initMeetingLog() {
    const csrf = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    fetch("/meeting/init-log", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
      },
      body: JSON.stringify({
        meeting_id: userInfo.meetingId, // slug in meetings.meeting_id
        user_id: settings.user_id,
        participant_name: userInfo.username,
        is_moderator: !!isModerator,
      }),
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (!data.success) {
          console.error("init-log failed:", data);
          return;
        }

        window.meetingDetailId = data.meeting_detail_id;
        window.meetingLogId = data.meeting_log_id;
      })
      .catch(function (err) {
        console.error("init-log error:", err);
      });
  }

  function sendLeaveBeacon(reason) {
    try {
      if (!window.meetingDetailId || !window.meetingLogId) return;

      const payload = JSON.stringify({
        _token: document
          .querySelector("[name=csrf-token]")
          .getAttribute("content"),
        meeting_detail_id: window.meetingDetailId,
        meeting_log_id: window.meetingLogId,
        user_id: settings.user_id,
        status: reason,
      });

      // Send as Blob with JSON content type — much more reliable than FormData
      const blob = new Blob([payload], { type: "application/json" });
      navigator.sendBeacon("/meeting/leave-log", blob);
    } catch (e) {
      console.log("error in sendBeacon", e);
    }
  }
})();
