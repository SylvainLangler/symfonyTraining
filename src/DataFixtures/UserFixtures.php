<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setFirstName('Sylvain')
             ->setLastName('Langler')
             ->setEmail('langlersylvain@gmail.com')
             ->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Sylvain1234'
        ));

        $manager->persist($user);

        $manager->flush();
    }
}
