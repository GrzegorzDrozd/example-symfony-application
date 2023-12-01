<?php

declare(strict_types=1);

namespace App\DTOs\Vico\Rating;

use App\Util\ArrayAccessTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property CreateScoreItemDto[] $ratings
 */
class CreateDto
{
    use ArrayAccessTrait;
    /** @var array<CreateScoreItemDto> */
    public array $ratings;

    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    public int $clientId;
}
