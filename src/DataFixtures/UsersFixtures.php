<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $passwordEncoder;
    
    public const MODERATOR = 'moder';
    public const SELLER = 'seller';
    public const CUSTOMER = 'customer';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $superAdmin = new User();
        $superAdmin->setUsername('admin');
        $superAdmin->setPassword($this->passwordEncoder->encodePassword(
            $superAdmin,
            'qwerty'
        ));
        $superAdmin->setRoles([RoleEnumType::ROLE_SUPER_ADMIN]);
        $manager->persist($superAdmin);
        
        $moderator = new User();
        $moderator->setUserName('moderator');
        $moderator->setPassword($this->passwordEncoder->encodePassword(
            $moderator,
            'qwerty'
        ));
        $moderator->setRoles([RoleEnumType::ROLE_MODERATOR]);
        $manager->persist($moderator);
        
        $seller = new User();
        $seller->setUserName('seller');
        $seller->setPassword($this->passwordEncoder->encodePassword(
            $seller,
            'qwerty'
        ));
        $seller->setRoles([RoleEnumType::ROLE_SELLER]);
        $seller->setNotificationPhone('7773332211');
        $manager->persist($seller);
        
        $customer = new User();
        $customer->setUserName('customer');
        $customer->setPassword($this->passwordEncoder->encodePassword(
            $customer,
            'qwerty'
        ));
        $customer->setRoles([RoleEnumType::ROLE_CUSTOMER]);
        $manager->persist($customer);
        
        $manager->flush();
        
        $locked = new User();
        $locked->setUserName('locked_customer');
        $locked->setPassword($this->passwordEncoder->encodePassword(
            $locked,
            'qwerty'
        ));
        $locked->setRoles([RoleEnumType::ROLE_CUSTOMER]);
        $locked->setLocked(true);
        $manager->persist($locked);
        
        $manager->flush();

        $customerWithPhone = new User();
        $customerWithPhone->setUserName('7771112233');
        $customerWithPhone->setPassword($this->passwordEncoder->encodePassword(
            $customerWithPhone,
            'qwerty'
        ));
        $customerWithPhone->setRoles([RoleEnumType::ROLE_CUSTOMER]);
        $manager->persist($customerWithPhone);

        $manager->flush();
        
        $this->addReference(self::MODERATOR, $moderator);
        $this->addReference(self::SELLER, $seller);
        $this->addReference(self::CUSTOMER, $customer);
    }
}
