<?php

declare(strict_types=1);

namespace App\Repository\Vico;

use App\Entity\Vico\RatingComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RatingComment>
 *
 * @method RatingComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingComment[]    findAll()
 * @method RatingComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingCommentRepository extends ServiceEntityRepository
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingComment::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getCommentByVicoIdAndCompanyId(int $vicoId, int $companyId): ?RatingComment
    {
        $qb = $this->createQueryBuilder('vrc');
        $qb->select('vrc')
            ->innerJoin('vrc.client', 'c')
            ->innerJoin('vrc.vico', 'v')
            ->where('v.id = :vicoId')
            ->andWhere('c.id = :companyId')
            ->setParameter('vicoId', $vicoId)
            ->setParameter('companyId', $companyId)
            ->orderBy('vrc.created', 'DESC');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
