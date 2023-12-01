<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Vico;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VicoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('Test Vico');
        $vico->setCreated(new DateTimeImmutable());

        $manager->persist($vico);
        $manager->flush();
    }
}
