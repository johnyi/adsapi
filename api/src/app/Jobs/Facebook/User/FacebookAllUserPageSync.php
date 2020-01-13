<?php

namespace App\Jobs\Facebook\User;

use App\Jobs\Job;
use App\Models\User;

class FacebookAllUserPageSync extends Job
{
    protected $params;

    /**
     * Create a new job instance.
     *
     * @param $params array
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $builder = User::where('expires_at', '>', date_create()->format('Y-m-d H:i:s'))->orWhereNull('expires_at');

        if (array_key_exists('userIds', $this->params)) {
            $builder = $builder->whereIn('user_id', $this->params['userIds']);
        }

        $users = $builder->get();
        if (!empty($users)) {
            foreach ($users as $user) {
                dispatch(new FacebookUserPageSync($user));
            }
        }
    }
}
