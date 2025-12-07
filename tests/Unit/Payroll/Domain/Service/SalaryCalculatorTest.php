<?php

declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Service;

use App\Payroll\Domain\Model\BonusType;
use App\Payroll\Domain\Model\Department;
use App\Payroll\Domain\Model\Employee;
use App\Payroll\Domain\Service\SalaryCalculator;
use PHPUnit\Framework\TestCase;

class SalaryCalculatorTest extends TestCase
{
    /**
     * @dataProvider fixedSalaryCases
     */
    public function testCalculateFixedSalary(
        float $remunerationBase,
        float $bonusPerYear,
        int $yearsOfWork,
        int $maxYears,
        float $expectedSalary,
    ): void
    {
        $employee = $this->createMock(Employee::class);
        $department = $this->createMock(Department::class);
        $department->method('getMaxBonusYears')->willReturn($maxYears);
        $department->method('getBonusAmount')->willReturn($bonusPerYear);
        $department->method('getBonusType')->willReturn(BonusType::FIXED);
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getYearsOfWork')->willReturn($yearsOfWork);
        $employee->method('getRemunerationBase')->willReturn($remunerationBase);

        $salaryCalculator = new SalaryCalculator();
        $result = $salaryCalculator->calculateSalary($employee);

        $this->assertEquals($expectedSalary, $result);
    }

    /**
     * @dataProvider fixedBonusCases
     */
    public function testCalculateFixedBonus(
        float $remunerationBase,
        float $bonusPerYear,
        int $yearsOfWork,
        int $maxYears,
        float $expectedBonus,
    ): void
    {
        $employee = $this->createMock(Employee::class);
        $department = $this->createMock(Department::class);
        $department->method('getMaxBonusYears')->willReturn($maxYears);
        $department->method('getBonusAmount')->willReturn($bonusPerYear);
        $department->method('getBonusType')->willReturn(BonusType::FIXED);
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getYearsOfWork')->willReturn($yearsOfWork);
        $employee->method('getRemunerationBase')->willReturn($remunerationBase);

        $salaryCalculator = new SalaryCalculator();
        $result = $salaryCalculator->calculateBonus($employee);

        $this->assertEquals($expectedBonus, $result);
    }

    /**
     * @dataProvider percentSalaryCases
     */
    public function testCalculatePercentSalary(
        float $remunerationBase,
        float $bonusPerYear,
        int $yearsOfWork,
        int $maxYears,
        float $expectedSalary,
    ): void
    {
        $employee = $this->createMock(Employee::class);
        $department = $this->createMock(Department::class);
        $department->method('getMaxBonusYears')->willReturn($maxYears);
        $department->method('getBonusAmount')->willReturn($bonusPerYear);
        $department->method('getBonusType')->willReturn(BonusType::PERCENT);
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getYearsOfWork')->willReturn($yearsOfWork);
        $employee->method('getRemunerationBase')->willReturn($remunerationBase);

        $salaryCalculator = new SalaryCalculator();
        $result = $salaryCalculator->calculateSalary($employee);

        $this->assertEquals($expectedSalary, $result);
    }

    /**
     * @dataProvider percentBonusCases
     */
    public function testCalculatePercentBonus(
        float $remunerationBase,
        float $bonusPerYear,
        int $yearsOfWork,
        int $maxYears,
        float $expectedSalary,
    ): void
    {
        $employee = $this->createMock(Employee::class);
        $department = $this->createMock(Department::class);
        $department->method('getMaxBonusYears')->willReturn($maxYears);
        $department->method('getBonusAmount')->willReturn($bonusPerYear);
        $department->method('getBonusType')->willReturn(BonusType::PERCENT);
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getYearsOfWork')->willReturn($yearsOfWork);
        $employee->method('getRemunerationBase')->willReturn($remunerationBase);

        $salaryCalculator = new SalaryCalculator();
        $result = $salaryCalculator->calculateBonus($employee);

        $this->assertEquals($expectedSalary, $result);
    }

    public function fixedSalaryCases(): \Generator
    {
        yield [1000, 100, 5, 2, 1200];
        yield [3000, 150, 3, 5, 3450];
        yield [1000, 100, 3, 3, 1300];
        yield [1000, 0, 3, 3, 1000];
        yield [100, 1000, 3, 2, 2100];
    }

    public function fixedBonusCases(): \Generator
    {
        yield [1000, 100, 5, 2, 200];
        yield [3000, 150, 3, 5, 450];
        yield [1000, 100, 3, 3, 300];
        yield [1000, 0, 3, 3, 0];
        yield [100, 1000, 3, 2, 2000];
    }

    public function percentSalaryCases(): \Generator
    {
        yield [1000, 15, 5, 2, 1322.50];
        yield [3000, 10, 3, 5, 3993];
        yield [1000, 5, 3, 3, 1157.63];
        yield [1000, 0, 3, 3, 1000];
        yield [100, 100, 3, 2, 400];
    }

    public function percentBonusCases(): \Generator
    {
        yield [1000, 15, 5, 2, 322.50];
        yield [3000, 10, 3, 5, 993];
        yield [1000, 5, 3, 3, 157.63];
        yield [1000, 0, 3, 3, 0];
        yield [100, 100, 3, 2, 300];
    }
}
