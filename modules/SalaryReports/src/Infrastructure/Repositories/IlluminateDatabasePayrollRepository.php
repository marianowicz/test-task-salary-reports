<?php

declare(strict_types=1);

namespace SalaryReports\Infrastructure\Repositories;

use Illuminate\Database\DatabaseManager;
use SalaryReports\Domain\PayrollData;
use SalaryReports\Domain\Repositories\PayrollRepositoryInterface;

class IlluminateDatabasePayrollRepository implements PayrollRepositoryInterface
{
    private DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function getPayrollData(): PayrollData
    {
        // TODO: Implement getPayrollData() method.
    }
}
