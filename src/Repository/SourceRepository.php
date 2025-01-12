<?php

namespace App\Repository;

use App\Entity\News;
use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Source>
 */
class SourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Source::class);
    }

    /**
     * Считает количество новостей для источника за указанный период.
     *
     * @param Source $source
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return int
     */
    public function countNewsBySourceAndPeriod(Source $source, \DateTimeInterface $startDate = new \DateTime('-1 day'), \DateTimeInterface $endDate = new \DateTime('now')): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(n.id)')
            ->leftJoin('s.news', 'n')
            ->andWhere('s.id = :sourceId')
            ->andWhere('n.pubDate BETWEEN :startDate AND :endDate')
            ->setParameter('sourceId', $source->getId())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
