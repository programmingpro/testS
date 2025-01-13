<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * Метод для получения новостей за указанный период с поддержкой пагинации.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $page
     * @param int $limit
     * @return News[]
     */
    public function findNewsByPeriod(string $startDate, string $endDate, int $page, int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('n')
            ->where('n.pubDate >= :startDate')
            ->andWhere('n.pubDate <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('n.id', 'DESC');

        $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Метод для подсчета количества новостей за указанный период.
     *
     * @param string $startDate Начальная дата периода (в формате Y-m-d).
     * @param string $endDate Конечная дата периода (в формате Y-m-d).
     * @return int Количество новостей за период.
     */
    public function countNewsByPeriod(string $startDate, string $endDate): int
    {
        $queryBuilder = $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->where('n.pubDate >= :startDate')
            ->andWhere('n.pubDate <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
