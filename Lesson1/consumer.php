<?php

    namespace Lesson1;

    require_once __DIR__ . '/../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;

    $queue = 'queue1';
    if(isset($argv[1])){

        $queue = $argv[1];
    }

    $connection = new AMQPStreamConnection('172.17.0.2', 5672, 'root', '123456', 'rabbitmqhost');
    $channel = $connection->channel();

    $channel->queue_declare($queue, false, false, false, false);

    $callback = function ($msg) {
        echo $msg->body . "\n";
    };

    $channel->basic_consume($queue, '', false, true, false, false, $callback);

    try {
        $channel->consume();
        sleep(1);
    } catch (\Throwable $exception) {
        echo $exception->getMessage();
    }