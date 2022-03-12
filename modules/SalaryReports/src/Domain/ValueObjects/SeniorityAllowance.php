<?php

declare(strict_types=1);

namespace SalaryReports\Domain\ValueObjects;

use SalaryReports\Domain\Entities\Employee;

class SeniorityAllowance extends Allowance
{
    public function calculateFor(Employee $employee): float
    {
        return $this->allowanceValue * min(10, $employee->getYearsAtCompany());
    }

    public function getTypeName(): string
    {
        return 'seniority';
    }
}
