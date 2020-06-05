<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Flight;

class EventsService {
    
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * В лучайном порядке возвращает отменёные рейсы либо ничего
     * @return ?array
     */
    public function getCanceledFlight(): ?array 
    {
        if(random_int(0, 10) < 5)
            return null;
        
        $limit = random_int(0, 3);
        
        $flight = $this->em->getRepository(Flight::class)->getRandomActualFlightsIds($limit);
        
        return $flight;
    }
    
    /**
     * В случайном  порядке возвращает рейсы, на которые остановлены продажи билетов
     * @return ?array
     */
    public function getTicketSalesCompleted(): ?array
    {
        if(random_int(0, 10) < 5)
            return null;
        
        $limit = random_int(0, 3);
        
        $canceledSales = $this->em->getRepository(Flight::class)->getRandomActualFlightsIds($limit, ['id', 'DESC']);
        
        return $canceledSales;
    }
}