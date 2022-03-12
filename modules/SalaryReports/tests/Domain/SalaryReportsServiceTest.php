<?php

namespace SalaryReports\Tests\Domain;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SalaryReports\Domain\Entities\Department;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\Factories\AllowanceFactory;
use SalaryReports\Domain\SalaryReport;
use SalaryReports\Domain\SalaryReportsService;
use SalaryReports\Infrastructure\Repositories\InMemoryPayrollRepository;

class SalaryReportsServiceTest extends TestCase
{
    /**
     * we treat the whole module as a unit in this test, so we do not mock anything,
     * we just replace I/O with an in-memory implementation
     */

    private SalaryReportsService $salaryReportsService;
    private InMemoryPayrollRepository $payrollRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->payrollRepository = new InMemoryPayrollRepository();
        $this->salaryReportsService = new SalaryReportsService(
            $this->payrollRepository,
            new AllowanceFactory()
        );
    }

    /** @test */
    public function it_generates_an_empty_salary_report()
    {
        $report = $this->salaryReportsService->generateReport();

        $this->assertInstanceOf(SalaryReport::class, $report);
        $this->assertCount(0, $report->getItems());
    }

    /** @test */
    public function it_generates_an_example_salary_report_properly()
    {
        $this->payrollRepository->addItem(
            new Employee(1, 'Adam', 'Kowalski', Carbon::now()->subYears(15), 1000.0),
            new Department(1, 'HR', 'seniority', 100.0)
        );

        $this->payrollRepository->addItem(
            new Employee(2, 'Ania', 'Nowak', Carbon::now()->subYears(5), 1100.0),
            new Department(2, 'Customer Service', 'percentage', 10.0)
        );

        $this->salaryReportsService = new SalaryReportsService($this->payrollRepository, new AllowanceFactory());

        $report = $this->salaryReportsService->generateReport();
        $items = $report->getItems();
        $this->assertCount(2, $items);
        $this->assertSame([
                'Adam',
                'Kowalski',
                'HR',
                1000.0,
                1000.0,
                'seniority',
                2000.0,
            ], [
                $items[0]->getFirstName(),
                $items[0]->getLastName(),
                $items[0]->getDepartmentName(),
                $items[0]->getBaseSalary(),
                $items[0]->getAllowanceAmount(),
                $items[0]->getAllowanceType(),
                $items[0]->getTotalSalary(),
        ]);
        $this->assertSame([
            'Ania',
            'Nowak',
            'Customer Service',
            1100.0,
            110.0,
            'percentage',
            1210.0,
        ], [
            $items[1]->getFirstName(),
            $items[1]->getLastName(),
            $items[1]->getDepartmentName(),
            $items[1]->getBaseSalary(),
            $items[1]->getAllowanceAmount(),
            $items[1]->getAllowanceType(),
            $items[1]->getTotalSalary(),
        ]);
    }

    /** @test */
    public function it_can_search_items_by_department_name()
    {
        $this->payrollRepository->addItem(
            new Employee(1, 'Adam', 'Kowalski', Carbon::now()->subYears(15), 1000.0),
            new Department(1, 'HR', 'seniority', 100.0)
        );

        $this->payrollRepository->addItem(
            new Employee(2, 'Ania', 'Nowak', Carbon::now()->subYears(5), 1100.0),
            new Department(2, 'Customer Service', 'percentage', 10.0)
        );

        $this->salaryReportsService = new SalaryReportsService($this->payrollRepository, new AllowanceFactory());

        $report = $this->salaryReportsService->generateReport('HR');
        $items = $report->getItems();
        $this->assertCount(1, $items);
        $this->assertSame('Kowalski', $items[0]->getLastName());
    }
}
