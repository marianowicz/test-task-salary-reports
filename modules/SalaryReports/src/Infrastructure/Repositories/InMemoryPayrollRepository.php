<?php

declare(strict_types=1);

namespace SalaryReports\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SalaryReports\Domain\Entities\PayrollItem;
use SalaryReports\Domain\PayrollData;
use SalaryReports\Domain\Entities\Department;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\Repositories\PayrollRepositoryInterface;

class InMemoryPayrollRepository implements PayrollRepositoryInterface
{
    private PayrollData $payrollData;

    public function __construct()
    {
        $this->payrollData = new PayrollData();
    }

    public function addItem(Employee $employee, Department $department)
    {
        $this->payrollData->addItem(new PayrollItem($employee, $department));
    }

    public function getPayrollData(string $search = ''): PayrollData
    {
        if (!$search) {
            return $this->payrollData;
        }

        $items = new Collection($this->getPayrollData()->getItems());

        $filtered = $items->filter(function (PayrollItem $item) use ($search) {
            return Str::contains($item->getDepartment()->getName(), $search, true);
        });

        return PayrollData::createFromItems($filtered->all());
    }
}
