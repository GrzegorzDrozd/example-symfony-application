<?php

declare(strict_types=1);

namespace App\Repository\Vico;

use App\Entity\Vico\RatingScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RatingScore>
 *
 * @method RatingScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingScore[]    findAll()
 * @method RatingScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingScoreRepository extends ServiceEntityRepository
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingScore::class);
    }
}
