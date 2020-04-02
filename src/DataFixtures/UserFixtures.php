<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $profile = new Profile();
        $profile->setTitle('Profil 1')
                ->setDescription('Description du profil 1');

        $user->setFirstName('Sylvain')
             ->setLastName('Langler')
             ->setEmail('langlersylvain@gmail.com')
             ->setRoles(['ROLE_ADMIN'])
             ->setProfile($profile)
             ->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Sylvain1234'
        ));

        $manager->persist($user);

        $manager->flush();
    }
}
