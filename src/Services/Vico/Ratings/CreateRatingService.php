<?php

namespace App\Services\Vico\Ratings;

use App\DTOs\Vico\Rating\CreateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Entity\Vico\RatingScore;
use App\Enums\Ratings;
use App\Exception\ClientNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class CreateRatingService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws ClientNotFoundException
     */
    public function addToEntity(Vico $vico, CreateDto $ratingCreateDto): void
    {
        $client = $this->entityManager->find(Client::class, $ratingCreateDto->clientId);
        if (empty($client)) {
            throw new ClientNotFoundException();
        }

        foreach ($ratingCreateDto->ratings as $rating) {
            $ratingScore = new RatingScore();
            $ratingScore->setName(Ratings::fromStringOrEnum($rating['name']));
            $ratingScore->setValue($rating['value']);
            $ratingScore->setClient($client);

            $vico->addRating($ratingScore);
        }

        $this->entityManager->persist($vico);
        $this->entityManager->flush();
    }
}
