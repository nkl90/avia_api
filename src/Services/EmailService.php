<?php

namespace App\Services;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class EmailService implements ConsumerInterface
{
    private $flightService;
    
    public function __construct(FlightService $service) {
        $this->flightService = $service;
    }
    
    public function execute(AMQPMessage $msg)
    {
        $this->flightService->cancelFlight((int) $msg->getBody());
        
    }

}