<?php

namespace App\Services\Vico\Ratings;

use App\DTOs\Vico\Rating\UpdateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Enums\Ratings;
use App\Exception\ClientNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class UpdateRatingService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws ClientNotFoundException
     */
    public function updateOnEntity(Vico $vico, UpdateDto $ratingUpdateDto): void
    {
        $client = $this->entityManager->find(Client::class, $ratingUpdateDto->clientId);
        if (empty($client)) {
            throw new ClientNotFoundException('Client not found');
        }
        $ratingCategories = [];
        $newValues = [];
        foreach($ratingUpdateDto->ratings as $r) {
            if ($r['name'] instanceof Ratings) {
                $ratingCategories[] = $r['name']->value;
                $newValues[$r['name']->value] = $r['value'];
                continue;
            }
            $ratingCategories[] = $r['name'];
            $newValues[$r['name']] = $r['value'];
        }

        $existingCategories = array_map(
            static function ($row) {
                return $row->getName()->value;
            },
            $client->getRatings()->toArray()
        );

        $this->handleUpdate(
            array_intersect($existingCategories, $ratingCategories),
            $client,
            $newValues
        );
        $this->handleRemoval(
            array_diff($existingCategories, $ratingCategories),
            $client
        );

        $this->entityManager->persist($vico);
        $this->entityManager->persist($client);
        $this->entityManager->flush();
    }

    protected function handleUpdate(
        array $ratingsToUpdate,
        Client $client,
        array $newValues
    ): void {
        $currentRatingsByRatingName = [];
        foreach($client->getRatings()->toArray() as $row) {
            $currentRatingsByRatingName[$row->getName()->value] = $row;
        }

        foreach ($ratingsToUpdate as $rating) {
            $ratingScore = $currentRatingsByRatingName[$rating];
            if (empty($ratingScore)) {
                throw new RuntimeException('Rating not found');
            }
            $ratingScore->setValue($newValues[$rating]);
        }
    }

    protected function handleRemoval(
        array $ratingsToRemove,
        Client $client
    ): void {
        $currentRatingsByRatingName = [];
        foreach($client->getRatings()->toArray() as $row) {
            $currentRatingsByRatingName[$row->getName()->value] = $row;
        }
        foreach ($ratingsToRemove as $rating) {
            $ratingScore = $currentRatingsByRatingName[$rating];
            if (empty($ratingScore)) {
                throw new RuntimeException('Rating not found');
            }
            $this->entityManager->remove($ratingScore);
        }
    }
}
