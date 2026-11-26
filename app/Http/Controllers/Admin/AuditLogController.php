<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $userId = $request->integer('user_id');
        $action = $request->string('action')->toString();
        $date = $request->string('date')->toString();

        $query = AuditLog::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('actor_name', 'like', "%{$search}%")
                    ->orWhere('entity', 'like', "%{$search}%")
                    ->orWhere('change_summary', 'like', "%{$search}%");
            });
        }

        if ($userId > 0) {
            $query->where('user_id', $userId);
        }

        if ($action !== '' && $action !== 'all') {
            $query->where('action', $action);
        }

        if ($date !== '') {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name']);
        $actions = AuditLog::distinct()->orderBy('action')->pluck('action');

        return view('admin.audit.index', [
            'logs' => $logs,
            'users' => $users,
            'actions' => $actions,
            'search' => $search,
            'userId' => $userId,
            'selectedAction' => $action,
            'date' => $date,
        ]);
    }
}
