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
        $router->get('js', 'AuthController@js');
        $router->get('login', 'AuthController@login');
        $router->get('callback', 'AuthController@callback');
        $router->post('user', 'AuthController@user');
        $router->post('token', 'AuthController@token');
    });

    // Marketing
    $router->group(['namespace' => 'Marketing', 'prefix' => 'marketing/{managerId}'], function() use ($router) {
        // Business
        $router->group(['prefix' => 'business/{businessId}'], function() use ($router) {
            $router->get('account', 'BusinessController@account');
        });

        $router->group(['prefix' => 'account/{accountId}'], function() use ($router) {
            // Account
            $router->get('view', 'AccountController@view');

            // Campaign
            $router->group(['prefix' => 'campaign'], function() use ($router) {
                $router->get('', 'CampaignController@index');
                $router->post('create', 'CampaignController@create');
            });
        });
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
        $router->post('profile/update', 'ProfileController@update');
        $router->post('profile/delete', 'ProfileController@delete');
    });

    // Page
    $router->group(['prefix' => 'page/{pageId}'], function() use ($router) {
        $router->post('subscribe', 'PageController@subscribe');
    });

    // User
    $router->group(['namespace' => 'User', 'prefix' => 'user/{userId}'], function() use ($router) {
        // Page
        $router->group(['prefix' => 'page'], function() use ($router) {
            $router->get('', 'PageController@index');
            $router->post('create', 'PageController@create');
        });
    });

    // Webhook
    $router->post('webhook', 'WebhookController@index');
    $router->get('webhook', 'WebhookController@verify');
});

$router->post('job', 'JobController@index');
