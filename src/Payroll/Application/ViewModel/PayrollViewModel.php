<?php

declare(strict_types=1);

namespace App\Payroll\Application\ViewModel;

class PayrollViewModel
{
    public function __construct(
        private string $name,
        private string $surname,
        private string $department,
        private float $remunerationBase,
        private float $remunerationAddition,
        private string $bonusType,
        private float $salary,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getRemunerationBase(): float
    {
        return $this->remunerationBase;
    }

    public function getRemunerationAddition(): float
    {
        return $this->remunerationAddition;
    }

    public function getBonusType(): string
    {
        return $this->bonusType;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }
}
