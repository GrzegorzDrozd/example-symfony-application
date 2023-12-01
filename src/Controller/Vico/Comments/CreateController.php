<?php

declare(strict_types=1);

namespace App\Controller\Vico\Comments;

use App\DTOs\Vico\Comment\CreateDto;
use App\Entity\Vico;
use App\Exception\ClientNotFoundException;
use App\Services\Vico\Comment\CreateCommentService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{
    public function __construct(
        protected CreateCommentService $createCommentService
    ) {
    }

    #[Route('/vico/{vicoId}/comment', name: 'create_new_comment', requirements: ['vicoId' => '\d+'], methods: 'POST', format: 'json')]
    public function newComment(
        #[MapRequestPayload]
        CreateDto $commentCreateDto,
        #[MapEntity(id: 'vicoId')]
        Vico $vico,
    ): JsonResponse {
        try {
            $this->createCommentService->addToEntity($vico, $commentCreateDto);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'One company can submit only one score of given type or one comment.'
                ], 400
            );
        } catch (ClientNotFoundException $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Client not found'], 404);
        }

        return new JsonResponse(['status' => 'ok'], 200);
    }
}
