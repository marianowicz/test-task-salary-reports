<?php

declare(strict_types=1);

namespace SalaryReports\Tests\Domain\ValueObjects;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\ValueObjects\SeniorityAllowance;

class SeniorityAllowanceTest extends TestCase
{
    private SeniorityAllowance $seniorityAllowance;

    public function setUp(): void
    {
        parent::setUp();

        $this->seniorityAllowance = new SeniorityAllowance(120.0);
    }

    /** @test */
    public function it_calculates_allowance_amount_properly_for_employees_with_less_than_one_year_at_the_company()
    {
        $this->assertSame(0.0, $this->seniorityAllowance->calculateFor(
            new Employee(1,'a', 'b', Carbon::now()->subDays(360), 1000.0) // still not a full year
        ));
    }

    /** @test */
    public function it_calculates_allowance_amount_properly_for_employees_between_1_and_10_years_at_the_company()
    {
        $this->assertSame(720.0, $this->seniorityAllowance->calculateFor(
            new Employee(1,'a', 'b', Carbon::now()->subYears(6), 1000.0)
        ));
    }

    /** @test */
    public function it_calculates_allowance_amount_properly_for_employees_with_10_years_at_the_company()
    {
        $this->assertSame(1200.0, $this->seniorityAllowance->calculateFor(
            new Employee(1,'a', 'b', Carbon::now()->subYears(10), 1000.0)
        ));
    }

    /** @test */
    public function it_calculates_allowance_amount_properly_for_employees_with_over_10_years_at_the_company()
    {
        $this->assertSame(1200.0, $this->seniorityAllowance->calculateFor(
            new Employee(1,'a', 'b', Carbon::now()->subYears(15), 1000.0)
        ));
    }
}
