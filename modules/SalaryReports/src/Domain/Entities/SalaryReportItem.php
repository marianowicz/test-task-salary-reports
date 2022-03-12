<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Entities;

use SalaryReports\Domain\ValueObjects\Allowance;
use SalaryReports\Domain\ValueObjects\Salary;

class SalaryReportItem
{
    private Employee $employee;
    private Allowance $allowance;

    private string $departmentName;
    private float $allowanceAmount;
    private float $totalSalaryAmount;

    public function __construct(Employee $employee, string $departmentName, Allowance $allowance)
    {
        $this->employee = $employee;
        $this->departmentName = $departmentName;
        $this->allowance = $allowance;
    }

    public function getFirstName(): string
    {
        return $this->employee->getFirstName();
    }

    public function getLastName(): string
    {
        return $this->employee->getLastName();
    }

    public function getDepartmentName(): string
    {
        return $this->departmentName;
    }

    public function getBaseSalary(): float
    {
        return $this->employee->getBaseSalary();
    }

    public function getAllowanceAmount(): float
    {
        if (!isset($this->allowanceAmount)) {
            $this->allowanceAmount = $this->allowance->calculateFor($this->employee);
        }

        return $this->allowanceAmount;
    }

    public function getAllowanceType(): string
    {
        return $this->allowance->getTypeName();
    }

    public function getTotalSalary(): float
    {
        if (!isset($this->totalSalaryAmount)) {
            $this->totalSalaryAmount = $this->getBaseSalary() + $this->getAllowanceAmount();
        }

        return $this->totalSalaryAmount;
    }
}
