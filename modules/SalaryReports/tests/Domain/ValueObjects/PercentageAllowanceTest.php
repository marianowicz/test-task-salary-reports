<?php

declare(strict_types=1);

namespace SalaryReports\Tests\Domain\ValueObjects;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\ValueObjects\PercentageAllowance;

class PercentageAllowanceTest extends TestCase
{
    /** @test */
    public function it_calculates_allowance_amount_properly_for_0_percent_allowance()
    {
        $this->assertSame(0.0, (new PercentageAllowance(0.0))->calculateFor(
            new Employee(1, 'a', 'b', Carbon::now()->subYears(5), 1000.0)
        ));
    }

    /** @test */
    public function it_calculates_allowance_amount_properly_for_positive_percent_allowance()
    {
        $this->assertSame(300.0, (new PercentageAllowance(30.0))->calculateFor(
            new Employee(1, 'a', 'b', Carbon::now()->subYears(5), 1000.0)
        ));
    }
}
