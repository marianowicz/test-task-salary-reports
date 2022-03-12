<?php

declare(strict_types=1);

namespace SalaryReports\Tests\Domain\Factories;

use PHPUnit\Framework\TestCase;
use SalaryReports\Domain\Exceptions\InvalidAllowanceTypeException;
use SalaryReports\Domain\Exceptions\NegativeAllowanceValueException;
use SalaryReports\Domain\Factories\AllowanceFactory;
use SalaryReports\Domain\ValueObjects\PercentageAllowance;
use SalaryReports\Domain\ValueObjects\SeniorityAllowance;

class AllowanceFactoryTest extends TestCase
{
    private AllowanceFactory $allowanceFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->allowanceFactory = new AllowanceFactory();
    }

    /** @test */
    public function it_throws_an_exception_if_allowance_value_is_negative()
    {
        $this->expectException(NegativeAllowanceValueException::class);

        $this->allowanceFactory->create('seniority', -10.0);
    }

    /** @test */
    public function it_throws_an_exception_if_allowance_type_is_invalid()
    {
        $this->expectException(InvalidAllowanceTypeException::class);

        $this->allowanceFactory->create('some_other', 20.0);
    }

    /** @test */
    public function it_properly_creates_an_allowance_of_type_seniority()
    {
        $this->assertInstanceOf(
            SeniorityAllowance::class,
            $this->allowanceFactory->create('seniority', 20.0)
        );
    }

    /** @test */
    public function it_properly_creates_an_allowance_of_type_percentage()
    {
        $this->assertInstanceOf(
            PercentageAllowance::class,
            $this->allowanceFactory->create('percentage', 20.0)
        );
    }
}
