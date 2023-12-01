<?php

namespace App\Tests\functional;

use App\Entity\Client;
use Random\RandomException;

trait ClientEntityTrait
{

    /**
     * @throws RandomException
     */
    protected function getClientEntity(
        \Symfony\Bundle\FrameworkBundle\KernelBrowser $client,
        bool $persist = true,
        $lastName = 'test',
        $firstName = 'test',
        $password = 'test',
        $username = null,
        ?\DateTimeImmutable $created = null
    ): Client {
        if (empty($username)) {
            $username = 'test' . base64_encode(random_bytes(10)) . date('Y-m-d H:i:s');
        }

        if (empty($created)) {
            $created = new \DateTimeImmutable();
        }

        $clientEntity = new Client();
        $clientEntity->setLastName($lastName);
        $clientEntity->setFirstName($firstName);
        $clientEntity->setUsername($username);
        $clientEntity->setPassword($password);
        $clientEntity->setCreated($created);

        if ($persist) {
            $em = $client->getKernel()->getContainer()->get('doctrine')->getManager();

            $em->persist($clientEntity);
            $em->flush();
        }
        return $clientEntity;
    }
}
