/*jshint esversion: 6 */
/*jshint node: true */
//handle join event
const fetch = require('node-fetch').default;
const cron = require('node-cron');
let meetings = {};

const usersByUserId = new Map();  // userId → socket.id
const messageHistory = new Map();  // meetingId → [ { message, username, timestamp }… ]

//handle join event
function handleJoin(socket, data) {
    if (!data.screen) {
        const meetingId = data.meetingId;

        socket.meetingId = meetingId;
        socket.moderator = data.isModerator;
        socket.screen = data.screen;
        socket.join(meetingId);
        socket.userId = data.userId;

        //create meeting if not exists (when the moderator rights are off)
        if (!meetings[meetingId]) {
            meetings[meetingId] = {
                users: [],
                ...meetings[meetingId]
            }
        }

        meetings[meetingId].users.push({ "userId": data.userId, isModerator: data.isModerator });

        // record the current details
        setSocketDetails(socket, data.userId, socket.id, meetingId, data.isModerator);
    }

    sendToMeeting(socket, data);
}

//handle disconnect event
function handleDisconnect(socket, io, meetingId, userId) {
    //if the socket is moderator and it is not a screen then delete from meetings
    if (socket.moderator && !socket.screen) delete meetings[socket.meetingId];

    // remove mapping
    usersByUserId.delete(userId);

    if (meetings[meetingId]) {
        meetings[meetingId].users = meetings[meetingId].users.filter(item => item.userId !== userId);
    }

    //delete the messageHistory and meetings
    if (socket.moderator || (meetings[meetingId] && !meetings[meetingId].users.length)) {
        messageHistory.delete(meetingId);
        delete meetings[meetingId];
    }

    //notify and leave room
    sendToMeeting(socket, {
        type: 'leave',
        userId,
        isModerator: socket.moderator
    });
}

//handle get missed messages
function handleGetMissedMessages(socket, data) {
    const since = data.since;
    const hist = messageHistory.get(socket.meetingId) || [];

    // filter out only messages after the given timestamp
    const missed = hist.filter(m => m.timestamp > since && socket.userId !== m.userId && (m.toUserId === socket.userId || m.toUserId === 'all'));

    if (missed.length) {
        // send back only to this socket
        socket.emit('message', { type: 'messageHistory', messages: missed });
    }
}

//check meeting length and moderator availibility
function handleCheckMeeting(socket, data, io) {
    const meetingId = data.meetingId;

    let resultObj;
    let result = !io.sockets.adapter.rooms.get(meetingId) || io.sockets.adapter.rooms.get(meetingId).size < data.userLimit;

    if (result) {
        const moderator = confirmModerator(data.moderator, meetingId);

        if (moderator) {
            meetings[meetingId] = {
                isModeratorPresent: true,
                moderator: data.userId,
                users: []
            };

            //directly allow the user if he is the moderator
            resultObj = { type: 'checkMeetingResult', result: true, message: '' };
        } else if (data.authMode == "disabled" || data.moderatorRights == "disabled") {
            //directly allow the user if the moderator rights are disabled
            resultObj = { type: 'checkMeetingResult', result: true, message: '' };
        } else if (meetings[meetingId] && meetings[meetingId].isModeratorPresent) {
            //notify the moderator for new request
            sendToPeer(io, { type: 'permission', toUserId: meetings[meetingId].moderator, fromSocketId: socket.id, username: data.username, meetingId: meetingId });
            resultObj = { type: 'info', message: 'please_wait' };
        } else {
            //do not allow anyone in the meeting before moderator joins
            resultObj = { type: 'checkMeetingResult', result: false, message: 'not_started' };
        }
    } else {
        //user limit is reached
        resultObj = { result: false, message: 'meeting_full' };
    }

    io.to(socket.id).emit('message', resultObj);
}

//confirm moderator
function confirmModerator(isModerator, meetingId) {
    if (!isModerator) return false;

    return !(meetings[meetingId] && meetings[meetingId].isModeratorPresent);
}

//save message histroy
function saveMessageHistory(meetingId, msgRecord) {
    const history = messageHistory.get(meetingId) || [];
    history.push(msgRecord);
    messageHistory.set(meetingId, history);
}

//notify the moderator for mic toggled
function handleMicToggled(socket, data, io) {
    sendToPeer(io, { type: 'micToggled', toUserId: meetings[data.meetingId].moderator, fromUserId: socket.userId, audioMuted: data.audioMuted, meetingId: socket.meetingId });
}

