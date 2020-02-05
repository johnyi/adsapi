<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['namespace' => 'Facebook', 'prefix' => 'fb'], function() use ($router) {
    // Auth
    $router->group(['prefix' => 'auth'], function() use ($router) {
        // Frontend
        $router->get('js', 'AuthController@js');
        $router->post('user', 'AuthController@user');
        $router->post('token', 'AuthController@token');
        // Backend
        $router->get('login', 'AuthController@login');
        $router->get('callback', 'AuthController@callback');
    });

    // Marketing
    $router->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function() use ($router) {
        // Account
        $router->group(['prefix' => 'account'], function() use ($router) {
            $router->get('', 'AccountController@index');
            $router->get('{accountId}/view', 'AccountController@view');
            $router->get('{accountId}/insight', 'AccountController@insight');
        });

        // Campaign
        $router->group(['prefix' => 'campaign'], function() use ($router) {
            $router->get('', 'CampaignController@index');
            $router->post('create', 'CampaignController@create');
            $router->get('insight', 'CampaignController@insight');
            $router->get('{campaignId}/view', 'CampaignController@view');
            $router->put('{campaignId}/update', 'CampaignController@update');
            $router->delete('{campaignId}/delete', 'CampaignController@delete');
        });

        // Ad Set
        $router->group(['prefix' => 'adset'], function() use ($router) {
            $router->get('', 'AdSetController@index');
            $router->post('create', 'AdSetController@create');
            $router->get('insight', 'AdSetController@insight');
            $router->get('{adSetId}/view', 'AdSetController@view');
            $router->put('{adSetId}/update', 'AdSetController@update');
            $router->delete('{adSetId}/delete', 'AdSetController@delete');
        });

        // Ad
        $router->group(['prefix' => 'ad'], function() use ($router) {
            $router->get('', 'AdController@index');
            $router->post('create', 'AdController@create');
            $router->get('insight', 'AdController@insight');
            $router->get('{adId}/view', 'AdController@view');
            $router->put('{adId}/update', 'AdController@update');
            $router->delete('{adId}/delete', 'AdController@delete');
        });

        // Ad Creative
        $router->group(['prefix' => 'adcreative'], function() use ($router) {
            $router->get('{adCreativeId}/view', 'AdCreativeController@view');
        });

        // Ad Image
        $router->group(['prefix' => 'adimage'], function() use ($router) {
            $router->get('', 'AdImageController@index');
            $router->post('create', 'AdImageController@create');
            $router->delete('delete', 'AdImageController@delete');
        });

        // Ad Video
        $router->group(['prefix' => 'advideo'], function() use ($router) {
            $router->get('', 'AdVideoController@index');
            $router->post('create', 'AdVideoController@create');
            $router->delete('{adVideoId}/delete', 'AdVideoController@delete');
        });

        // Target
        $router->get('targeting/search', 'TargetingController@search');
    });

    // Business
    $router->group(['namespace' => 'Business', 'prefix' => 'business/{managerId}'], function() use ($router) {
        $router->get('account', 'AccountController@index');
    });

    // Messenger
    $router->group(['namespace' => 'Messenger', 'prefix' => 'messenger/{pageId}'], function() use ($router) {
        // Attachment
        $router->get('attachment', 'AttachmentController@index');
        $router->post('attachment/upload', 'AttachmentController@upload');

        // Insight
        $router->get('insight', 'InsightController@index');

        // Message
        $router->get('message', 'MessageController@index');
        $router->post('message/send', 'MessageController@send');

        // Profile
        $router->get('profile', 'ProfileController@index');
        $router->put('profile/update', 'ProfileController@update');
        $router->delete('profile/delete', 'ProfileController@delete');
    });

    // Page
    $router->group(['prefix' => 'page/{pageId}'], function() use ($router) {
        $router->get('audience', 'PageController@audience');
        $router->post('subscribe', 'PageController@subscribe');
    });

    // User
    $router->group(['namespace' => 'User', 'prefix' => 'user/{userId}'], function() use ($router) {
        // Page
        $router->group(['prefix' => 'page'], function() use ($router) {
            $router->get('', 'PageController@index');
            $router->post('create', 'PageController@create');
        });

        // Page
        $router->group(['prefix' => 'account'], function() use ($router) {
            $router->get('', 'AccountController@index');
        });
    });

    // Webhook
    $router->post('webhook', 'WebhookController@index');
    $router->get('webhook', 'WebhookController@verify');
});

$router->post('job', 'JobController@index');
