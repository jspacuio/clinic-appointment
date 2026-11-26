<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $role = $request->string('role')->toString() ?: 'all';
        $status = $request->string('status')->toString() ?: 'all';

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $role = UserRole::from($validated['role']);

        $user = DB::transaction(function () use ($validated, $role): User {
            $created = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            if ($role === UserRole::Doctor) {
                Doctor::create([
                    'user_id' => $created->id,
                    'full_name' => $created->name,
                    'specialization' => $validated['specialization'] ?? 'General Medicine',
                    'license_number' => 'MD-'.random_int(100000, 999999),
                    'phone' => $created->phone,
                    'email' => $created->email,
                    'consultation_duration' => 30,
                    'availability_summary' => 'Mon–Fri · 8:00 AM–4:00 PM',
                    'avatar_tone' => 'teal',
                    'is_active' => true,
                ]);
            } elseif ($role === UserRole::Patient) {
                Patient::create([
                    'user_id' => $created->id,
                    'patient_code' => Patient::generateNextCode(),
                    'full_name' => $created->name,
                    'sex' => 'Prefer not to say',
                    'phone' => $created->phone ?: 'Not provided',
                    'email' => $created->email,
                    'avatar_tone' => 'teal',
                    'is_active' => true,
                ]);
            }

            return $created;
        });

        AuditLog::record(
            $request->user(),
            'Invited user',
            "User · {$user->name}",
            "Role assigned: {$role->label()}"
        );

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Account created for {$user->name} ({$role->label()}).");
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot deactivate your own active session account.');
        }

        $newState = ! $user->is_active;

        DB::transaction(function () use ($user, $newState): void {
            $user->update(['is_active' => $newState]);

            if ($user->doctor !== null) {
                $user->doctor->update(['is_active' => $newState]);
            }
        });

        AuditLog::record(
            $request->user(),
            'Changed user access',
            "User · {$user->name}",
            $newState ? 'Inactive → Active' : 'Active → Inactive'
        );

        return redirect()
            ->route('admin.users.index')
            ->with('status', "{$user->name} is now ".($newState ? 'Active' : 'Inactive').'.');
    }
}
