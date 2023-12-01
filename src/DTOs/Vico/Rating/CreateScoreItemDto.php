<?php

declare(strict_types=1);

namespace App\DTOs\Vico\Rating;

use App\Enums\Ratings;
use App\Util\ArrayAccessTrait;
use Symfony\Component\Validator\Constraints as Assert;

class CreateScoreItemDto implements \ArrayAccess
{
    use ArrayAccessTrait;
    #[Assert\NotBlank]
    public Ratings $name;

    #[Assert\GreaterThan(0)]
    #[Assert\LessThanOrEqual(5)]
    public int $value;
}
