<?php

namespace App\Tests\functional\Comments;
use App\Tests\functional\ClientEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdatingCommentTest extends WebTestCase
{
    use ClientEntityTrait;
    public function testUpdatingExistingComment()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')
            ->getManager();

        $clientInformation = $em->getConnection()
            ->executeQuery('
SELECT 
    c.id, cmt.id as comment_id
FROM 
    client as c left join
    ratings.vico_rating_comment as cmt on (c.id = cmt.client_id) 
LIMIT 1'
            )->fetchAssociative();

        $newCommentContent = 'test comment'.base64_encode(random_bytes(10)).date('Y-m-d H:i:s');
        $crawler = $client->request('PUT', '/vico/1/comment', [
            'clientId' => $clientInformation['id'],
            'comment' => $newCommentContent
        ]);
        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());

        $comment = $em->getConnection()
            ->executeQuery('
SELECT 
    id, content, client_id, vico_id
FROM 
    ratings.vico_rating_comment 
WHERE 
    client_id = :clientId and
    vico_id = :vicoId
LIMIT 1',
                [
                    'clientId' => $clientInformation['id'],
                    'vicoId' => 1
                ]
            )->fetchAssociative();
        self::assertSame($newCommentContent, $comment['content']);
    }

    public function testUpdatingNonExistingComment()
    {
        $client = static::createClient();
        $clientEntity = $this->getClientEntity($client);

        $crawler = $client->request('PUT', '/vico/1/comment', [
            'clientId' => $clientEntity->getId(),
            'comment' => 'test'
        ]);
        self::assertResponseStatusCodeSame(404);
        self::assertJson($client->getResponse()->getContent());
    }
    public function testUpdatingWithoutClient()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/vico/1/comment', [
            'clientId' => 99999,
            'comment' => 'test'
        ]);
        self::assertResponseStatusCodeSame(404);
        self::assertJson($client->getResponse()->getContent());
    }
}
