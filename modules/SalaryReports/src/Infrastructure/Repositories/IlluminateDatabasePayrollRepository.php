<?php

declare(strict_types=1);

namespace SalaryReports\Infrastructure\Repositories;

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use SalaryReports\Domain\DTO\SearchParams;
use SalaryReports\Domain\Entities\Department;
use SalaryReports\Domain\Entities\Employee;
use SalaryReports\Domain\Entities\PayrollItem;
use SalaryReports\Domain\PayrollData;
use SalaryReports\Domain\Repositories\PayrollRepositoryInterface;

class IlluminateDatabasePayrollRepository implements PayrollRepositoryInterface
{
    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function getPayrollData(SearchParams $searchParams): PayrollData
    {
        $query = $this->db->table('employees AS e')
            ->join('departments AS d', 'e.department_id', '=', 'd.id')
            ->select('e.id AS employee_id', 'e.*', 'd.*', 'd.id AS department_id', 'd.name AS department_name');
        if ($searchParams->shouldSearch()) {
            // @TODO think about fulltext search? but it does not work well with join :(
            $query->where(function($query) use ($searchParams) {
                $query->orWhere('e.first_name', 'LIKE', '%' . $searchParams->getSearch() . '%')
                    ->orWhere('e.last_name', 'LIKE', '%' . $searchParams->getSearch() . '%')
                    ->orWhere('d.name', 'LIKE', '%' . $searchParams->getSearch() . '%');
            });
        }

        $payrollData = new PayrollData();
        foreach ($query->get() as $row) {
            $payrollData->addItem(new PayrollItem(
                new Employee(
                    $row->employee_id,
                    $row->first_name,
                    $row->last_name,
                    Carbon::create($row->joined_at),
                    (float) $row->base_salary
                ),
                new Department(
                    $row->department_id,
                    $row->department_name,
                    $row->allowance_type,
                    (float) $row->allowance_value
                )
            ));
        }

        return $payrollData;
    }
}
