<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Flight;

class FlightFixtures extends Fixture
{
    protected array $cities = [
        'Москва', 'Лондон', 'Берлин', 'Нью-Йорк', 'Париж',
        'Токио', 'Сан-Франциско', 'Алма-Аты', 'Гон-конг'
    ];
    public const REF_PREFIX = 'flight-';
    
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $flight = new Flight();
            $fromCityNumber = random_int(0, count($this->cities) - 1);
            $toCityNumber = $this->getDiffRand($fromCityNumber, 0, count($this->cities) - 1);
            
            $flight->setFromCity($this->cities[$fromCityNumber]);
            $flight->setToCity($this->cities[$toCityNumber]);
            $flight->setStatus(Flight::STATUS_ACTUAL);
            $flight->setSeatsCount(random_int(50, 400));
            $flight->setDate(new \DateTime($this->getRandomDate()));
            
            $manager->persist($flight);
            
            $this->addReference(self::REF_PREFIX . $i, $flight);
        }
        
        $manager->flush();
    }
    
    private function getDiffRand($number, $min, $max)
    {
        $newRand = random_int($min, $max);
        
        if($newRand == $number)
            return $this->getDiffRand($number, $min, $max);
        
        return $newRand;
    }
    
    private function getRandomDate()
    {
        $year= mt_rand(2020, date("Y"));
        $month= mt_rand(6, 12);
        $day= mt_rand(1, 28);
        
        return $year . "-" . $month . "-" . $day;
    }
}
