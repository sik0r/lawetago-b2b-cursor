<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * Check if a company with the given NIP already exists.
     */
    public function existsByNip(string $nip): bool
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.nip = :nip')
            ->setParameter('nip', $nip)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * Find companies with pagination.
     *
     * @param int $page  The page number
     * @param int $limit The number of items per page
     *
     * @return array<Company> The paginated companies
     */
    public function findPaginated(int $page = 1, int $limit = 10): array
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}
