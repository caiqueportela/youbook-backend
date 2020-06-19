<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@youbook');
        $user->setPassword($this->encoder->encodePassword($user, '!23Mudar@'));
        $user->setLocale('pt_BR');

        $adminRole = $manager->getRepository(UserRole::class)->findOneByName('admin');
        $user->addRole($adminRole);

        $manager->persist($user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['data'];
    }

}