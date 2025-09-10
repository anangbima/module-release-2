<?php

namespace Modules\ModuleRelease2\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GenericExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $data;
    protected array $headings;
    protected ?\Closure $mapper;

    public function __construct(Collection $data, array $headings, ?\Closure $mapper = null)
    {
        $this->data = $data;
        $this->headings = $headings;
        $this->mapper = $mapper;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        return $this->mapper
            ? call_user_func($this->mapper, $row)
            : array_values((array) $row); // fallback
    }
}