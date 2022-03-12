<?php

declare(strict_types=1);

namespace SalaryReports\Domain\ValueObjects;

use SalaryReports\Domain\Entities\Employee;

abstract class Allowance
{
    protected float $allowanceValue;

    public function __construct(float $allowanceValue)
    {
        $this->allowanceValue = $allowanceValue;
    }

    abstract public function calculateFor(Employee $employee): float;

    abstract public function getTypeName(): string;
}
