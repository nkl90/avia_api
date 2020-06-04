<?php

namespace App\Services;

use App\Entity\Reservation;
use App\Entity\Flight;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Ticket;

class TicketService {
    
    protected $em;
    protected $security;
    
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    /**
     * Бизнес-логика продажи билета
     * 1. Существует ли такой рейс?
     * 2. Не забронировано ли уже это место?
     * 3. Не продан ли уже билет на это место
     * 4. Нормально ли авторизован этот пользователь?
     * 5. Попытка списания средств
     * ... etc...
     * @param int $flightId
     * @param int $seatNumber
     */
    public function buyTicket(int $flightId, int $seatNumber) {
        $flight = $this->em->getRepository(Flight::class)->findOneById($flightId);
        
        if(!$flight)
            throw new NotFoundHttpException('Flight not found');
        
        $reserv = $this->em->getRepository(Reservation::class)->findOneby([
            'flight' => $flight,
            'seatNumber' => $seatNumber,
            'state' => Reservation::STATE_ACTUAL
        ]);
        
        if($reserv)
            throw new \Exception('Seat alredy reserved');
        
        $client = $this->security->getUser();
        
        if(!$client)
            throw new AccessDeniedException('No user');
        
        $ticket = $this->em->getRepository(Ticket::class)->findOneBy([
            'flight' => $flight,
            'seatNumber' => $seatNumber,
            'state' => Ticket::STATUS_ACTUAL
        ]);
        
        if($ticket)
            throw new \Exception('Ticket already sold');
        
        $ticket = new Ticket();
        $ticket->setCustomer($client);
        $ticket->setFlight($flight);
        $ticket->setState(Ticket::STATUS_ACTUAL);
        $ticket->setSeatNumber($seatNumber);
        
        $this->em->persist($ticket);
        
        $this->em->flush();
        
        return $ticket->getId();
    }
    
    /**
     * Бизнес-логика возврата билета
     * 0. Существует ли указанный билет?
     * 2. Принадлежит ли указанный билет текущему пользователю?
     * 
     * @param int $flightId
     * @param int $seatNumber
     */
    public function cancelTicket(int $ticketId)
    {
        $ticket = $this->em->getRepository(Ticket::class)->findOneBy([
            'id' => $ticketId,
            'state' => Ticket::STATUS_ACTUAL
        ]);
        
        if(!$ticket)
            throw new NotFoundHttpException('Ticket not found');
        
        $customer = $this->security->getUser();
        
        if(!$customer)
            throw new AccessDeniedException('No user');
        
        if($ticket->getCustomer() != $customer)
            throw new AccessDeniedException('Access denide to this ticket');
        
        $ticket->setState(Ticket::STATUS_RETURNED);
        
        $this->em->persist($ticket);
        
        $this->em->flush();
        
        return true;
    }
}