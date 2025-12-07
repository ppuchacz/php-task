<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Service;

use App\Payroll\Domain\Model\BonusType;
use App\Payroll\Domain\Model\Employee;

class SalaryCalculator
{
    public function calculateSalary(Employee $employee): float
    {
        return $employee->getRemunerationBase() + $this->calculateBonus($employee);
    }

    public function calculateBonus(Employee $employee): float
    {
        $department = $employee->getDepartment();
        switch ($department->getBonusType()) {
            case BonusType::FIXED:
                return $this->calcFixedBonus($employee);
            case BonusType::PERCENT:
                return $this->calcPercentBonus($employee);
            default:
                throw new \Exception('Unsupported bonus type');
        }
    }

    private function calcFixedBonus(Employee $employee): float
    {
        $years = min($employee->getYearsOfWork(), $employee->getDepartment()->getMaxBonusYears());
        return $years * $employee->getDepartment()->getBonusAmount();
    }

    private function calcPercentBonus(Employee $employee): float
    {
        $years = min($employee->getYearsOfWork(), $employee->getDepartment()->getMaxBonusYears());
        $factor = pow($employee->getDepartment()->getBonusAmount() / 100 + 1, $years) - 1;

        return round($factor * $employee->getRemunerationBase(), 2);
    }
}
