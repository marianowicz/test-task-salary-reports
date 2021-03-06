<?php

declare(strict_types=1);

namespace SalaryReports\Tests\Domain;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use SalaryReports\Domain\DTO\SearchParams;
use SalaryReports\Domain\Entities\Department;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\Entities\SalaryReportItem;
use SalaryReports\Domain\Exceptions\InvalidSortColumnException;
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

    private function addExampleItemsToThePayrollRepository(): void
    {
        $this->payrollRepository->addItem(
            new Employee(1, 'Adam', 'Kowalski', Carbon::now()->subYears(15), 1000.0),
            new Department(1, 'HR', 'seniority', 100.0)
        );

        $this->payrollRepository->addItem(
            new Employee(2, 'Ania', 'Nowak', Carbon::now()->subYears(5), 1100.0),
            new Department(2, 'Customer Service', 'percentage', 10.0)
        );

        $this->payrollRepository->addItem(
            new Employee(2, 'Kazimierz', 'Nowacki', Carbon::now()->subYears(3), 1000.0),
            new Department(1, 'HR', 'seniority', 100.0)
        );
    }

    private function assertItemIs(array $expected, SalaryReportItem $item): void
    {
        $this->assertSame($expected, [
                $item->getFirstName(),
                $item->getLastName(),
                $item->getDepartmentName(),
                $item->getBaseSalary(),
                $item->getAllowanceAmount(),
                $item->getAllowanceType(),
                $item->getTotalSalary(),
            ]
        );
    }

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
        $report = $this->salaryReportsService->generateReport(new SearchParams());

        $this->assertInstanceOf(SalaryReport::class, $report);
        $this->assertCount(0, $report->getItems());
    }

    /** @test */
    public function it_generates_an_example_salary_report_properly()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport(new SearchParams());
        $items = $report->getItems();

        $this->assertCount(3, $items);
        $this->assertItemIs(['Adam', 'Kowalski', 'HR', 1000.0, 1000.0, 'seniority', 2000.0], $items[0]);
        $this->assertItemIs(['Ania', 'Nowak', 'Customer Service', 1100.0, 110.0, 'percentage', 1210.0], $items[1]);
        $this->assertItemIs(['Kazimierz', 'Nowacki', 'HR', 1000.0, 300.0, 'seniority', 1300.0], $items[2]);
    }

    /** @test */
    public function it_generates_a_report_filtered_by_department_name()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport((new SearchParams())->setSearch('HR'));
        $items = $report->getItems();

        $this->assertCount(2, $items);
        $this->assertItemIs(['Adam', 'Kowalski', 'HR', 1000.0, 1000.0, 'seniority', 2000.0], $items[0]);
        $this->assertItemIs(['Kazimierz', 'Nowacki', 'HR', 1000.0, 300.0, 'seniority', 1300.0], $items[1]);
    }

    /** @test */
    public function it_generates_a_report_filtered_by_first_name()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport((new SearchParams())->setSearch('Ania'));
        $items = $report->getItems();

        $this->assertCount(1, $items);
        $this->assertItemIs(['Ania', 'Nowak', 'Customer Service', 1100.0, 110.0, 'percentage', 1210.0], $items[0]);
    }

    /** @test */
    public function it_generates_a_report_filtered_by_last_name()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport((new SearchParams())->setSearch('Nowa'));
        $items = $report->getItems();

        $this->assertCount(2, $items);
        $this->assertItemIs(['Ania', 'Nowak', 'Customer Service', 1100.0, 110.0, 'percentage', 1210.0], $items[0]);
        $this->assertItemIs(['Kazimierz', 'Nowacki', 'HR', 1000.0, 300.0, 'seniority', 1300.0], $items[1]);
    }

    /** @test */
    public function it_generates_a_report_sorted_by_a_column_that_is_not_calculated_dynamically()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport(
            (new SearchParams())->setSortBy(SalaryReport::BASE_SALARY, true)
        );
        $items = $report->getItems();

        $this->assertCount(3, $items);
        $this->assertItemIs(['Ania', 'Nowak', 'Customer Service', 1100.0, 110.0, 'percentage', 1210.0], $items[0]);
        $this->assertItemIs(['Adam', 'Kowalski', 'HR', 1000.0, 1000.0, 'seniority', 2000.0], $items[1]);
        $this->assertItemIs(['Kazimierz', 'Nowacki', 'HR', 1000.0, 300.0, 'seniority', 1300.0], $items[2]);
    }

    /** @test */
    public function it_generates_a_report_that_is_both_filtered_and_sorted_by_a_column_that_is_calculated_dynamically()
    {
        $this->addExampleItemsToThePayrollRepository();
        $report = $this->salaryReportsService->generateReport(
            (new SearchParams())->setSearch('HR')->setSortBy(SalaryReport::TOTAL_SALARY)
        );
        $items = $report->getItems();

        $this->assertCount(2, $items);
        $this->assertItemIs(['Kazimierz', 'Nowacki', 'HR', 1000.0, 300.0, 'seniority', 1300.0], $items[0]);
        $this->assertItemIs(['Adam', 'Kowalski', 'HR', 1000.0, 1000.0, 'seniority', 2000.0], $items[1]);
    }

    /** @test */
    public function it_throws_an_exception_if_we_try_to_sort_report_by_column_that_is_not_allowed()
    {
        $this->expectException(InvalidSortColumnException::class);

        $this->addExampleItemsToThePayrollRepository();
        $this->salaryReportsService->generateReport(
            (new SearchParams())->setSortBy('some_not_allowed_column')
        );
    }
}
