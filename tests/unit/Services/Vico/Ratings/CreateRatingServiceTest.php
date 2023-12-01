<?php

namespace App\Tests\unit\Services\Vico\Ratings;

use App\DTOs\Vico\Rating\CreateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Enums\Ratings;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Ratings\CreateRatingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateRatingServiceTest extends TestCase
{
    public function testAddingRating(): void
    {
        $clientId = rand(1, 100);
        $client = new Client();
        $client->setId($clientId);

        $vico = $this->createMock(Vico::class);
        $vico->setId(1);
        $vico->setName('test');

        $vico->expects(self::once())->method('addRating')->with($this->callback(function ($savedRatingScore) use ($client) {
            return $savedRatingScore->getClient() === $client && $savedRatingScore->getName() === Ratings::COMMUNICATION && $savedRatingScore->getValue() === 1;
        }));

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('persist')->with($vico);
        $emMock->expects(self::once())->method('flush');
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn($client);

        $dto = new CreateDto();
        $dto->ratings = [
            [
                'name' => Ratings::COMMUNICATION->value,
                'value' => 1,
            ]
        ];
        $dto->clientId = $clientId;

        $this->object = new CreateRatingService(
            $emMock,
        );

        try {
            $this->object->addToEntity($vico, $dto);
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testAddingRatingClientNotFound(): void
    {
        $this->expectException(ClientNotFoundException::class);
        $clientId = rand(1, 100);
        $client = new Client();
        $client->setId($clientId);

        $vico = $this->createMock(Vico::class);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn(null);

        $dto = new CreateDto();
        $dto->clientId = $clientId;

        $this->object = new CreateRatingService(
            $emMock,
        );

        $this->object->addToEntity($vico, $dto);
    }
}
