<?php


namespace App\DataFixtures;


use App\Entity\GroupRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GroupRoleFixtures extends Fixture implements FixtureGroupInterface
{

    private $roles = [
        'admin',
        'article_author',
        'course_author',
        'article_editor',
        'course_editor'
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->roles as $roleName) {
            $role = new GroupRole();
            $role->setName($roleName);
            $role->setDescription('');
            $manager->persist($role);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['def'];
    }

}