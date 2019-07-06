<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | You can find your API key on your Bugsnag dashboard.
    |
    | This api key points the Bugsnag notifier to the project in your account
    | which should receive your application's uncaught exceptions.
    |
    */
    'api_key' => env('BUGSNAG_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | Name of custom queue, Bugsnag request should executed on.
    |
    | Leave as null, to use the project's default queue.
    |
    */
    'queue' => null,

    /*
    |--------------------------------------------------------------------------
    | Failed Jobs
    |--------------------------------------------------------------------------
    |
    | Sets if failed jobs should be reported to bugsnag (bool)
    |
    */
    'report_failed_jobs' => true,

    /*
    |--------------------------------------------------------------------------
    | Rebind handler
    |--------------------------------------------------------------------------
    |
    | Rebind handler from this package
    | It possible to set this false and just handle the report in App/Exceptions/Handler
    |
    */
    'rebind_handler' => true,

    /*
    |--------------------------------------------------------------------------
    | Notify release stages
    |--------------------------------------------------------------------------
    |
    | Set which release stages should send notifications to Bugsnag.
    |
    | Example: ['local', 'development', 'staging', 'production']
    |
    */
    'notify_release_stages' => ['development', 'staging', 'production'],

    /*
    |--------------------------------------------------------------------------
    | Endpoint
    |--------------------------------------------------------------------------
    |
    | Set what server the Bugsnag notifier should send errors to. By default
    | this is set to 'https://notify.bugsnag.com', but for Bugsnag Enterprise
    | this should be the URL to your Bugsnag instance.
    |
    */
    'endpoint' => 'https://notify.bugsnag.com',

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    |
    | Use this if you want to ensure you don't send sensitive data such as
    | passwords, and credit card numbers to our servers. Any keys which
    | contain these strings will be filtered.
    |
    */
    'filters' => ['password'],

    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    |
    | If your server is behind a proxy server, you can configure this as well.
    | Other than the host, none of these settings are mandatory.
    |
    | Note: Proxy configuration is only possible if the PHP cURL extension
    | is installed.
    |
    | Example:
    |
    |     'proxy' => [
    |         'host'     => 'bugsnag.com',
    |         'port'     => 42,
    |         'user'     => 'username',
    |         'password' => 'password123'
    |     ]
    |
    */
    'proxy' => null,
];
