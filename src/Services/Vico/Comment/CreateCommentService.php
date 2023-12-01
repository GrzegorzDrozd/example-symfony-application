<?php

namespace App\Services\Vico\Comment;

use App\DTOs\Vico\Comment\CreateDto;
use App\Entity\Client;
use App\Entity\Vico;
use App\Entity\Vico\RatingComment;
use App\Exception\ClientNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class CreateCommentService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws ClientNotFoundException
     */
    public function addToEntity(Vico $vico, CreateDto $vicoCommentCreateDto): void
    {
        $client = $this->entityManager->find(Client::class, $vicoCommentCreateDto->clientId);
        if (empty($client)) {
            throw new ClientNotFoundException();
        }
        $comment = new RatingComment();
        $comment->setContent($vicoCommentCreateDto->comment);
        $comment->setClient($client);
        $comment->setVico($vico);

        $vico->addComment($comment);

        $this->entityManager->persist($vico);
        $this->entityManager->flush();
    }
}
