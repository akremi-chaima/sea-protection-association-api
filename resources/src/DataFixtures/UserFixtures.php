<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasherInterface;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setFirstName('chayma')
            ->setLastName('akermi')
            ->setEmail('chayma.akermi1997@gmail.com');
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, hash('sha256', 'myPassword')));
        $manager->persist($user);
        $manager->flush();
    }
}
