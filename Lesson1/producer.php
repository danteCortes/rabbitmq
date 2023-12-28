<?php

    namespace Lesson1;

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../App/FakerSentence.php';

    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    use App\FakerSentence;

    function getId() {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $cadenaAleatoria = str_shuffle($caracteres);
    
        return substr($cadenaAleatoria, 0, 5);
    }

    $queue = 'queue1';
    if(isset($argv[1])){

        $queue = $argv[1];
    }

    $faker = new FakerSentence();

    $connection = new AMQPStreamConnection('172.17.0.2', 5672, 'root', '123456', 'rabbitmqhost');
    $channel = $connection->channel();

    $channel->queue_declare($queue, false, false, false, false);

    for ($i=0; $i < 6; $i++) { 
        
        $message = json_encode([
            'id' => getId(),
            'message' => $faker->sentence()
        ]);
    
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', $queue);
        
        echo $message."\n";

        sleep(1);
    }

    $channel->close();
    $connection->close();