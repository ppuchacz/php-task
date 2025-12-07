<?php

declare(strict_types=1);

namespace App\Payroll\Application\Query;

class ListPayrollQuery
{
    public function __construct(
        private ?string $department = null,
        private ?string $name = null,
        private ?string $surname = null,
        private int $page = 1,
        private int $limit = 20,
        private ?string $orderByColumn = null,
        private ?string $orderByDirection = null,
    ) {
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOrderByColumn(): ?string
    {
        return $this->orderByColumn;
    }

    public function getOrderByDirection(): ?string
    {
        return $this->orderByDirection;
    }
}
