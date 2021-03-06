<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Facebook\Messenger\UserProfile;
use App\Models\Facebook\Webhook;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\MessageReferral;
use App\Models\Page;
use App\Models\PageAudience;
use DateTime;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        if (!Webhook::validate($request->header('X-Hub-Signature'), file_get_contents('php://input'))) {
            die('message validation error');
        }

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
                                } else {
                                    // Save message
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

                                    // Save message attachment
                                    if (array_key_exists('attachments', $event['message'])) {
                                        foreach ($event['message']['attachments'] as $attachment) {
                                            $messageAttachment = new MessageAttachment();
                                            $messageAttachment['message_id'] = $event['message']['mid'];
                                            $messageAttachment['type'] = $attachment['type'];
                                            $messageAttachment['url'] = $attachment['payload']['url'];
                                            $messageAttachment->save();
                                        }
                                    }

                                    // Save audience
                                    if ($message['type'] == 'RECEIVE') {
                                        $page = Page::where('page_id', '=', $event['recipient']['id'])->first();
                                        if (empty($page)) {
                                            continue;
                                        }

                                        $profile = (new UserProfile($page['access_token']))->get($event['sender']['id']);

                                        $audience = PageAudience::where('page_id', '=', $event['recipient']['id'])->where('ps_id', '=', $event['sender']['id'])->first();
                                        if (empty($audience)) {
                                            $audience = new PageAudience();
                                            $audience['page_id'] = $event['recipient']['id'];
                                            $audience['ps_id'] = $event['sender']['id'];
                                        }

                                        $audience['name'] = $profile['name'];
                                        $audience['first_name'] = $profile['first_name'];
                                        $audience['last_name'] = $profile['last_name'];
                                        $audience['profile_pic'] = $profile['profile_pic'];
                                        $audience['locale'] = array_key_exists('locale', $profile) ? $profile['locale'] : null;
                                        $audience['timezone'] = array_key_exists('timezone', $profile) ? $profile['timezone'] : null;
                                        $audience['gender'] = array_key_exists('gender', $profile) ? $profile['gender'] : null;
                                        $audience->save();
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

    public function verify(Request $request)
    {
        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        if (!empty($mode) and !empty($token)) {
            if ($mode == 'subscribe' and $token == env('FB_WEBHOOK_VERIFY_TOKEN')) {
                return $challenge;
            }
        }
    }
}
