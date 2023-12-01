<?php

declare(strict_types=1);

namespace App\DTOs\Vico\Comment;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDto
{
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    public int $clientId;

    #[Assert\NotBlank]
    public string $comment;
}
