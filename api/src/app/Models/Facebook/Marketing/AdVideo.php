<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;

class AdVideo extends Marketing
{
    public function get(string $accountId, array $params)
    {
        $response = [];

        $cursor = (new AdAccount('act_' . $accountId))->getAdVideos([
            'id',
            'file_url',
            'thumbnails',
        ], $params);

        $response['data'] = [];

        if (!empty($params['limit'])) {
            $response['before'] = $cursor->getBefore();
            $response['after'] = $cursor->getAfter();

            $content = $cursor->getLastResponse()->getContent();

            foreach ($content['data'] as $row) {
                $response['data'][] = [
                    'adVideoId'  => $row['id'],
                    'fileUrl'    => $row['file_url'],
                    'thumbnails' => $row['thumbnails'],
                ];
            }
        } else {
            while ($cursor->key() !== null) {
                $row = $cursor->current()->getData();

                $response['data'][] = [
                    'adVideoId'  => $row['id'],
                    'fileUrl'    => $row['file_url'],
                    'thumbnails' => $row['thumbnails'],
                ];

                $cursor->next();
            }
        }

        return $response;
    }

    public function create(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->createAdVideo([], $params);

        return $response->exportAllData();
    }

    public function delete(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->deleteAdVideos([], $params);

        return $response->getContent();
    }
}
