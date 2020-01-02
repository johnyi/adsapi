<?php

namespace App\Models\Facebook\Messenger;

class Webhook
{
    public static function validate($signature, $rawPostData)
    {
        if (strlen($signature) == 45 && substr($signature, 0, 5) == 'sha1=') {
            $signature = substr($signature, 5);
        }

        if (hash_equals($signature, hash_hmac('sha1', $rawPostData, env('FB_APP_SECRET')))) {
            return true;
        }

        return false;
    }
}
