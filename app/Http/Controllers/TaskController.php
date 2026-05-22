<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Account;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TasksExport;
use App\Imports\TasksImport;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Task::with('user');

        if (!$user->isSuperadmin()) {
            if ($user->isManager()) {
                $subordinateIds = $user->subordinates()->pluck('id')->toArray();
                $query->whereIn('user_id', array_merge([$user->id], $subordinateIds));
            } else {
                $query->where('user_id', $user->id);
            }
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:LOW,MEDIUM,HIGH',
            'status' => 'required|in:TODO,IN_PROGRESS,DONE,OVERDUE',
        ]);

        $validated['user_id'] = auth()->id();
        
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:LOW,MEDIUM,HIGH',
            'status' => 'required|in:TODO,IN_PROGRESS,DONE,OVERDUE',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function toggleStatus(Task $task)
    {
        $task->update([
            'status' => $task->status === 'DONE' ? 'TODO' : 'DONE'
        ]);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function export()
    {
        return Excel::download(new TasksExport, 'tasks_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new TasksImport, $request->file('file'));
        return redirect()->route('tasks.index')->with('success', 'Tasks imported successfully.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array {
                return ['Judul Tugas', 'Deskripsi', 'Deadline (YYYY-MM-DD)', 'Prioritas (LOW/MEDIUM/HIGH)', 'Status (TODO/DONE)'];
            }
        }, 'template_tasks.xlsx');
    }
}
