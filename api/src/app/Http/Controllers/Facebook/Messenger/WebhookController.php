<?php

namespace App\Http\Controllers\Facebook\Messenger;

use App\Http\Controllers\Controller;
use App\Models\Facebook\Messenger\User as MessengerUser;
use App\Models\Facebook\Messenger\Webhook;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\MessageReferral;
use App\Models\Page;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Log;

class WebhookController extends Controller
{
    public function index(Request $request, string $pageId)
    {
        Log::info($request->all());

        if (!Webhook::validate($request->header('X-Hub-Signature'), file_get_contents('php://input'))) {
            die('message validation error');
        }

        $page = Page::where('page_id', '=', $pageId)->first();
        if (!empty($page)) {
            try {
                $object = $request->input('object');
                if ($object == 'page') {
                    $entries = $request->input('entry');
                    if (!empty($entries)) {
                        foreach ($entries as $entry) {
                            if (!empty($entry['messaging'])) {
                                foreach ($entry['messaging'] as $event) {
                                    $date = new DateTime();
                                    $date->setTimestamp($event['timestamp'] / 1000);

                                    if (array_key_exists('read', $event)) {
                                        Message::where('sender_id', '=', $event['recipient']['id'])->where('recipient_id', '=', $event['sender']['id'])->where('timestamp', '<=', $event['timestamp'])->update(['is_read' => true]);

                                        continue;
                                    } elseif (array_key_exists('referral', $event)) {
                                        $messageReferral = new MessageReferral();
                                        $messageReferral['sender_id'] = $event['sender']['id'];
                                        $messageReferral['recipient_id'] = $event['recipient']['id'];
                                        $messageReferral['timestamp'] = $event['timestamp'];
                                        $messageReferral['ref'] = $event['referral']['ref'];
                                        $messageReferral['referer_url'] = array_key_exists('ad_id', $event['referral']) ? $event['referral']['ad_id'] : null;
                                        $messageReferral['source'] = $event['referral']['source'];
                                        $messageReferral['type'] = $event['referral']['type'];
                                        $messageReferral['referer_url'] = array_key_exists('referer_url', $event['referral']) ? $event['referral']['referer_url'] : null;
                                        $messageReferral['created_at'] = $date->format('Y-m-d H:i:s');
                                        $messageReferral->save();

                                        continue;
                                    }

                                    // Save user
                                    $user = User::where('page_id', '=', $pageId)->where('ps_id', '=', $event['sender']['id'])->first();
                                    if (empty($user)) {
                                        $user = new User();
                                        $user['ps_id'] = $event['sender']['id'];
                                        $user['page_id'] = $pageId;
                                    }

                                    $messengerUser = new MessengerUser($page['access_token']);
                                    $profile = $messengerUser->profile($event['sender']['id']);

                                    $user['first_name'] = $profile['first_name'];
                                    $user['last_name'] = $profile['last_name'];
                                    $user['profile_pic'] = $profile['profile_pic'];
                                    $user['locale'] = $profile['locale'];
                                    $user['timezone'] = $profile['timezone'];
                                    $user['gender'] = $profile['gender'];
                                    $user->save();

                                    $message = new Message();
                                    $message['type'] = array_key_exists('is_echo', $event['message']) ? 'RESPONSE' : 'RECEIVE';
                                    $message['sender_id'] = $event['sender']['id'];
                                    $message['recipient_id'] = $event['recipient']['id'];
                                    $message['message_id'] = $event['message']['mid'];
                                    $message['timestamp'] = $event['timestamp'];
                                    $message['text'] = array_key_exists('text', $event['message']) ? $event['message']['text'] : null;
                                    $message['quick_reply'] = array_key_exists('quick_reply', $event['message']) ? $event['message']['quick_reply']['payload'] : null;
                                    $message['reply_to'] = array_key_exists('reply_to', $event['message']) ? $event['message']['reply_to']['mid'] : null;
                                    $message['created_at'] = $date->format('Y-m-d H:i:s');
                                    $message->save();

                                    if (array_key_exists('attachments', $event['message'])) {
                                        foreach ($event['message']['attachments'] as $attachment) {
                                            $messageAttachment = new MessageAttachment();
                                            $messageAttachment['message_id'] = $event['message']['mid'];
                                            $messageAttachment['type'] = $attachment['type'];
                                            $messageAttachment['url'] = $attachment['payload']['url'];
                                            $messageAttachment->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } finally {
                return 'OK';
            }
        }
    }

    public function verify(Request $request, string $pageId)
    {
        $page = Page::where('page_id', '=', $pageId)->first();
        if (!empty($page)) {
            $mode = $request->input('hub_mode');
            $token = $request->input('hub_verify_token');
            $challenge = $request->input('hub_challenge');

            if (!empty($mode) and !empty($token)) {
                if ($mode == 'subscribe' and $token == $page['verify_token']) {
                    return $challenge;
                }
            }
        }
    }
}
