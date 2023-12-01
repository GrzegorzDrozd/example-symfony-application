<?php

namespace App\Tests\functional\Comments;
use App\Entity\Client;
use App\Tests\functional\ClientEntityTrait;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function date;

class AddingCommentTest extends WebTestCase
{
    use ClientEntityTrait;

    public function testAddingRating()
    {

        $client = static::createClient();

        $clientEntity = $this->getClientEntity($client);

        $crawler = $client->request('POST', '/vico/1/comment', [
            'clientId' => $clientEntity->getId(),
            'comment' => 'test comment'
        ]);
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());
    }

    public function testAddingCommentClientNotFound()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/vico/1/comment', [
            'clientId' => 9999999,
            'comment' => 'test comment'
        ]);
        self::assertResponseStatusCodeSame(404);
        self::assertJson($client->getResponse()->getContent());
    }

    public function testAddingCommentAgain()
    {
        $client = static::createClient();
        // make sure client has a comment
        $clientInformation = $client->getContainer()->get('doctrine')
            ->getManager()->getConnection()
            ->executeQuery('
SELECT 
    c.id
FROM 
    client as c left join
    ratings.vico_rating_comment as cmt on (c.id = cmt.client_id) 
LIMIT 1'
            )->fetchAssociative();

        $crawler = $client->request('POST', '/vico/1/comment', [
            'clientId' => $clientInformation['id'],
            'comment' => 'test comment'
        ]);
        self::assertResponseStatusCodeSame(400);
        self::assertJson($client->getResponse()->getContent());
    }
}
