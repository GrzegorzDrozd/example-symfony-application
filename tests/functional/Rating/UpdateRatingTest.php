<?php

namespace App\Tests\functional\Rating;
use App\Enums\Ratings;
use App\Tests\functional\ClientEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateRatingTest extends WebTestCase
{
    use ClientEntityTrait;
    public function testUpdatingRating()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $currentScore = $em->getConnection()
            ->executeQuery('select id, vico_id, client_id, name, "value" from vico_rating_score limit 1;')
            ->fetchAssociative();

        $crawler = $client->request('PUT', '/vico/'.$currentScore['vico_id'].'/ratings', [
            'clientId' => $currentScore['client_id'],
            'ratings' => [
                [
                    'name' => $currentScore['name'],
                    'value' => (((int)$currentScore['value']) + 1) % 5
                ]
            ]
        ]);
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());

        $updatedScore = $em->getConnection()
            ->executeQuery(
                'select id, vico_id, client_id, name, "value" from vico_rating_score where id = :id limit 1;',
                [
                    'id' => $currentScore['id']
                ]
            )
            ->fetchAssociative();
        self::assertEquals($currentScore['id'], $updatedScore['id']);
    }

    public function testUpdatingRatingWhenClientNotFound()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/vico/1/ratings', [
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
}
