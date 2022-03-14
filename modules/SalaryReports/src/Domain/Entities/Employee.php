<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Entities;

use Carbon\Carbon;

class Employee
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private Carbon $joinedAt;
    private float $baseSalary;

    public function __construct(int $id, string $firstName, string $lastName, Carbon $joinedAt, float $baseSalary)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->joinedAt = $joinedAt;
        $this->baseSalary = $baseSalary; // @TODO should we use Money instead?
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getYearsAtCompany(): int
    {
        return Carbon::now()->diffInYears($this->joinedAt);
    }

    public function getBaseSalary(): float
    {
        return $this->baseSalary;
    }
}
