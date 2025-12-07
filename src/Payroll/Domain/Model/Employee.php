<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Model;

class Employee
{
    private ?int $id = null;

    public function __construct(
        private string     $name,
        private string     $surname,
        private float      $remunerationBase,
        private Department $department,
        private \DateTime      $jobStartDate,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getRemunerationBase(): float
    {
        return $this->remunerationBase;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getJobStartDate(): \DateTime
    {
        return $this->jobStartDate;
    }

    public function getYearsOfWork(): int
    {
        return $this->jobStartDate->diff(new \DateTime())->y;
    }
}
