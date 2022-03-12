<?php

namespace SalaryReports\Domain;

use SalaryReports\Domain\Entities\PayrollItem;

class PayrollData
{
    /** @var PayrollItem[] */
    private array $items = [];

    public function addItem(PayrollItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
