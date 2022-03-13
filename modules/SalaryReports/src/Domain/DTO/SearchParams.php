<?php

declare(strict_types=1);

namespace SalaryReports\Domain\DTO;

class SearchParams
{
    private ?string $search = null;
    private ?string $sortBy = null;
    private bool $sortDescending = false;

    public function setSearch(string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function setSortBy(string $columnName, ?bool $descending = false): self
    {
        $this->sortBy = $columnName;
        $this->sortDescending = $descending;

        return $this;
    }

    public function shouldSort(): bool
    {
        return is_string($this->sortBy);
    }

    public function shouldSearch(): bool
    {
        return !is_null($this->search) && strlen($this->search) > 0;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function shouldSortDescending(): bool
    {
        return $this->sortDescending;
    }
}
