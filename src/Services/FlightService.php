<?php

namespace App\Services;

use App\Entity\Reservation;
use App\Entity\Flight;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FlightService {
    
    private $em;
    private $security;
    private $mailer;
    
    public function __construct(EntityManagerInterface $em, Security $security, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->security = $security;
        $this->mailer = $mailer;
    }
    /**
     * Бизнес-логика бронирования
     * 1. Существует ли такой рейс?
     * 2. Не забронировано ли уже это место?
     * 3. Нормально ли авторизован этот пользователь?
     * 
     * @param int $flightId
     * @param int $seatNumber
     */
    public function reservSeat(int $flightId, int $seatNumber) {
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
        
        $newReserv = new Reservation();
        $newReserv->setClient($client);
        $now = new \DateTime('now');
        $newReserv->setDateOfReservation($now);
        $newReserv->setFlight($flight);
        $newReserv->setSeatNumber($seatNumber);
        $newReserv->setState(Reservation::STATE_ACTUAL);
        
        $this->em->persist($newReserv);
        
        $this->em->flush();
        
        return true;
    }
    
    /**
     * Бизнес-логика отмены брони
     * 0. Существует ли указанный рейс?
     * 1. Существует ли указанная бронь?
     * 2. Принадлежит ли эта бронь этому пользователю?
     * 
     * @param int $flightId
     * @param int $seatNumber
     */
    public function cancelReserveSeat(int $flightId, int $seatNumber)
    {
        $flight = $this->em->getRepository(Flight::class)->findOneById($flightId);
        
        if(!$flight)
            throw new NotFoundHttpException('Flight not found');
        
        $reserv = $this->em->getRepository(Reservation::class)->findOneby([
            'flight' => $flight,
            'seatNumber' => $seatNumber,
            'state' => Reservation::STATE_ACTUAL
        ]);
        
        if(!$reserv)
            throw new NotFoundHttpException('Reservation not found');
        
        $client = $this->security->getUser();
        
        if($reserv->getClient() != $client)
            throw new AccessDeniedException('You don\'t have premission for this reservation' );
        
        $reserv->setState(Reservation::STATE_CANCELED);
        
        $this->em->persist($reserv);
        
        $this->em->flush();
        
        return true;
    }
    
    /**
     * Бизнес-логика отмены рейса
     * 1. изменить статус рейса
     * 2. Оповестить пользователей об отмене
     */
    public function cancelFlight(int $flightId)
    {
        $flight = $this->em->getRepository(Flight::class)->findOneById($flightId);
        
        if(!$flight)
            throw new NotFoundHttpException('Flight not found');
        
        $flight->setStatus(Flight::STATUS_CANCALED);
        $this->em->persist($flight);
        $this->em->flush();
        
        $tickets = $this->em->getRepository(Ticket::class)->findByFlight($flight);
        
        // TODO: Хорошо бы разложить эту отправку в очередь и вынести её в отдельный сервис
        
        $mails = [];
        foreach($tickets as $ticket){
            $mails[] = $ticket->getCustomer()->getEmail();
        }
        $mails = array_unique($mails);
        
        $message = (new \Swift_Message('К сожалению, Ваш рейс отменён'))
            ->setFrom($_ENV['MAILER_SEND_FROM'])
            ->setTo($mails)
            ->setBody('К сожалению, рейс # ' . $flight->getId() . ' был отменён в связи с погодными условаиями', 'text/plain')
        ;
        
        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            dump($e);
        }
        
    }
}