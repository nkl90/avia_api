<?php

namespace App\Services;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class EmailService implements ConsumerInterface
{

    public function execute(AMQPMessage $msg)
    {
        dd($msg);
        dump($msg->getBody());
        $msg->setIsTruncated(true);
        $response = json_decode($msg->body, true);

        $type = $response["Type"];

        if ($type == "VerificationEmail") $this->sendVerificationEmail($response);
    }

    private function sendVerificationEmail($response) {

        // do something
    }
}