<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Entities;

class PayrollItem
{
    private Employee $employee;
    private Department $department;

    public function __construct(Employee $employee, Department $department)
    {
        $this->employee = $employee;
        $this->department = $department;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }
}
