<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Repository;

use App\Payroll\Domain\Model\Department;

interface DepartmentRepositoryInterface
{
    public function save(Department $department): void;

    public function findByName(string $name): ?Department;
}
