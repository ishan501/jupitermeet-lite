(function () {
  "use strict";

  const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

  if (isMobile) {
    $('.jm-meeting-detail').hide();
    $('.jm-meeting-card').removeClass('active');
  }

  function showToast(message) {
    let toastElement = $(".toast");

    toastElement.find(".toast-body").text(message);
    toastElement.addClass("show");

    setTimeout(function () {
      toastElement.removeClass("show");
    }, 5000);
  }

  //copy meeting link
  $("#copyMeetingLink").on("click", function () {
    navigator.clipboard.writeText(this.getAttribute("data-link"));
    showToast(languages.link_copied);
  });

  $(document).on("click", ".mobile-back-btn", function () {
    $('.meeting-list').show();
    $('.jm-meeting-header').show();
    $('.meeting-search-box').show();

    $('.jm-meeting-detail').hide();

  });


  //update meeting details when a meeting is clicked
  $(document).on("click", ".jm-meeting-card", function () {
    let meetingTitle = $(this).attr("data-title"),
      meetingDescription = $(this).attr("data-description"),
      password = $(this).attr("data-password"),
      meetingIdAuto = $(this).attr("data-auto"),
      meetingDate = $(this).attr("data-date"),
      meetingTime = $(this).attr("data-time"),
      meetingTimezone = $(this).attr("data-timezone");

    meetingId = $(this).attr("data-id");

    $(".active").removeClass("active");
    $(this).addClass("active");

    updateDetail(
      meetingTitle,
      meetingDescription,
      password,
      meetingIdAuto,
      meetingDate,
      meetingTime,
      meetingTimezone
    );

    if (isMobile) {
      $('.meeting-list').hide();
      $('.jm-meeting-header').hide();
      $('.meeting-search-box').hide();

      $('.jm-meeting-detail').show();
    }
  });

  //ajax call to create a meeting
  $("#createMeetingsForm").on("submit", function (e) {
    e.preventDefault();
    $("#createMeetingButton").attr("disabled", true);

    const form = $(this);
    const formData = form.serializeArray();
    const fields = [
      "title",
      "description",
      "password",
      "date",
      "time",
      "timezone",
    ];

    //remove all the errors
    fields.forEach((field) => {
      form
        .find("[name='" + field + "']")
        .removeClass("is-invalid")
        .siblings(".invalid-feedback")
        .text("");
    });

    $.ajax({
      url: $(this).data("action"),
      data: htmlEscapeArray(formData),
      type: "post",
    })
      .done(function (data) {
        data = JSON.parse(data);
        $("#createMeetingButton").attr("disabled", false);

        if (data.success) {
          showToast(languages.meeting_created);
          $("#createMeetingsForm")[0].reset();
          $(".empty").attr("hidden", true);
          $(".meetingDetail").removeAttr("hidden");
          $("#createMeetingModal").modal("hide");

          addMeeting(data.data);

          $('.jm-meeting-detail').removeAttr("hidden");

          if (isMobile) {
            $(".mobile-back-btn").click();
          }
        } else {
          showToast(data.error);
          $("#createMeetingModal").modal("hide");
        }
      })
      .catch(function (error) {
        const validationErrors = error.responseJSON.errors;

        if (!validationErrors) {
          showToast(languages.error_occurred);
          $("#createMeetingButton").attr("disabled", false);
          return;
        }

        //show errors
        Object.entries(validationErrors).forEach(([field, value]) => {
          form
            .find("[name='" + field + "']")
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .text(value[0]);
        });

        showToast(languages.error_occurred);
        $("#createMeetingButton").attr("disabled", false);
      });
  });

  //add newly created meeting to the div
  function addMeeting(data) {
    meetingId = data.id;

    $(".empty").attr("hidden", true);

    if ($(".active-meeting")) {
      $(".active-meeting").removeClass("active-meeting");
    }

    $(".meeting-list").prepend(buildMeetingList(data));

    $(".jm-meeting-card")[0].click();
  }

  //build the meeting list
  function buildMeetingList(data) {
    //get the template element
    const template = document.getElementById("meetingTemplate");

    //clone the template
    const meetingCard = template.querySelector("a").cloneNode(true);

    //populate the cloned template with data
    meetingCard.setAttribute("data-title", data.title);
    meetingCard.setAttribute(
      "data-description",
      data.description ? data.description : "-"
    );
    meetingCard.setAttribute("data-id", data.id);
    meetingCard.setAttribute("data-auto", data.meeting_id);
    meetingCard.setAttribute(
      "data-password",
      data.password ? data.password : "-"
    );
    meetingCard.setAttribute("data-date", formatDate(data.date));
    meetingCard.setAttribute("data-time", formatTime(data.time));
    meetingCard.setAttribute(
      "data-timezone",
      data.timezone ? data.timezone : ""
    );
    meetingCard.querySelector("h3").textContent = data.title;
    meetingCard.querySelector("h3").classList.add("text-truncate");
    meetingCard.querySelector(".text-secondary.text-truncate").textContent =
      data.description;
    meetingCard.querySelector(
      ".text-secondary:first-child"
    ).textContent = `Meeting ID: ${data.meeting_id}`;

    if (data.date || data.time || data.timezone) {
      meetingCard.querySelector(".jm-meeting-date").innerHTML = `
        <span class="icon-tabler">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4"></path>
            <path d="M18 14v4h4"></path>
            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
            <path d="M15 3v4"></path>
            <path d="M7 3v4"></path>
            <path d="M3 11h16"></path>
          </svg>
        </span>
        <p class="text-truncate m-0">${data.date} ${data.time} ${data.timezone}</p>`;
    }



    return meetingCard;
  }

  //set meeting details
  function updateDetail(
    title,
    description,
    password,
    meetingIdAuto,
    date,
    time,
    timezone
  ) {
    title = title.trim();
    $(
      '#meetingTitleDetail, .jm-meeting-card[data-id="' +
      meetingId +
      '"] .meeting-title'
    ).text(title);
    $("#meetingDescriptionDetail").text(description ? description : "-");
    $(
      '.jm-meeting-card[data-id="' + meetingId + '"] .meeting-description'
    ).text(description ? description : "-");
    $("#meetingStart").attr(
      "href",
      "/meeting/" + meetingIdAuto
    );
    $("#meetingHistory").attr(
      "href",
      "/meeting-history/" + meetingIdAuto
    );
    $("#invite, #edit, #delete").attr("data-id", meetingId);
    $("#meetingPasswordDetail").text(password ? password : "-");
    $("#meetingIdDetail").text(meetingIdAuto);
    $("#meetingDateDetail").text(date ? date : "-");
    $("#meetingTimeDetail").text(time ? time : "-");
    $("#meetingTimezoneDetail").text(timezone ? timezone : "-");
  }

  //copy meeting URL to the clipboard
  $("#copyParticularMeeting").on("click", function (e) {
    e.preventDefault();
    let link = $("#meetingStart").attr("href");

    const inp = document.createElement("input");
    inp.style.display = "hidden";
    document.body.appendChild(inp);
    inp.value = window.location.origin + link;
    inp.select();
    document.execCommand("copy", false);
    inp.remove();
    showToast(languages.link_copied);
  });

  //ajax call to delete a meeting
  $(".delete").on("click", function (e) {
    e.preventDefault();

    if (confirm(languages.confirmation)) {
      $.ajax({
        url: $(this).data("action"),
        data: {
          id: meetingId,
        },
        type: "post",
      })
        .done(function (data) {
          data = JSON.parse(data);

          if (data.success) {
            showToast(languages.meeting_deleted);

            $(".meeting-list .active").remove();

            if (data.noMeetingCount) {
              $(".meetingDetail").attr("hidden", true);
              $(".empty, #emptyDetails").removeAttr("hidden");
              $('.jm-meeting-detail').hide();
            } else {
              $(".jm-meeting-card")[0].click();
            }

            if (isMobile) {
              $(".mobile-back-btn").click();
            }

          } else {
            showToast(data.error);
          }
        })
        .catch(function () {
          showToast(languages.error_occurred);
          $("#createMeetingButton").attr("disabled", false);
        });
    }
  });

  //generate a random meeting ID for new meetings
  function generateMeetingId() {
    return Math.random().toString(36).substr(2, 9);
  }

  //set meeting ID in the modal
  $("#createMeetingModal").on("show.bs.modal", function () {
    let meetingId = generateMeetingId();

    $("#createMeetingId").text(meetingId);
    $("#createMeetingsFormId").val(meetingId);
  });

  //set the details when the edit modal is opened
  $("#editMeetingModal").on("show.bs.modal", function () {
    let id = meetingId,
      meetingCard = $('.jm-meeting-card[data-id="' + id + '"]'),
      title = $("#meetingTitleDetail").text().trim(),
      description = $("#meetingDescriptionDetail").text().trim(),
      password = meetingCard.attr("data-password"),
      meetingIdAuto = meetingCard.attr("data-auto"),
      meetingDate = meetingCard.attr("data-date"),
      meetingTime = meetingCard.attr("data-time"),
      meetingTimezone = meetingCard.attr("data-timezone");

    $("#titleEdit").val(title);
    $("#descriptionEdit").val(description == "-" ? "" : description);
    $("#passwordEdit").val(password == "-" ? "" : password);
    $("#meetingIdEdit").text(meetingIdAuto);
    $("#meetingsFormIdEdit").val(meetingId);
    $("#dateEdit").val(meetingDate == "-" ? "" : formatDate(meetingDate));
    $("#timeEdit").val(meetingTime == "-" ? "" : formatTime(meetingTime));
    $("#timezoneEdit").val(meetingTimezone == "-" ? "" : meetingTimezone);
  });

  //ajax call to save the edited meeting
  $("#editMeetingsForm").on("submit", function (e) {
    e.preventDefault();

    $("#updateMeetingButton").attr("disabled", true);

    $.ajax({
      url: $(this).data("action"),
      data: htmlEscapeArray($(this).serializeArray()),
      type: "post",
    })
      .done(function (data) {
        data = JSON.parse(data);
        $("#updateMeetingButton").attr("disabled", false);

        if (data.success) {
          showToast(languages.meeting_updated);
          $("#editMeetingsForm")[0].reset();
          $("#editMeetingModal").modal("hide");
          let meetingElement = $(".meeting-list").find(
            `[data-id='${data.data.id}']`
          );

          meetingElement.attr("data-title", data.data.title);
          meetingElement.attr("data-description", data.data.description);
          meetingElement.attr("data-password", data.data.password);
          meetingElement.attr("data-date", data.data.date);
          meetingElement.attr("data-time", data.data.time);
          meetingElement.attr("data-timezone", data.data.timezone);

          meetingElement.find(".meeting-title").text(data.data.title);
          meetingElement
            .find(".meeting-description")
            .text(data.data.description);
          if (data.data.date || data.data.time || data.data.timezone) {
            meetingElement
              .find(".jm-meeting-date")
              .html(
                '<span class="icon-tabler">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" ' +
                'stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" ' +
                'class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">' +
                '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>' +
                '<path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4"/>' +
                '<path d="M18 14v4h4"/>' +
                '<path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>' +
                '<path d="M15 3v4"/>' +
                '<path d="M7 3v4"/>' +
                '<path d="M3 11h16"/>' +
                "</svg>" +
                "</span> " +
                '<p class="text-truncate m-0">' +
                data.data.date +
                " " +
                data.data.time +
                " " +
                data.data.timezone +
                "</p>"
              );
          }

          updateDetail(
            data.data.title,
            data.data.description,
            data.data.password,
            data.data.meeting_id,
            data.data.date,
            data.data.time,
            data.data.timezone
          );
        } else {
          showToast(data.error);
          $("#editMeetingModal").modal("hide");
        }
      })
      .catch(function () {
        showToast(languages.error_occurred);
        $("#updateMeetingButton").attr("disabled", false);
      });
  });

  //to prevent XSS vulnerability
  function htmlEscapeArray(input) {
    let data = {};
    $.each(input, function () {
      data[this.name] = this.value
        .replace(/&/g, "")
        .replace(/"/g, "")
        .replace(/'/g, "")
        .replace(/</g, "")
        .replace(/>/g, "");
    });
    return data;
  }

  //format date
  function formatDate(date) {
    return date ? date.split("-").reverse().join("-") : "-";
  }

  //format time
  function formatTime(time12h) {
    if (!time12h || time12h == "-") return "-";

    const [time, modifier] = time12h.split(" ");
    let [hours, minutes] = time.split(":");

    if (hours === "12") {
      hours = "00";
    }

    if (modifier === "PM") {
      hours = parseInt(hours, 10) + 12;
    }

    return hours + ":" + minutes;
  }

  $("#Invite").on("show.bs.modal", function () {
    $(".jm-invite-list .list-group").html("");
    $("#inviteId").val(meetingId);

    $.ajax({
      url: "get-invites",
      data: { id: meetingId },
    })
      .done(function (data) {
        data = JSON.parse(data);

        if (data.success) {
          data.data.forEach(function (email) {
            let section =
              '<div class="list-group-item list-group-item-action">' +
              email +
              "</div>";
            $(".jm-invite-list .list-group").prepend(section);
          });
        } else {
          showToast(languages.error_occurred);
        }
      })
      .catch(function () {
        showToast(languages.error_occurred);
      });
  });

  $("#inviteForm").on("submit", function (e) {
    e.preventDefault();

    const emails = $("#inviteEmail").val();
    showToast(languages.sending_invite);


    //reset form and tomselect
    $(this)[0].reset();
    $("#resetInviteForm").click();

    $.ajax({
      url: "send-invite",
      data: {
        id: inviteId.value,
        emails: JSON.stringify(emails),
      },
      type: "post",
    })
      .done(function (data) {
        data = JSON.parse(data);

        if (data.success) {
          $("#inviteEmail").val(null).trigger("change");
          showToast(data.message);
        } else {
          showToast(data.error);
          $("#Invite").modal("hide");
        }
      })
      .catch(function () {
        showToast(languages.error_occurred);
      });
  });

  //to prevent XSS vulnerability
  function htmlEscapeArray(input) {
    let data = {};
    $.each(input, function () {
      data[this.name] = this.value
        .replace(/&/g, "")
        .replace(/"/g, "")
        .replace(/'/g, "")
        .replace(/</g, "")
        .replace(/>/g, "");
    });
    return data;
  }

  //convert date
  function convertDate(date) {
    let x = date.split("-");
    return x[1] + "-" + x[0] + "-" + x[2];
  }

  //get ISO string
  function getISOString(dateTime) {
    return dateTime
      .toISOString()
      .replace(/-/g, "")
      .replace(/:/g, "")
      .slice(0, 15);
  }

  //get ISO string outlook
  function getISOStringOutlook(dateTime) {
    return dateTime.toISOString().slice(0, 19);
  }
})();
