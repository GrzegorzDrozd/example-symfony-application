<?php

declare(strict_types=1);

namespace App\Controller\Vico\Comments;

use App\DTOs\Vico\Comment\UpdateDto;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Exception\VicoCommentNotFoundException;
use App\Services\Vico\Comment\UpdateCommentService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    public function __construct(
        protected UpdateCommentService $updateCommentService
    ) {
    }

    #[Route('/vico/{vicoId}/comment', name: 'udate_comment', requirements: ['vicoId' => '\d+'], methods: 'PUT', format: 'json')]
    public function newComment(
        #[MapRequestPayload]
        UpdateDto $commentUpdateDto,
        #[MapEntity(id: 'vicoId')]
        Vico $vico,
    ): JsonResponse {
        try {
            $this->updateCommentService->updateOnEntity($vico, $commentUpdateDto);
        } catch (VicoCommentNotFoundException $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Comment not found'], 404);
        } catch (ClientNotFoundException $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Client not found'], 404);
        }

        return new JsonResponse(['status' => 'ok'], 200);
    }
}
