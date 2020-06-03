<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    public const REF_PREFIX = 'user-';
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setEmail('user' . $i . '@mail.ru');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'qwerty'
            ));
            $manager->persist($user);
            $this->addReference(self::REF_PREFIX . $i, $user);
        }
        
        $manager->flush();
       
        
    }
}
