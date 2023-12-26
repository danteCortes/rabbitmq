<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    function getId() {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $cadenaAleatoria = str_shuffle($caracteres);
    
        return substr($cadenaAleatoria, 0, 5);
    }

    $faker = Faker\Factory::create();

    $connection = new AMQPStreamConnection('172.17.0.2', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $message = json_encode([
        'id' => getId(),
        'message' => $faker->sentence
    ]);

    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');

    echo $message."\n";

    $channel->close();
    $connection->close();