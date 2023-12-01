<?php

declare(strict_types=1);

namespace App\Controller\Vico\Ratings;

use App\DTOs\Vico\Rating\UpdateDto;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Ratings\UpdateRatingService;
use Exception;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateRatingController extends AbstractController
{
    public function __construct(
        protected UpdateRatingService $updateRatingService
    ) {
    }

    #[Route('/vico/{vicoId}/ratings', name: 'update_new_rating', requirements: ['vicoId'   => '\d+',
                                                                                'ratingId' => '\d+'
    ], methods: 'PUT')]
    public function updateRating(
        #[MapRequestPayload]
        UpdateDto $ratingUpdateDto,
        #[MapEntity(id: 'vicoId')]
        Vico $vico,
    ): JsonResponse {
        try {
            $this->updateRatingService->updateOnEntity($vico, $ratingUpdateDto);
        } catch (ClientNotFoundException $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }
        return new JsonResponse(['status' => 'ok'], 200);
    }
}
