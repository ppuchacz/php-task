<?php

declare(strict_types=1);

namespace App\Payroll\Infrastructure\Persistance\Doctrine\Repository;

use App\Payroll\Domain\Model\Department;
use App\Payroll\Domain\Repository\DepartmentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository implements DepartmentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $department): void
    {
        $this->getEntityManager()->persist($department);
        $this->getEntityManager()->flush();
    }

    public function findByName(string $name): ?Department
    {
        return $this->findOneBy(['name' => $name]);
    }
}
