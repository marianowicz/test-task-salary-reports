<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Entities;

class Department
{
    private int $id;
    private string $name;
    private string $allowanceType;
    private float $allowanceValue;

    public function __construct(int $id, string $name, string $allowanceType, float $allowanceValue)
    {
        $this->id = $id;
        $this->name = $name;
        $this->allowanceType = $allowanceType;
        $this->allowanceValue = $allowanceValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAllowanceType(): string
    {
        return $this->allowanceType;
    }

    public function getAllowanceValue(): float
    {
        return $this->allowanceValue;
    }
}
