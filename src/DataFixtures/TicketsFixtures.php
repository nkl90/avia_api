<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ticket;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TicketsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $ticket = new Ticket();
            $flight = $this->getReference(FlightFixtures::REF_PREFIX . random_int(0, 9));
            $ticket->setFlight($flight);
            $customer = $this->getReference(UsersFixtures::REF_PREFIX . random_int(0, 9));
            $ticket->setCustomer($customer);
            $ticket->setState(Ticket::STATUS_ACTUAL);
            
            // Ненадежное решение, возможно, всемогущий рандом выдаст одно и то же место
            // на один и тот же рейс из-за чего фикстуры могут и не взлететь
            $ticket->setSeatNumber(random_int(0, $flight->getSeatsCount()));
            
            $manager->persist($ticket);
        }

        $manager->flush();
    }
    
    public function getDependencies() {
        return [
            FlightFixtures::class,
            UsersFixtures::class
        ];
    }
}
