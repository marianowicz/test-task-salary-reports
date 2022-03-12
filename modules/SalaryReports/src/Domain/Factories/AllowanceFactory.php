<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Factories;

use SalaryReports\Domain\Exceptions\InvalidAllowanceTypeException;
use SalaryReports\Domain\Exceptions\NegativeAllowanceValueException;
use SalaryReports\Domain\ValueObjects\SeniorityAllowance;
use SalaryReports\Domain\ValueObjects\PercentageAllowance;
use SalaryReports\Domain\ValueObjects\Allowance;

class AllowanceFactory
{
    public const PERCENTAGE = 'percentage';
    public const SENIORITY = 'seniority';

    public function create(string $allowanceType, float $allowanceValue): Allowance
    {
        // @TODO in fact this might be a part of PercentageAllowance / SeniorityAllowance validation
        if ($allowanceValue < 0) {
            throw new NegativeAllowanceValueException();
        }

        switch ($allowanceType) {
            case self::PERCENTAGE:
                return new PercentageAllowance($allowanceValue);
            case self::SENIORITY:
                return new SeniorityAllowance($allowanceValue);
            default:
                throw new InvalidAllowanceTypeException();
        }
    }
}
