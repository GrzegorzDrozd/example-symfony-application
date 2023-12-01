<?php

namespace App\Services\Vico\Comment;

use App\DTOs\Vico\Comment\UpdateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Exception\VicoCommentNotFoundException;
use App\Repository\Vico\RatingCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class UpdateCommentService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RatingCommentRepository $vicoRatingCommentRepository,
    ) {
    }

    /**
     * @throws VicoCommentNotFoundException
     * @throws ClientNotFoundException
     */
    public function updateOnEntity(Vico $vico, UpdateDto $vicoCommentUpdateDto): void
    {
        $client = $this->entityManager->find(Client::class, $vicoCommentUpdateDto->clientId);

        if (empty($client)) {
            throw new ClientNotFoundException();
        }
        try {
            $comment = $this->vicoRatingCommentRepository->getCommentByVicoIdAndCompanyId(
                $vico->getId(),
                $client->getId()
            );
        } catch (NonUniqueResultException $e) {
        }
        if (empty($comment)) {
            throw new VicoCommentNotFoundException();
        }
        $comment->setContent($vicoCommentUpdateDto->comment);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
