<?php

declare(strict_types=1);

namespace App\Controller\Vico\Ratings;

use App\DTOs\Vico\Rating\CreateDto;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Ratings\CreateRatingService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{
    public function __construct(
        protected CreateRatingService $createRatingService
    ) {
    }

    #[Route('/vico/{vicoId}/ratings', name: 'create_new_rating', requirements: ['vicoId' => '\d+'], methods: 'POST')]
    public function newRating(
        #[MapRequestPayload]
        CreateDto $ratingCreateDto,
        #[MapEntity(id: 'vicoId')]
        Vico $vico,
    ): JsonResponse {
        try {
            $this->createRatingService->addToEntity($vico, $ratingCreateDto);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'One company can submit only one score of given type.'], 500
            );
        } catch (ClientNotFoundException $e) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'Client not found.'], 404
            );
        }

        return new JsonResponse(['status' => 'ok'], 200);
    }
}
