<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet as FacebookAdSet;

class AdSet extends Marketing
{
    public function get(string $accountId)
    {
        $response = [];

        $cursor = (new AdAccount('act_' . $accountId))->getAdSets([
            'id',
            'name',
            'daily_budget',
            'bid_amount',
            'budget_remaining',
            'bid_strategy',
            'optimization_goal',
            'billing_event',
            'status',
            'target',
            'account_id',
            'campaign_id',
        ]);

        if (!empty($params['limit'])) {
            $content = $cursor->getLastResponse()->getContent();

            $response['before'] = $cursor->getBefore();
            $response['after'] = $cursor->getAfter();
            $response['data'] = [];

            foreach ($content['data'] as $row) {
                $response['data'][] = [
                    'adSetId'          => $row['id'],
                    'name'             => $row['name'],
                    'dailyBudget'      => $row['daily_budget'],
                    'bidAmount'        => $row['bid_amount'],
                    'budgetRemaining'  => $row['budget_remaining'],
                    'bidStrategy'      => $row['bid_strategy'],
                    'optimizationGoal' => $row['optimization_goal'],
                    'billingEvent'     => $row['billing_event'],
                    'status'           => $row['status'],
                    'accountId'        => $row['account_id'],
                    'campaignId'       => $row['campaign_id'],
                ];
            }
        } else {
            $response['data'] = [];

            while ($cursor->key() !== null) {
                $row = $cursor->current()->getData();

                $response[] = [
                    'adSetId'          => $row['id'],
                    'name'             => $row['name'],
                    'dailyBudget'      => $row['daily_budget'],
                    'bidAmount'        => $row['bid_amount'],
                    'budgetRemaining'  => $row['budget_remaining'],
                    'bidStrategy'      => $row['bid_strategy'],
                    'optimizationGoal' => $row['optimization_goal'],
                    'billingEvent'     => $row['billing_event'],
                    'status'           => $row['status'],
                    'accountId'        => $row['account_id'],
                    'campaignId'       => $row['campaign_id'],
                ];

                $cursor->next();
            }
        }

        return $response;
    }

    public function create(string $accountId, array $params)
    {
        $response = (new AdAccount('act_' . $accountId))->createAdSet([], $params);

        return $response->exportAllData();
    }

    public function find(string $adSetId)
    {
        $response = (new FacebookAdSet($adSetId))->getSelf();

        return $response->exportAllData();
    }

    public function update(string $adSetId, array $params)
    {
        $response = (new FacebookAdSet($adSetId))->updateSelf([], $params);

        return $response->exportAllData();
    }

    public function delete(string $adSetId)
    {
        $response = (new FacebookAdSet($adSetId))->deleteSelf();

        return $response->getContent();
    }

    public function insight(string $accountId, array $params)
    {
        $response = [];

        $fields = [
            'account_currency',
            'account_id',
            'account_name',
            'campaign_id',
            'campaign_name',
            'adset_id',
            'adset_name',
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
                    'campaignId'        => array_key_exists('campaign_id', $row) ? $row['campaign_id'] : null,
                    'campaignName'      => array_key_exists('campaign_name', $row) ? $row['campaign_name'] : null,
                    'adSetId'           => array_key_exists('adset_id', $row) ? $row['adset_id'] : null,
                    'adSetName'         => array_key_exists('adset_name', $row) ? $row['adset_name'] : null,
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
                    'campaignId'        => array_key_exists('campaign_id', $row) ? $row['campaign_id'] : null,
                    'campaignName'      => array_key_exists('campaign_name', $row) ? $row['campaign_name'] : null,
                    'adSetId'           => array_key_exists('adset_id', $row) ? $row['adset_id'] : null,
                    'adSetName'         => array_key_exists('adset_name', $row) ? $row['adset_name'] : null,
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
