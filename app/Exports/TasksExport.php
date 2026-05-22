<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Task::all();
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Deskripsi',
            'Priority',
            'Status',
            'Due Date',
        ];
    }

    public function map($task): array
    {
        return [
            $task->title,
            $task->description,
            $task->priority,
            $task->status,
            $task->due_date?->format('Y-m-d'),
        ];
    }
}
