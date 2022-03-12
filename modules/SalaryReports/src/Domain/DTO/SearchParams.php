<?php

declare(strict_types=1);

namespace SalaryReports\Domain\DTO;

class SearchParams
{
    private ?string $search = null;
    private ?string $sortBy = null;
    private string $sortDirection = 'ASC';

    public function __construct(?string $search = null, ?string $sortBy = null, string $sortDirection = 'ASC')
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }
}
