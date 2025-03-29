<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Advertisement;
use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advertisement>
 *
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    /**
     * Find all advertisements for a specific company
     */
    public function findByCompany(Company $company): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :company')
            ->setParameter('company', $company)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find advertisements for a specific company with optional status filter
     */
    public function findByCompanyAndStatus(Company $company, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.company = :company')
            ->setParameter('company', $company)
            ->orderBy('a.createdAt', 'DESC');

        if ($status !== null) {
            $qb->andWhere('a.status = :status')
                ->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }
    
    /**
     * Save an advertisement
     */
    public function save(Advertisement $advertisement, bool $flush = false): void
    {
        $this->getEntityManager()->persist($advertisement);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove an advertisement
     */
    public function remove(Advertisement $advertisement, bool $flush = false): void
    {
        $this->getEntityManager()->remove($advertisement);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
} 