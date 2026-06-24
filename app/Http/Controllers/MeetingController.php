<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingDetail;
use App\Models\MeetingLog;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class MeetingController extends Controller
{
    //show meeting page
    public function meeting($id)
    {
        $meeting = new \stdClass();

        if (getSetting('AUTH_MODE') == 'enabled') {
            $meeting = Meeting::where(['meeting_id' => $id, 'status' => 'active'])->first();

            if (!$meeting) {
                return redirect('/')->withErrors(__('The meeting does not exist'));
            }

            $meeting->features = getUserPlanFeatures($meeting->user_id);
        } else {
            $meeting->title = __('Meeting');
            $meeting->meeting_id = $id;
            $meeting->description = '-';
            $meeting->password = null;
            $meeting->user_id = 0;
            $meeting->features = Plan::first()->features;
            $meeting->date = null;
            $meeting->time = null;
            $meeting->timezone = '';
        }

        $meeting->isModerator = Auth::user() && getSetting('MODERATOR_RIGHTS') == "enabled" ? Auth::user()->id == $meeting->user_id : false;
        $meeting->username = Auth::user() ? Auth::user()->username : '';
        $meeting->timeLimit = $meeting->features->time_limit;
        $meeting->userLimit = $meeting->features->user_limit ?? 5;
        $meeting->isAdmin = Auth::user() && getSetting('MODERATOR_RIGHTS') == "enabled" ? Auth::user()->role == 'admin' : false;

        return view('meeting', [
            'page' => __('Meeting'),
            'meeting' => $meeting
        ]);
    }

    //get the application details and send it to the user
    public function getDetails()
    {
        $details = new stdClass();
        $details->stunUrl = getSetting('STUN_URL');
        $details->turnUrl = getSetting('TURN_URL');
        $details->turnUsername = getSetting('TURN_USERNAME');
        $details->turnPassword = getSetting('TURN_PASSWORD');
        $details->defaultUsername = getSetting('DEFAULT_USERNAME');
        $details->appName = getSetting('APPLICATION_NAME');
        $details->signalingURL = getSetting('SIGNALING_URL');
        $details->authMode = getSetting('AUTH_MODE');
        $details->moderatorRights = getSetting('MODERATOR_RIGHTS');
        $details->aiChatbot = getSetting('AI_CHATBOT');
        $details->user_id = getAuthUserInfo('id');


        return json_encode(['success' => true, 'data' => $details]);
    }
    //api to store the file in storage folder when it is uploaded in chat
    public function fileUploads(Request $request)
    {
        try {
            if (!$request->meetingId) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 500,
                    'message' => __('Something went wrong'),
                ]);
            }

            $meeting = Meeting::where('meeting_id', $request->meetingId)->first();

            if (!isset($meeting)) {
                $user = User::where('username', $request->meetingId)->first();
            } else {
                $user = $meeting->user;
            }

            $maxFileSizeMB = $user->plan->features->max_filesize;
            $maxFileSizeKB = $maxFileSizeMB * 1024;

            $validator = Validator::make($request->all(), [
                'file' => "required|file|max:$maxFileSizeKB",
                'meetingId' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 500,
                    'message' => __('The uploaded file exceeds the maximum allowed size.'),
                ]);
            }


            // get the file and meeting id  
            $file = $request->file('file');
            $meetingId = $request->meetingId;

            //set file folder path
            $folderPath = 'file_uploads/' . $meetingId;

            // store the file in declared path
            $originalFilename = $file->getClientOriginalName();
            $path = $file->storeAs($folderPath, $originalFilename);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'file_name' => $originalFilename,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => __('Something went wrong'),
            ]);
        }
    }

    // api to delete the folder when meeting ends
    public function deleteFolder(Request $request)
    {
        try {

            //get the folder path
            $folderPath = storage_path("app/public/file_uploads/" . $request->meetingId);

            //if folder exists in the path then delete it
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

        } catch (\Exception $e) {
            Log::info('Error while deleting the meeting file folder' . $e->getMessage());
        }
    }

    //check if meeting password is valid or not
    public function checkMeetingPassword(Request $request)
    {
        $meeting = Meeting::find($request->id);

        if ($meeting->password == $request->password) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    public function getNodejsDetails(Request $request)
    {
        $apiToken = $request->header('X-Api-Token');
        $validToken = env('SIGNALING_TOKEN');

        if (!$apiToken || $apiToken !== $validToken) {
            return response()->json(['success' => false, 'message' => 'Unauthorized domain'], 403);
        }

        $nodejs = new stdClass();
        $nodejs->keyPath = getSetting('KEY_PATH');
        $nodejs->certPath = getSetting('CERT_PATH');
        $nodejs->port = getSetting('PORT');
        $nodejs->aiChatbot = getSetting('AI_CHATBOT');
        $nodejs->aiChatbotApiKey = getSetting('AI_CHATBOT_API_KEY');
        $nodejs->aiChatbotApiUrl = getAiChatbotUrl(getSetting('AI_CHATBOT'));
        $nodejs->aiChatbotModel = getSetting('AI_CHATBOT_MODEL');
        $nodejs->aiChatbotSeconds = getSetting('AI_CHATBOT_SECONDS');
        $nodejs->aiChatbotMessageLimit = getSetting('AI_CHATBOT_MESSAGE_LIMIT');
        $nodejs->aiChatbotMaxConversationLength = getSetting('AI_CHATBOT_MAX_CONVERSATION_LENGTH');

        return json_encode(['success' => true, 'data' => $nodejs]);
    }

    // log meeting join and leave with timestamps and calculate duration, also end meeting when all participants left
    public function initLog(Request $request)
    {
        // validate request
        $request->validate([
            'meeting_id' => 'required|string|max:255',
            'user_id' => 'nullable|integer',
            'participant_name' => 'nullable|string|max:255',
            'is_moderator' => 'required|boolean',
        ]);

        // use transaction to avoid race conditions
        return DB::transaction(function () use ($request) {

            // find meeting by meeting_id (not id) and return error if not found
            $meeting = Meeting::where('meeting_id', $request->meeting_id)->first();

            if (!$meeting) {
                return response()->json([
                    'success' => false,
                    'error' => 'Meeting not found',
                    'meeting_id' => $request->meeting_id,
                ], 404);
            }

            // find or create meeting_detail with status ongoing for that meeting
            $meetingDetail = MeetingDetail::where('meeting_id', $meeting->id)
                ->where('status', 'ongoing')
                ->latest('id')
                ->first();

            // if no ongoing meeting detail, create one (this can happen when the first participant joins and there is no meeting_detail yet, or when all participants left and meeting_detail got ended, and now a new participant is joining)
            if (!$meetingDetail) {
                $meetingDetail = MeetingDetail::create([
                    'meeting_id' => $meeting->id,
                    'status' => 'ongoing',
                    'started_at' => now(),
                    'ended_at' => null,
                    'duration' => 0,
                ]);
            }

            // check if there is already an active log for this user in this meeting_detail (can happen when user got disconnected but not left, and now rejoining), if yes, reuse the same log and return (avoid creating duplicate logs for the same user and meeting_detail)
            $activeLog = MeetingLog::where('meeting_detail_id', $meetingDetail->id)
                ->where('user_id', $request->user_id)
                ->whereNull('left_at')
                ->latest('id')
                ->first();

            // if there is an active log, just return success without creating a new log
            if ($activeLog) {
                return response()->json([
                    'success' => true,
                    'meeting_detail_id' => $meetingDetail->id,
                    'meeting_log_id' => $activeLog->id,
                    'reused' => true,
                ]);
            }

            // create a new log for the participant joining the meeting
            $log = MeetingLog::create([
                'meeting_detail_id' => $meetingDetail->id,
                'user_id' => $request->user_id,
                'participant_name' => $request->participant_name,
                'status' => 'joined',
                'joined_at' => now(),
                'left_at' => null,
                'duration' => 0,
                'is_moderator' => (bool) $request->is_moderator,
            ]);

            return response()->json([
                'success' => true,
                'meeting_detail_id' => $meetingDetail->id,
                'meeting_log_id' => $log->id,
                'reused' => false,
            ]);
        });
    }

    // log meeting leave and calculate duration, also end meeting when all participants left
    public function leaveLog(Request $request)
    {
        // validate request
        $request->validate([
            'meeting_detail_id' => 'required|integer',
            'meeting_log_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'status' => 'nullable|string|in:left,disconnected,kicked', // optional
        ]);

        // use transaction to avoid race conditions when multiple participants leaving at the same time
        return DB::transaction(function () use ($request) {

            // get meeting_detail_id, meeting_log_id, user_id and status (optional, default to 'left') from request
            $meetingDetailId = (int) $request->meeting_detail_id;
            $meetingLogId = (int) $request->meeting_log_id;
            $userId = $request->user_id;

            // update the log for the participant leaving the meeting
            $log = MeetingLog::where('id', $meetingLogId)
                ->where('meeting_detail_id', $meetingDetailId)
                ->where('user_id', $userId)
                ->first();

            if (!$log) {
                return response()->json(['success' => false, 'error' => 'log_not_found'], 404);
            }

            // if already closed, just return ok (avoid double leave calls)
            if ($log->left_at === null) {
                $log->status = $request->input('status', 'left');
                $log->left_at = now();

                // duration in seconds
                if ($log->joined_at) {
                    $log->duration = $log->joined_at
                        ? $log->joined_at->diffInSeconds($log->left_at)
                        : 0;
                } else {
                    $log->duration = 0;
                }

                $log->save();
            }

            // check if there is any active log for that meeting_detail, if no, end the meeting_detail
            $activeCount = MeetingLog::where('meeting_detail_id', $meetingDetailId)
                ->whereNull('left_at')
                ->count();

            if ($activeCount === 0) {
                $meetingDetail = MeetingDetail::where('id', $meetingDetailId)
                    ->where('status', 'ongoing')
                    ->first();

                // if meetingDetail exists, end it and calculate duration
                if ($meetingDetail) {
                    $meetingDetail->status = 'ended';
                    $meetingDetail->ended_at = now();

                    // duration in seconds
                    if ($meetingDetail->started_at) {
                        $meetingDetail->duration = $meetingDetail->started_at
                            ? $meetingDetail->started_at->diffInSeconds($meetingDetail->ended_at)
                            : 0;
                    } else {
                        $meetingDetail->duration = 0;
                    }

                    $meetingDetail->save();

                    // Call addon function to generate summary if addon is active
                    $addonClass = \Addons\AIMeetingAssistant\Http\Controllers\TranscriptionUploadController::class;

                    if (class_exists($addonClass) && method_exists($addonClass, 'generateSummary')) {
                        try {
                            app($addonClass)->generateSummary($meetingDetail->id);
                        } catch (\Throwable $e) {
                            Log::error('AIMeetingAssistant addon error', [
                                'meeting_detail_id' => $meetingDetail->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }

            return response()->json(['success' => true]);
        });
    }

    // create a meeting history page to show all the past meetings with transcription, summary and logs
    public function meetingHistory($id)
    {
        // find meeting by meeting_id and return error if not found or if the meeting doesn't belong to the user
        $meeting = Meeting::where("meeting_id", $id)->first();

        if (!$meeting) {
            return redirect('/')->withErrors(__('The meeting does not exist'));
        }

        if ($meeting->user_id != Auth::id()) {
            return redirect('/')->withErrors(__('You are not authorized to view this meeting history'));
        }

        // get all meeting details for that meeting ordered by started_at desc with pagination
        $meetingDetails = MeetingDetail::where('meeting_id', $meeting->id)
            ->orderBy('started_at', 'desc')
            ->paginate(config('app.pagination'));

        return view('meeting_history', [
            'page' => __('Meeting History'),
            'meeting' => $meeting,
            'meetingDetails' => $meetingDetails,
        ]);
    }

    // api to get meeting history details with logs and transcription summary
    public function meetingHistoryDetail($detailId)
    {
        // find meeting detail by id and return error if not found or if the meeting doesn't belong to the user
        $detail = MeetingDetail::with('meetingLogs')->find($detailId);

        if (!$detail) {
            return response()->json([
                'status' => false,
                'html' => '<div class="card-body"><div class="empty"><p class="empty-title">Session not found</p></div></div>',
            ], 404);
        }

        if ($detail->meeting->user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'html' => '<div class="card-body"><div class="empty"><p class="empty-title">You are not authorized to view this session history</p></div></div>',
            ], 403);
        }

        // load view with meeting detail and logs
        $html = view('include.user.meeting_history_detail', [
            'activeDetail' => $detail,
        ])->render();

        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }

    // api to upload transcription text for a meeting detail
    public function translate(Request $request)
    {
        // validate request
        $request->validate([
            'text' => 'required|string|max:5000',
            'target_language' => 'nullable|string|max:100',
        ]);

        // get meeting_slug, participant_name and transcribed_text from request
        $text = $request->text ?? "";
        $targetLanguage = $request->target_language;

        // if text is empty, return error
        if ($text === '') {
            return response()->json([
                'success' => false,
                'error' => 'Empty text',
            ], 422);
        }

        // call openai api to translate the text and return the translated text
        try {
            $response = Http::withOptions([
                'verify' => false, // TEMP local only
            ])
                ->withToken(getAddonSetting('SUMMARY_KEY'))
                ->timeout(60)
                ->post('https://api.openai.com/v1/responses', [
                    'model' => 'gpt-4.1-mini',
                    'input' => [
                        [
                            'role' => 'system',
                            'content' => [
                                [
                                    'type' => 'input_text',
                                    'text' => 'You are a translation engine. Translate the user text only. Do not explain anything. Preserve meaning and speaker tone as naturally as possible.',
                                ],
                            ],
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'input_text',
                                    'text' => "Translate the following text to {$targetLanguage}:\n\n{$text}",
                                ],
                            ],
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ], 500);
            }

            $data = $response->json();

            // Extract the translated text from the response
            $translatedText = trim(
                $data['output'][0]['content'][0]['text']
                ?? $data['output_text']
                ?? ''
            );

            return response()->json([
                'success' => true,
                'translated_text' => $translatedText,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
