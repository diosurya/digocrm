<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Task([
            'title'       => $row['judul'],
            'description' => $row['deskripsi'],
            'priority'    => strtolower($row['priority'] ?? 'medium'),
            'status'      => strtolower($row['status'] ?? 'pending'),
            'due_date'    => $row['due_date'] ? \Carbon\Carbon::parse($row['due_date']) : null,
        ]);
    }
}
