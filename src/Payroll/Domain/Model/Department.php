<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Model;

class Department
{
    private ?int $id = null;

    public function __construct(
        private string $name,
        private BonusType $bonusType,
        private int $maxBonusYears,
        private float $bonusAmount,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBonusType(): BonusType
    {
        return $this->bonusType;
    }

    public function getMaxBonusYears(): int
    {
        return $this->maxBonusYears;
    }

    public function getBonusAmount(): float
    {
        return $this->bonusAmount;
    }
}
