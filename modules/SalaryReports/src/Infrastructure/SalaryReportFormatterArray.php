<?php

declare(strict_types=1);

namespace SalaryReports\Infrastructure;

use SalaryReports\Domain\Entities\SalaryReportItem;
use SalaryReports\Domain\SalaryReport;

class SalaryReportFormatterArray
{
    public function format(SalaryReport $salaryReport): array
    {
        return array_map(function(SalaryReportItem $item) {
            return [
                $item->getFirstName(),
                $item->getLastName(),
                $item->getDepartmentName(),
                $item->getBaseSalary(),
                $item->getAllowanceAmount(),
                $item->getAllowanceType(),
                $item->getTotalSalary()
            ];
        }, $salaryReport->getItems());
    }
}
