<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** Globale takenlijst (alle auto's). */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'open'); // open, done, all, overdue, today

        $q = Task::query()->with('occasion');

        switch ($filter) {
            case 'done':
                $q->whereNotNull('completed_at');
                break;
            case 'all':
                break;
            case 'overdue':
                $q->whereNull('completed_at')->whereNotNull('due_at')->where('due_at', '<', now());
                break;
            case 'today':
                $q->whereNull('completed_at')->whereDate('due_at', today());
                break;
            case 'open':
            default:
                $q->whereNull('completed_at');
        }

        $q->orderByRaw('CASE WHEN completed_at IS NULL THEN 0 ELSE 1 END')
          ->orderByRaw('CASE WHEN due_at IS NULL THEN 1 ELSE 0 END')
          ->orderBy('due_at')
          ->orderByDesc('priority');

        $tasks = $q->paginate(50)->withQueryString();

        $counts = [
            'open'    => Task::whereNull('completed_at')->count(),
            'today'   => Task::whereNull('completed_at')->whereDate('due_at', today())->count(),
            'overdue' => Task::whereNull('completed_at')->whereNotNull('due_at')->where('due_at', '<', now())->count(),
            'done'    => Task::whereNotNull('completed_at')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'filter', 'counts'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['user_id'] = $request->user()?->id;

        Task::create($data);

        return back()->with('success', 'Taak aangemaakt.');
    }

    public function update(Request $request, Task $task)
    {
        $data = $this->validateData($request);
        $task->update($data);
        return back()->with('success', 'Taak bijgewerkt.');
    }

    public function toggle(Task $task)
    {
        $task->update(['completed_at' => $task->completed_at ? null : now()]);
        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Taak verwijderd.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'body'        => ['nullable', 'string', 'max:5000'],
            'priority'    => ['nullable', 'in:low,normal,high'],
            'due_at'      => ['nullable', 'date'],
            'occasion_id' => ['nullable', 'exists:occasions,id'],
        ]);
    }
}
