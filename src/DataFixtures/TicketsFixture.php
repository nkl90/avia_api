<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ticket;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Flight;
use App\Entity\User;

class TicketsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $ticket = new Ticket();
            $flight = $this->getReference(FlightFixture::REF_PREFIX . rand(0, 9));
            $ticket->setFlight($flight);
            $customer = $this->getReference(UsersFixtures::REF_PREFIX . rand(0, 9));
            $ticket->setCustomer($customer);
            $ticket->setState(Ticket::STATUS_ACTUAL);
            
            $manager->persist($ticket);
        }

        $manager->flush();
    }
    
    public function getDependencies() {
        return [
            FlightFixture::class,
            UsersFixtures::class
        ];
    }
}
