<?php

namespace App\Tests\unit\Services\Vico\Comment;

use App\DTOs\Vico\Comment\UpdateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Entity\Vico\RatingComment;
use App\Exception\ClientNotFoundException;
use App\Exception\VicoCommentNotFoundException;
use App\Repository\Vico\RatingCommentRepository;
use App\Services\Vico\Comment\UpdateCommentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdateCommentServiceTest extends TestCase
{
    public function testUpdatingComment(): void
    {
        $clientId = rand(1, 100);
        $commentContent = 'test'.rand(1, 100);

        $client = new Client();
        $client->setId($clientId);

        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('vico name');

        $commentMock = $this->createMock(RatingComment::class);
        $commentMock->expects(self::once())->method('setContent')->with($commentContent);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('persist')->with($commentMock);
        $emMock->expects(self::once())->method('flush');
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn($client);

        $repositoryMock = $this->createMock(RatingCommentRepository::class);
        $repositoryMock->expects(self::once())
            ->method('getCommentByVicoIdAndCompanyId')
            ->with($vico->getId(), $client->getId())
            ->willReturn($commentMock);

        $dto = new UpdateDto();
        $dto->comment = $commentContent;
        $dto->clientId = $clientId;

        $this->object = new UpdateCommentService(
            $emMock,
            $repositoryMock
        );

        try {
            $this->object->updateOnEntity($vico, $dto);
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testUpdatingCommentWhenClientNotFound(): void
    {
        $this->expectException(ClientNotFoundException::class);

        $clientId = rand(1, 100);
        $commentContent = 'test'.rand(1, 100);

        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('vico name');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn(null);

        $repositoryMock = $this->createMock(RatingCommentRepository::class);

        $dto = new UpdateDto();
        $dto->comment = $commentContent;
        $dto->clientId = $clientId;

        $this->object = new UpdateCommentService(
            $emMock,
            $repositoryMock
        );

        $this->object->updateOnEntity($vico, $dto);
    }


    public function testUpdatingCommentCommentNotFound(): void
    {
        $this->expectException(VicoCommentNotFoundException::class);

        $clientId = rand(1, 100);
        $commentContent = 'test'.rand(1, 100);

        $client = new Client();
        $client->setId($clientId);

        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('vico name');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn($client);

        $repositoryMock = $this->createMock(RatingCommentRepository::class);
        $repositoryMock->expects(self::once())
            ->method('getCommentByVicoIdAndCompanyId')
            ->with($vico->getId(), $client->getId())
            ->willReturn(null);

        $dto = new UpdateDto();
        $dto->comment = $commentContent;
        $dto->clientId = $clientId;

        $this->object = new UpdateCommentService(
            $emMock,
            $repositoryMock
        );

        try {
            $this->object->updateOnEntity($vico, $dto);
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }
}
