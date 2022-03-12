<?php

declare(strict_types=1);

namespace SalaryReports\Domain;

use SalaryReports\Domain\Entities\SalaryReportItem;

class SalaryReport
{
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
}
