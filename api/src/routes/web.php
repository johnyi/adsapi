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

        // Webhook
        $router->post('webhook', 'WebhookController@index');
        $router->get('webhook', 'WebhookController@verify');
    });

    // Marketing
    $router->group(['namespace' => 'Marketing', 'prefix' => 'marketing/{managerId}'], function() use ($router) {

    });
});
