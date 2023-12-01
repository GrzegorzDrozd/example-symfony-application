<?php

namespace App\Tests\unit\Services\Vico\Ratings;

use App\DTOs\Vico\Rating\UpdateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Enums\Ratings;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Ratings\UpdateRatingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdateRatingServiceTest extends TestCase
{
    public function testUpdatingRating(): void
    {
        $clientId = rand(1, 100);
        $client = $this->createMock(Client::class);

        $currentRatings = [
            (new Vico\RatingScore())->setName(Ratings::VALUE)->setValue(5),
            (new Vico\RatingScore())->setName(Ratings::QUALITY_OF_WORK)->setValue(4),
        ];

        $ratingCollectionMock = $this->getMockBuilder(\Doctrine\Common\Collections\Collection::class)
            ->onlyMethods(['toArray'])
            ->getMockForAbstractClass();
        $ratingCollectionMock->expects(self::exactly(3))->method('toArray')->willReturn($currentRatings);

        $client->setId($clientId);
        $client->expects(self::exactly(3))->method('getRatings')->willReturn($ratingCollectionMock);

        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('vico name');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('flush');
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn($client);
        $emMock->expects(self::exactly(2))->method('persist')->withConsecutive([$vico], [$client]);

        $this->object = new UpdateRatingService(
            $emMock,
        );

        $dto = new UpdateDto();
        $dto->clientId = $clientId;
        $dto->ratings = [
            [
                'name' => Ratings::COMMUNICATION->value,
                'value' => 1,
            ],
            [
                'name' => Ratings::VALUE->value,
                'value' => 5,
            ]
        ];

        try {
            $this->object->updateOnEntity($vico, $dto);
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testUpdatingRatingWhenClientNotFound(): void
    {
        $this->expectException(ClientNotFoundException::class);
        $clientId = rand(1, 100);
        $client = $this->createMock(Client::class);
        $client->setId($clientId);

        $vico = new Vico();
        $vico->setId(1);
        $vico->setName('vico name');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $emMock->expects(self::once())->method('find')->with(Client::class, $clientId)->willReturn(null);

        $this->object = new UpdateRatingService(
            $emMock,
        );

        $dto = new UpdateDto();
        $dto->clientId = $clientId;

        $this->object->updateOnEntity($vico, $dto);
    }
}
