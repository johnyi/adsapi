<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;

class AdImage extends Marketing
{
    public function get(string $accountId, array $params)
    {
        $response = [];

        $cursor = (new AdAccount('act_' . $accountId))->getAdImages([
            'id',
            'account_id',
            'hash',
            'height',
            'name',
            'original_height',
            'original_width',
            'permalink_url',
            'status',
            'width',
        ], $params);

        $response['data'] = [];

        if (!empty($params['limit'])) {
            $response['before'] = $cursor->getBefore();
            $response['after'] = $cursor->getAfter();

            $content = $cursor->getLastResponse()->getContent();

            foreach ($content['data'] as $row) {
                $response['data'][] = [
                    'accountId'      => $row['account_id'],
                    'adImageId'      => $row['id'],
                    'hash'           => $row['hash'],
                    'height'         => $row['height'],
                    'name'           => $row['name'],
                    'originalHeight' => $row['original_height'],
                    'originalWidth'  => $row['original_width'],
                    'permalinkUrl'   => $row['permalink_url'],
                    'status'         => $row['status'],
                    'width'          => $row['width'],
                ];
            }
        } else {
            while ($cursor->key() !== null) {
                $row = $cursor->current()->getData();

                $response['data'][] = [
                    'accountId'      => $row['account_id'],
                    'adImageId'      => $row['id'],
                    'hash'           => $row['hash'],
                    'height'         => $row['height'],
                    'name'           => $row['name'],
                    'originalHeight' => $row['original_height'],
                    'originalWidth'  => $row['original_width'],
                    'permalinkUrl'   => $row['permalink_url'],
                    'status'         => $row['status'],
                    'width'          => $row['width'],
                ];

                $cursor->next();
            }
        }

        return $response;
    }

    public function create(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->createAdImage([], $params);

        return $response->exportAllData();
    }

    public function delete(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->deleteAdImages([], $params);

        return $response->getContent();
    }
}
