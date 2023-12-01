<?php

namespace App\Tests\functional\Rating;
use App\Entity\Client;
use App\Enums\Ratings;
use App\Tests\functional\ClientEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddingRatingTest extends WebTestCase
{
    use ClientEntityTrait;
    public function testAddingRating()
    {
        $client = static::createClient();
        $clientEntity = $this->getClientEntity($client);

        $crawler = $client->request('POST', '/vico/1/ratings', [
            'clientId' => $clientEntity->getId(),
            'ratings' => [
                [
                    'name' => Ratings::QUALITY_OF_WORK->value,
                    'value' => 5
                ]
            ]
        ]);
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());
    }

    public function testAddingRatingWhenClientNotFound()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/vico/1/ratings', [
            'clientId' =>999999,
            'ratings' => [
                [
                    'name' => Ratings::QUALITY_OF_WORK->value,
                    'value' => 5
                ]
            ]
        ]);
        self::assertResponseStatusCodeSame(404);
        self::assertJson($client->getResponse()->getContent());
    }


    public function testAddingRatingDuplicateRating()
    {
        $client = static::createClient();
        $clientEntity = $this->getClientEntity($client);

        $em = $client->getContainer()->get('doctrine')->getManager();
        $currentScore = $em->getConnection()
            ->executeQuery('select vico_id, client_id, name from vico_rating_score limit 1;')
            ->fetchAssociative();

        $crawler = $client->request('POST', '/vico/'.$currentScore['vico_id'].'/ratings', [
            'clientId' => $currentScore['client_id'],
            'ratings' => [
                [
                    'name' => $currentScore['name'],
                    'value' => 5
                ]
            ]
        ]);
        self::assertResponseStatusCodeSame(500);
        self::assertJson($client->getResponse()->getContent());
    }
}
