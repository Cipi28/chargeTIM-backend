<?php
//
///**
// * Parse CloudAMPQ URL
// * amqp://user:pass@ec2.clustername.cloudamqp.com/vhost
// */
////if (env("CLOUDAMQP_URL", false)) {
////    $rabbitUrl = parse_url(env("CLOUDAMQP_URL"));
////    $rabbitHost = isset($rabbitUrl["host"]) ? $rabbitUrl["host"] : '127.0.0.1';
////    $rabbitPort = isset($rabbitUrl["port"]) ? $rabbitUrl["port"] : 5672;
////    $rabbitUser = isset($rabbitUrl["user"]) ? $rabbitUrl["user"] : 'guest';
////    $rabbitPassword = isset($rabbitUrl["pass"]) ? $rabbitUrl["pass"] : 'guest';
////    $rabbitVHost = substr($rabbitUrl['path'], 1) ?? '/';
////} else {
////    $rabbitHost = env('RABBITMQ_HOST', '127.0.0.1');
////    $rabbitPort = env('RABBITMQ_PORT', 5672);
////    $rabbitUser = env('RABBITMQ_LOGIN', 'guest');
////    $rabbitPassword = env('RABBITMQ_PASSWORD', 'guest');
////    $rabbitVHost = env('RABBITMQ_VHOST', '/');
////}
//
//
//return [
//
//    /*
//    |--------------------------------------------------------------------------
//    | Default Queue Connection Name
//    |--------------------------------------------------------------------------
//    |
//    | Lumen's queue API supports an assortment of back-ends via a single
//    | API, giving you convenient access to each back-end using the same
//    | syntax for every one. Here you may define a default connection.
//    |
//    */
//
//    'default' => env('QUEUE_CONNECTION', 'sync'),
//
//    /*
//    |--------------------------------------------------------------------------
//    | Queue Connections
//    |--------------------------------------------------------------------------
//    |
//    | Here you may configure the connection information for each server that
//    | is used by your application. A default configuration has been added
//    | for each back-end shipped with Lumen. You are free to add more.
//    |
//    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
//    |
//    */
//
//    'connections' => [
//
//        'sync' => [
//            'driver' => 'sync',
//        ],
//
//        'database' => [
//            'driver' => 'database',
//            'table' => env('QUEUE_TABLE', 'jobs'),
//            'queue' => 'default',
//            'retry_after' => 90,
//        ],
//
//        'beanstalkd' => [
//            'driver' => 'beanstalkd',
//            'host' => 'localhost',
//            'queue' => 'default',
//            'retry_after' => 90,
//        ],
//
//        'sqs' => [
//            'driver' => 'sqs',
//            'key' => env('SQS_KEY', 'your-public-key'),
//            'secret' => env('SQS_SECRET', 'your-secret-key'),
//            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
//            'queue' => env('SQS_QUEUE', 'your-queue-name'),
//            'region' => env('SQS_REGION', 'us-east-1'),
//        ],
//
//        'rabbitmq' => [
//
//            'driver' => 'rabbitmq',
//
//            'worker' => env('RABBITMQ_WORKER', 'default'),
//
//            'dsn' => env('RABBITMQ_DSN', null),
//
//            'hosts' => [
//                [
//                    'host' => $rabbitHost,
//                    'port' => $rabbitPort,
//                    'vhost' => $rabbitVHost,
//                    'user' => $rabbitUser,
//                    'password' => $rabbitPassword,
//                ],
//            ],
//
//            'factory_class' => Enqueue\AmqpLib\AmqpConnectionFactory::class,
//
//            'queue' => env('RABBITMQ_QUEUE', 'default'),
//
//            'options' => [
//
//                'exchange' => [
//
//                    'name' => env('RABBITMQ_EXCHANGE_NAME','default'),
//
//                    /*
//                     * Determine if exchange should be created if it does not exist.
//                     */
//
//                    'declare' => env('RABBITMQ_EXCHANGE_DECLARE', true),
//
//                    /*
//                     * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
//                     */
//
//                    'type' => env('RABBITMQ_EXCHANGE_TYPE', \Interop\Amqp\AmqpTopic::TYPE_DIRECT),
//                    'passive' => env('RABBITMQ_EXCHANGE_PASSIVE', false),
//                    'durable' => env('RABBITMQ_EXCHANGE_DURABLE', true),
//                    'auto_delete' => env('RABBITMQ_EXCHANGE_AUTODELETE', false),
//                    'arguments' => env('RABBITMQ_EXCHANGE_ARGUMENTS', 'default'),
//                ],
//
//                'queue' => [
//
//                    /*
//                     * Determine if queue should be created if it does not exist.
//                     */
//
//                    'declare' => env('RABBITMQ_QUEUE_DECLARE', true),
//
//                    /*
//                     * Determine if queue should be binded to the exchange created.
//                     */
//
//                    'bind' => env('RABBITMQ_QUEUE_DECLARE_BIND', true),
//
//                    /*
//                     * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
//                     */
//
//                    'passive' => env('RABBITMQ_QUEUE_PASSIVE', false),
//                    'durable' => env('RABBITMQ_QUEUE_DURABLE', true),
//                    'exclusive' => env('RABBITMQ_QUEUE_EXCLUSIVE', false),
//                    'auto_delete' => env('RABBITMQ_QUEUE_AUTODELETE', false),
//                    'arguments' => env('RABBITMQ_QUEUE_ARGUMENTS', 'default'),
//                ],
//            ],
//
//            /*
//             * Determine the number of seconds to sleep if there's an error communicating with rabbitmq
//             * If set to false, it'll throw an exception rather than doing the sleep for X seconds.
//             */
//
//            'sleep_on_error' => env('RABBITMQ_ERROR_SLEEP', 5),
//
//            /*
//             * Optional SSL params if an SSL connection is used
//             * Using an SSL connection will also require to configure your RabbitMQ to enable SSL. More details can be founds here: https://www.rabbitmq.com/ssl.html
//             */
//
//            'ssl_params' => [
//                'ssl_on' => env('RABBITMQ_SSL', false),
//                'cafile' => env('RABBITMQ_SSL_CAFILE', null),
//                'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
//                'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
//                'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
//                'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
//            ],
//
//        ],
//
//    ],
//
//    /*
//    |--------------------------------------------------------------------------
//    | Failed Queue Jobs
//    |--------------------------------------------------------------------------
//    |
//    | These options configure the behavior of failed queue job logging so you
//    | can control which database and table are used to store the jobs that
//    | have failed. You may change them to any database / table you wish.
//    |
//    */
//
//    'failed' => [
//        'database' => env('DB_CONNECTION', 'pgsql'),
//        'table' => env('QUEUE_FAILED_TABLE', 'failed_jobs'),
//    ],
//
//    'jobs' => [
//        \App\Jobs\Actions\FollowUpCreateJob::class => 'FOLLOW_UP_CREATE',
//        \App\Jobs\Actions\ReminderSendJob::class => 'REMINDER_SEND',
//        \App\Jobs\Email\InviteSurveyJob::class => 'EMAIL_INVITE_SURVEY',
//        \App\Jobs\Email\NotifyFollowUpJob::class => 'NOTIFY_FOLLOW_UP',
//        \App\Jobs\Email\PublishSurveyJob::class => 'PUBLISH_SURVEY',
//        \App\Jobs\Email\SendSurveyJob::class => 'SEND_SURVEY',
//
//        \App\Jobs\Exports\FeedbackExportJob::class => 'EXPORT',
//        \App\Jobs\Exports\FollowUpsExportJob::class => 'EXPORT',
//        \App\Jobs\Exports\EmailActivityExportJob::class => 'EXPORT',
//        \App\Jobs\Exports\SurveysExportJob::class => 'EXPORT',
//        \App\Jobs\Exports\SurveyZipExportJob::class => 'EXPORT',
//        \App\Jobs\Imports\AnswersReadJob::class => 'EXPORT',
//
//        \App\Jobs\Imports\ChunkReadJob::class => 'IMPORT_CONTACTS',
//        \App\Jobs\XMLChunkedReadJob::class => 'IMPORT_CONTACTS',
//
//        \App\Jobs\Sms\InviteSurveyJob::class => 'SMS_INVITE_SURVEY',
//        \App\Jobs\Sms\PublishSurveyJob::class => 'SMS_PUBLISH_SURVEY',
//
//        \App\Jobs\AnswerAnonymousJob::class => 'ANSWER_ANONYMOUS',
//        \App\Jobs\AnswerSyncJob::class => 'ANSWER_SYNC',
//        \App\Jobs\OndemandSyncJob::class => 'ANSWER_SYNC',
//        \App\Jobs\SyncAnswersOnDemandJob::class => 'ANSWER_SYNC',
//        \App\Jobs\SyncContactAnswersJob::class => 'ANSWER_SYNC',
//        \App\Jobs\SyncDeletedAnswerJob::class => 'ANSWER_SYNC',
//        \App\Jobs\FeedbackSyncJob::class => 'ANSWER_SYNC',
//        \App\Jobs\ElasticCloudSyncJob::class => 'ANSWER_SYNC',
//        \App\Jobs\AnswersBulkElasticSyncJob::class => 'ANSWER_SYNC',
//        \App\Jobs\DashboardShareJob::class => 'DASHBOARD_SHARE_JOB',
//
//        \App\Jobs\UploadAssetsJob::class => 'UPLOAD_ASSETS',
//        \App\Jobs\Email\PublishIndividualDashboardJob::class => 'EMAIL_INVITE_SURVEY',
//    ],
//];
