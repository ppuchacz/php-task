<?php

declare(strict_types=1);

namespace App\Payroll\Application\Handler;

use App\Payroll\Application\Query\ListPayrollQuery;
use App\Payroll\Application\ViewModel\PayrollViewModel;
use App\Payroll\Domain\Repository\DepartmentRepositoryInterface;
use App\Payroll\Domain\Repository\EmployeeRepositoryInterface;
use App\Payroll\Domain\Service\SalaryCalculator;

class ListPayrollQueryHandler
{
    const VIEW_DEFAULT_ORDER_BY_DIR = 'asc';

    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly SalaryCalculator $salaryCalculator,
    ) {
    }

    /**
     * @return PayrollViewModel[]
     */
    public function handle(ListPayrollQuery $query): array
    {
        $department = $query->getDepartment() !== null ?
            $this->departmentRepository->findByName($query->getDepartment()) : null;

        $criteria = [
            'name' => $query->getName(),
            'surname' => $query->getSurname(),
            ];

        $criteria = array_filter($criteria, fn ($value): bool => $value !== null);

        if ($query->getDepartment() !== null) {
            $criteria['department'] = $department;
        }
        $orderBy = $query->getOrderByColumn() !== null ? [
            $query->getOrderByColumn() => $query->getOrderByDirection() ?? self::VIEW_DEFAULT_ORDER_BY_DIR
        ] : null;

        $employees = $this->employeeRepository->findBy(
            $criteria,
            $orderBy,
            $query->getLimit(),
            ($query->getPage() - 1) * $query->getLimit(),
        );

        $result = [];

        foreach ($employees as $employee) {
            $result[] = new PayrollViewModel(
                $employee->getName(),
                $employee->getSurname(),
                $employee->getDepartment()->getName(),
                $employee->getRemunerationBase(),
                $this->salaryCalculator->calculateBonus($employee),
                $employee->getDepartment()->getBonusType()->value,
                $this->salaryCalculator->calculateSalary($employee),
            );
        }

        return $result;
    }
}
