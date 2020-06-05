<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use App\Services\TicketService;
use App\Services\EventsService;

class EventsMethod implements JsonRpcMethodInterface
{
    private $eventsService;
    
    public function __construct(EventsService $events)
    {
        $this->eventsService = $events;
        
    }
    
    public function apply(array $paramList = null)
    {
        $result = [];
        $canceledFlight = $this->eventsService->getCanceledFlight();
        
        if($canceledFlight)
            $result['canceled_flight'] = $canceledFlight;
        
        
        $canceledTicket = $this->eventsService->getTicketSalesCompleted();
        
        if($canceledTicket)
            $result['canceled_ticket'] = $canceledTicket;
        
        return $result;
    }
}