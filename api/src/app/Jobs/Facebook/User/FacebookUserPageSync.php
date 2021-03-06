<?php

namespace App\Jobs\Facebook\User;

use App\Jobs\Job;
use App\Models\Page;
use App\Models\User;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookUserPageSync extends Job
{
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $user User
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws FacebookSDKException
     *
     */
    public function handle()
    {
        $fb = new Facebook([
            'app_id'     => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);

        $response = $fb->get(sprintf('/%s/accounts', $this->user['user_id']), $this->user['access_token']);
        $userPages = $response->getDecodedBody()['data'];
        if (!empty($userPages)) {
            foreach ($userPages as $userPage) {
                $page = Page::where('page_id', '=', $userPage['id'])->where('user_id', '=', $this->user['user_id'])->first();
                if (empty($page)) {
                    $page = new Page();
                    $page['page_id'] = $userPage['id'];
                    $page['user_id'] = $this->user['user_id'];
                }

                $page['name'] = $userPage['name'];
                $page['category'] = $userPage['category'];
                $page['access_token'] = $userPage['access_token'];
                $page->save();

                $fields = [
                    'messages',
                    'messaging_postbacks',
                    'message_reads',
                    'messaging_referrals',
                    'message_echoes',
                ];

                $fb->post(sprintf('/%s/subscribed_apps?subscribed_fields=%s', $page['page_id'], implode(',', $fields)), [], $page['access_token']);
            }
        }
    }
}
