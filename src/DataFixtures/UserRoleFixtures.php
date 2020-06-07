<?php

namespace App\DataFixtures;

use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserRoleFixtures extends Fixture implements FixtureGroupInterface
{

    private $roles = [
        'user',
        'author',
        'admin'
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->roles as $roleName) {
            $role = new UserRole();
            $role->setName($roleName);
            $role->setDescription('');
            $manager->persist($role);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['a'];
    }

}