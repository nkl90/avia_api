<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use App\Services\FlightService;

class ReserveSeatMethod implements JsonRpcMethodInterface
{
    private $flightService;
    
    public function __construct(FlightService $flight)
    {
        $this->flightService = $flight;
        
    }
    
    public function apply(array $paramList = null)
    {
        return $this->flightService->reservSeat($paramList['flight_id'], $paramList['seat_number']);        
    }
}