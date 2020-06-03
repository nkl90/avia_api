<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Reservation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReservationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $reserv = new Reservation();
            $flight = $this->getReference(FlightFixture::REF_PREFIX . random_int(0, 9));
            $reserv->setFlight($flight);
            $flightDate = $flight->getDate();
            $reserv->setDateOfReservation($flightDate->modify('+1 day') );
            $reserv->setSeatNumber(random_int(0, $flight->getSeatsCount()));
            $reserv->setState(Reservation::STATE_ACTUAL);
            $reserv->setClient($this->getReference(UsersFixtures::REF_PREFIX . rand(0, 9)));
            
            $manager->persist($reserv);
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
