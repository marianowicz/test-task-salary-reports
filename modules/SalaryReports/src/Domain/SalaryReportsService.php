<?php

declare(strict_types=1);

namespace SalaryReports\Domain;

use SalaryReports\Domain\Entities\SalaryReportItem;
use SalaryReports\Domain\Factories\AllowanceFactory;
use SalaryReports\Domain\Repositories\PayrollRepositoryInterface;

class SalaryReportsService
{
    private PayrollRepositoryInterface $payrollRepository;
    private AllowanceFactory $allowanceFactory;

    public function __construct(PayrollRepositoryInterface $payrollRepository, AllowanceFactory $allowanceFactory)
    {
        $this->payrollRepository = $payrollRepository;
        $this->allowanceFactory = $allowanceFactory;
    }

    public function generateReport(): SalaryReport
    {
        $salaryReport = new SalaryReport();
        foreach ($this->payrollRepository->getPayrollData()->getItems() as $payrollItem) {
            $salaryReport->addItem(new SalaryReportItem(
                $payrollItem->getEmployee(),
                $payrollItem->getDepartment()->getName(),
                $this->allowanceFactory->create(
                    $payrollItem->getDepartment()->getAllowanceType(),
                    $payrollItem->getDepartment()->getAllowanceValue()
                )
            ));
        }

        return $salaryReport;
    }
}
