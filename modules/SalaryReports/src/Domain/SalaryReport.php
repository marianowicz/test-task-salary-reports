<?php

declare(strict_types=1);

namespace SalaryReports\Domain;

use Illuminate\Support\Collection;
use SalaryReports\Domain\Entities\SalaryReportItem;
use SalaryReports\Domain\Exceptions\InvalidSortColumnException;

class SalaryReport
{
    public const FIRST_NAME = 'FirstName';
    public const LAST_NAME = 'LastName';
    public const DEPARTMENT_NAME = 'DepartmentName';
    public const BASE_SALARY = 'BaseSalary';
    public const ALLOWANCE_AMOUNT = 'AllowanceAmount';
    public const ALLOWANCE_TYPE = 'AllowanceType';
    public const TOTAL_SALARY = 'TotalSalary';

    private const AVAILABLE_SORT_COLUMNS = [
        self::FIRST_NAME,
        self::LAST_NAME,
        self::DEPARTMENT_NAME,
        self::BASE_SALARY,
        self::ALLOWANCE_AMOUNT,
        self::ALLOWANCE_TYPE,
        self::TOTAL_SALARY
    ];

    /** @var SalaryReportItem[] */
    private array $items = [];

    public function addItem(SalaryReportItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function sortBy(string $columnName, bool $descending): void
    {
        $getterMethod = $this->determineGetterMethod($columnName);

        $items = new Collection($this->items);
        $this->items = $items->sortBy(function(SalaryReportItem $item) use ($getterMethod, $descending) {
            return $item->{$getterMethod}(); // @TODO ouch, that's tricky... - think if this should not be replaced by something else...
        }, SORT_REGULAR, $descending)->values()->all();
    }

    private function determineGetterMethod(string $columnName): string
    {
        if (!in_array($columnName, self::AVAILABLE_SORT_COLUMNS)) {
            throw new InvalidSortColumnException();
        }

        return 'get' . $columnName; // @TODO see above
    }
}
