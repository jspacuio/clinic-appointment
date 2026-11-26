<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $doctors = Doctor::where('is_active', true)
            ->orderBy('full_name')
            ->get();

        $categories = Doctor::where('is_active', true)
            ->select('specialization', DB::raw('COUNT(*) as doctors_count'))
            ->groupBy('specialization')
            ->orderBy('specialization')
            ->get();

        return view('welcome', [
            'doctors' => $doctors,
            'categories' => $categories,
            'activeDoctorsCount' => $doctors->count(),
            'patientsCount' => Patient::count(),
        ]);
    }

    public function doctors(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $category = $request->string('category')->toString() ?: 'all';

        $allCategories = Doctor::where('is_active', true)
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        $query = Doctor::where('is_active', true);

        if ($category !== 'all') {
            $query->where('specialization', $category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        return view('public.doctors', [
            'doctors' => $query->orderBy('full_name')->get(),
            'categories' => $allCategories,
            'selectedCategory' => $category,
            'search' => $search,
        ]);
    }

    public function services(): View
    {
        $categories = Doctor::where('is_active', true)
            ->select('specialization', DB::raw('COUNT(*) as doctors_count'), DB::raw('MIN(consultation_duration) as min_duration'))
            ->groupBy('specialization')
            ->orderBy('specialization')
            ->get();

        return view('public.services', [
            'categories' => $categories,
        ]);
    }
}
