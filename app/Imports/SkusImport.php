<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkusImport implements ToCollection, WithHeadingRow
{
    public $rows;

    public function collection(Collection $collection)
    {
        $this->rows = $collection;
    }
}