<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use App\Services\TicketService;

class TicketBuyMethod implements JsonRpcMethodInterface
{
    private $ticketService;
    
    public function __construct(TicketService $ticket)
    {
        $this->ticketService = $ticket;
        
    }
    
    public function apply(array $paramList = null)
    {
        return $this->ticketService->buyTicket($paramList['flight_id'], $paramList['seat_number']);        
    }
}