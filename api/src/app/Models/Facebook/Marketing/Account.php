<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;

class Account extends Marketing
{
    public function insight(string $accountId, array $params)
    {
        $response = [];

        $fields = [
            'account_currency',
            'account_id',
            'account_name',
            'actions',
            'clicks',
            'conversions',
            'cost_per_action_type',
            'cost_per_conversion',
            'cpc',
            'cpm',
            'ctr',
            'date_start',
            'date_stop',
            'impressions',
            'objective',
            'reach',
            'spend',
            'unique_actions',
            'unique_clicks',
            'unique_ctr',
        ];

        $cursor = (new AdAccount('act_' . $accountId))->getInsights($fields, $params);

        $content = $cursor->getLastResponse()->getContent();

        $response['summary'] = $content['summary'];
        $response['data'] = [];

        if (!empty($params['limit'])) {
            $response['before'] = $cursor->getBefore();
            $response['after'] = $cursor->getAfter();

            foreach ($content['data'] as $row) {
                $response['data'][] = [
                    'accountId'         => array_key_exists('account_id', $row) ? $row['account_id'] : null,
                    'accountName'       => array_key_exists('account_name', $row) ? $row['account_name'] : null,
                    'currency'          => array_key_exists('account_currency', $row) ? $row['account_currency'] : null,
                    'actions'           => array_key_exists('actions', $row) ? $row['actions'] : null,
                    'clicks'            => array_key_exists('clicks', $row) ? $row['clicks'] : null,
                    'conversions'       => array_key_exists('conversions', $row) ? $row['conversions'] : null,
                    'costPerActionType' => array_key_exists('cost_per_action_type', $row) ? $row['cost_per_action_type'] : null,
                    'costPerConversion' => array_key_exists('cost_per_conversion', $row) ? $row['cost_per_conversion'] : null,
                    'cpc'               => array_key_exists('cpc', $row) ? $row['cpc'] : null,
                    'cpm'               => array_key_exists('cpm', $row) ? $row['cpm'] : null,
                    'ctr'               => array_key_exists('ctr', $row) ? $row['ctr'] : null,
                    'date'              => array_key_exists('date_stop', $row) ? $row['date_stop'] : null,
                    'impressions'       => array_key_exists('impressions', $row) ? $row['impressions'] : null,
                    'objective'         => array_key_exists('objective', $row) ? $row['objective'] : null,
                    'reach'             => array_key_exists('reach', $row) ? $row['reach'] : null,
                    'spend'             => array_key_exists('spend', $row) ? $row['spend'] : null,
                    'uniqueActions'     => array_key_exists('unique_actions', $row) ? $row['unique_actions'] : null,
                    'uniqueClicks'      => array_key_exists('unique_clicks', $row) ? $row['unique_clicks'] : null,
                    'uniqueCtr'         => array_key_exists('unique_ctr', $row) ? $row['unique_ctr'] : null,
                ];
            }
        } else {
            while ($cursor->key() !== null) {
                $row = $cursor->current()->getData();

                $response['data'][] = [
                    'accountId'         => array_key_exists('account_id', $row) ? $row['account_id'] : null,
                    'accountName'       => array_key_exists('account_name', $row) ? $row['account_name'] : null,
                    'currency'          => array_key_exists('account_currency', $row) ? $row['account_currency'] : null,
                    'actions'           => array_key_exists('actions', $row) ? $row['actions'] : null,
                    'clicks'            => array_key_exists('clicks', $row) ? $row['clicks'] : null,
                    'conversions'       => array_key_exists('conversions', $row) ? $row['conversions'] : null,
                    'costPerActionType' => array_key_exists('cost_per_action_type', $row) ? $row['cost_per_action_type'] : null,
                    'costPerConversion' => array_key_exists('cost_per_conversion', $row) ? $row['cost_per_conversion'] : null,
                    'cpc'               => array_key_exists('cpc', $row) ? $row['cpc'] : null,
                    'cpm'               => array_key_exists('cpm', $row) ? $row['cpm'] : null,
                    'ctr'               => array_key_exists('ctr', $row) ? $row['ctr'] : null,
                    'date'              => array_key_exists('date_stop', $row) ? $row['date_stop'] : null,
                    'impressions'       => array_key_exists('impressions', $row) ? $row['impressions'] : null,
                    'objective'         => array_key_exists('objective', $row) ? $row['objective'] : null,
                    'reach'             => array_key_exists('reach', $row) ? $row['reach'] : null,
                    'spend'             => array_key_exists('spend', $row) ? $row['spend'] : null,
                    'uniqueActions'     => array_key_exists('unique_actions', $row) ? $row['unique_actions'] : null,
                    'uniqueClicks'      => array_key_exists('unique_clicks', $row) ? $row['unique_clicks'] : null,
                    'uniqueCtr'         => array_key_exists('unique_ctr', $row) ? $row['unique_ctr'] : null,
                ];

                $cursor->next();
            }
        }

        return $response;
    }
}
