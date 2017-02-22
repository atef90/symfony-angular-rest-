<?php

namespace FullStack\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FullStack\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $userGuest = new User();
        $userGuest->setUsername('user');
        $userGuest->setPlainPassword('user');
        $userGuest->setEmail('user@user.com');
        $userGuest->setEnabled(1);
        $userGuest->setRoles([User::ROLE_USER]);
        $manager->persist($userGuest);

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setEnabled(1);
        $userGuest->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userAdmin);
        $manager->flush();
    }

    public function getOrder(){

        return 1;
    }
}