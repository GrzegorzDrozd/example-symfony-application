<?php

namespace App\Tests\unit\Services\Vico\Comment;

use App\DTOs\Vico\Comment\CreateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Comment\CreateCommentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateCommentServiceTest extends TestCase
{
    public function testAddingComment(): void
    {
        $clientId = rand(1, 100);
        $client = new Client();
        $client->setId($clientId);

        $vico = $this->createMock(Vico::class);
        $vico->setId(1);
        $vico->setName('test');

        $vico->expects(self::once())->method('addComment')->with($this->callback(function ($savedComment) use ($client, $vico) {
            return $savedComment->getClient() === $client && $savedComment->getVico() === $vico;
        }));

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('persist')->with($vico);
        $emMock->expects(self::once())->method('flush');
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn($client);

        $dto = new CreateDto();
        $dto->comment = 'test';
        $dto->clientId = $clientId;

        $this->object = new CreateCommentService(
            $emMock,
        );

        try {
            $this->object->addToEntity($vico, $dto);
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testAddingCommentWhenClientNotFound(): void
    {
        $this->expectException(ClientNotFoundException::class);
        $clientId = 1;

        $vico = $this->createMock(Vico::class);
        $vico->setId(1);
        $vico->setName('test');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn(null);

        $dto = new CreateDto();
        $dto->comment = 'test';
        $dto->clientId = $clientId;

        $this->object = new CreateCommentService(
            $emMock,
        );

        $this->object->addToEntity($vico, $dto);
    }
}
