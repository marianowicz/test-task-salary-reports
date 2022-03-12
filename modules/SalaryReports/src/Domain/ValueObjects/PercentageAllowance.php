<?php

declare(strict_types=1);

namespace SalaryReports\Domain\ValueObjects;

use SalaryReports\Domain\Entities\Employee;

class PercentageAllowance extends Allowance
{
    public function calculateFor(Employee $employee): float
    {
        return $employee->getBaseSalary() * $this->allowanceValue / 100;
    }

    public function getTypeName(): string
    {
        return 'percentage';
    }
}