//notify the moderator for camera toggled
function handleCameraToggled(socket, data, io) {
    sendToPeer(io, { type: 'cameraToggled', toUserId: meetings[data.meetingId].moderator, fromUserId: socket.userId, videoMuted: data.videoMuted, meetingId: socket.meetingId });
}

//send the message to particular user
function sendToPeer(io, data) {
    const toUserId = data.toUserId
    const socketId = getUserSocketId(toUserId);

    const msgRecord = {
        ...data,
        timestamp: Date.now(),
        userId: null,
        toUserId
    };

    io.to(socketId).emit('message', msgRecord);

    saveMessageHistory(data.meetingId, msgRecord);
}

//send the message to particular meeting
function sendToMeeting(socket, data) {
    const meetingId = socket.meetingId;
    const msgRecord = {
        ...data,
        userId: socket.userId,
        timestamp: Date.now(),
        toUserId: 'all'
    };

    socket.broadcast
        .to(meetingId)
        .emit('message', msgRecord);

    if (data.type === 'speaking') {
        return;
    }

    saveMessageHistory(meetingId, msgRecord);
}

//handle permission result
function handlePermissionResult(io, { socketId, ...data }) {
    io.to(socketId).emit('message', data);
}

//get user's socket ID
function getUserSocketId(userId) {
    return usersByUserId.get(userId);
}

//check details
function checkDetails() {
    fetch(process.env.DOMAIN + '/check-details')
        .then(res => res.text())
        .then(result => {
            if (!result) process.exit(1);
        });
}

//set socket details
function setSocketDetails(socket, userId, socketId, meetingId, isModerator) {
    usersByUserId.set(userId, socketId);

    socket.join(meetingId);

    socket.meetingId = meetingId;
    socket.moderator = isModerator;
    socket.userId = userId;
}

module.exports = function (io) {
    checkDetails();

    cron.schedule('0 0 * * 0', () => {
        checkDetails();
    });

    //handle connection event
    io.sockets.on('connection', function (socket) {
        const handshake = socket.request;
        const userId = handshake._query['userId'];
        const meetingId = handshake._query['meetingId'];
        const isModerator = handshake._query['isModerator'];

        if (getUserSocketId(userId)) {
            // update the current socket details
            setSocketDetails(socket, userId, socket.id, meetingId, isModerator);
        }

        socket.on('message', function (data, ack) {
            data = JSON.parse(data);

            //send acknoledgement
            if (ack) ack({ ok: true });

            switch (data.type) {
                case 'join':
                    handleJoin(socket, data);
                    break;
                case 'checkMeeting':
                    handleCheckMeeting(socket, data, io);
                    break;
                case 'permissionResult':
                    handlePermissionResult(io, data);
                    return;
                case 'offer':
                case 'answer':
                case 'candidate':
                case 'currentTime':
                case 'kick':
                    sendToPeer(io, data);
                    break;
                case 'meetingMessage':
                case 'raiseHand':
                case 'sync':
                    sendToMeeting(socket, data);
                    break;
                case "speaking":
                    sendToMeeting(socket, data);
                    break;
                case 'micToggled':
                    handleMicToggled(socket, data, io);
                    break;
                case 'cameraToggled':
                    handleCameraToggled(socket, data, io);
                    break;
                case 'mic-admin':
                case 'camera-admin':
                case 'notifyMediaStatus':
                    sendToPeer(io, data);
                    break;
                case 'moderatorButtons':
                    sendToPeer(io, data);
                    break;
                case 'getMissedMessages':
                    handleGetMissedMessages(socket, data);
                    break;
            }
        });

        socket.on('disconnect', function (reason) {
            const meetingId = socket.meetingId;
            const userId = socket.userId;

            if (!userId) return;

            if (
                reason === 'transport close' ||
                reason === 'ping timeout' ||
                reason === 'transport error'
            ) {
                //wait for 60 seconds for the user to come back online
                setTimeout(() => {
                    const currentSocketId = getUserSocketId(userId);

                    //disconnect if the user never comes back online
                    if (currentSocketId && currentSocketId == socket.id) {
                        handleDisconnect(socket, io, meetingId, userId);
                    }
                }, 60 * 1000);
            } else {
                handleDisconnect(socket, io, meetingId, userId);
            }

            socket.leave(meetingId);
        });
    });
}