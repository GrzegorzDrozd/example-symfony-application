<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $client->setId(1);
        $client->setUsername('test_user');
        $client->setFirstName('Test');
        $client->setLastName('User');
        $client->setPassword('testtesttest123');
        $client->setCreated(new DateTimeImmutable());

        $manager->persist($client);
        $manager->flush();
    }
}
