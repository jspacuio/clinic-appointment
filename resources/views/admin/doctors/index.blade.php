@extends('layouts.admin')

@section('title', 'Doctors')
@section('topbar-title', 'Doctors')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Doctors</h2>
            <p>Manage practitioners, specialties, and availability.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" onclick="openModal('add-doctor-modal')">
                <x-clinic-icon name="plus" size="17" /><span>Add Doctor</span>
            </button>
        </div>
    </div>

    <div class="doctor-toolbar">
        <form method="GET" action="{{ route('admin.doctors.index') }}" class="search">
            <x-clinic-icon name="search" size="18" />
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search doctors or specialty...">
        </form>
        <div class="segmented">
            <a href="{{ route('admin.doctors.index', ['status' => 'all', 'search' => $search]) }}" class="{{ $status === 'all' ? 'active' : '' }}">All doctors</a>
            <a href="{{ route('admin.doctors.index', ['status' => 'active', 'search' => $search]) }}" class="{{ $status === 'active' ? 'active' : '' }}">Active</a>
            <a href="{{ route('admin.doctors.index', ['status' => 'inactive', 'search' => $search]) }}" class="{{ $status === 'inactive' ? 'active' : '' }}">Inactive</a>
        </div>
    </div>

    <div class="doctor-grid">
        @forelse ($doctors as $doc)
            <section class="card doctor-card">
                <div class="doctor-top">
                    <x-clinic-avatar :name="$doc->full_name" :tone="$doc->avatar_tone" size="lg" />
                    <div class="toggle-line">
                        <span>{{ $doc->is_active ? 'Active' : 'Inactive' }}</span>
                        <form method="POST" action="{{ route('admin.doctors.toggle', $doc) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="toggle {{ $doc->is_active ? 'on' : '' }}" aria-label="Toggle {{ $doc->full_name }} status">
                                <i></i>
                            </button>
                        </form>
                    </div>
                </div>
                <h3>{{ $doc->full_name }}</h3>
                <span class="specialty">{{ $doc->specialization }}</span>
                <div class="availability">
                    <x-clinic-icon name="clock" />
                    <div>
                        <small>Availability</small>
                        <strong>{{ $doc->availability_summary ?: 'Mon–Fri · 8:00 AM–4:00 PM' }}</strong>
                    </div>
                </div>
                <div class="doctor-metrics">
                    <span><strong>{{ $doc->total_appointments_count }}</strong>appointments</span>
                    <span><strong>{{ $doc->today_appointments_count }}</strong>today</span>
                </div>
                <div class="button-row">
                    <button
                        type="button"
                        class="btn secondary"
                        onclick="openEditDoctorModal({{ Js::from([
                            'action' => route('admin.doctors.update', $doc),
                            'full_name' => $doc->full_name,
                            'specialization' => $doc->specialization,
                            'license_number' => $doc->license_number,
                            'phone' => $doc->phone,
                            'email' => $doc->email,
                            'consultation_duration' => $doc->consultation_duration,
                            'availability_summary' => $doc->availability_summary,
                        ]) }})"
                    >
                        <x-clinic-icon name="edit" size="16" /><span>Edit</span>
                    </button>
                    <a href="{{ route('admin.schedules.index', ['doctor_id' => $doc->id]) }}" class="btn ghost">View schedule</a>
                </div>
            </section>
        @empty
            <section class="card" style="grid-column:1/-1; padding:32px; text-align:center; color:var(--muted);">
                No doctors match your search criteria.
            </section>
        @endforelse
    </div>

    {{-- Add Doctor Modal --}}
    <div id="add-doctor-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Add doctor</h2>
                    <p>Create a practitioner profile and Doctor Portal login.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('add-doctor-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.doctors.store') }}">
                @csrf
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="full_name" placeholder="Dr. First Last" required>
                        </label>
                        <label class="field">
                            <span>Specialization *</span>
                            <input type="text" name="specialization" list="doctor-specializations-list" placeholder="e.g. Eye Care, General Medicine" required>
                        </label>
                        <label class="field">
                            <span>License number *</span>
                            <input type="text" name="license_number" placeholder="MD-000000" required>
                        </label>
                        <label class="field">
                            <span>Phone</span>
                            <input type="text" name="phone" placeholder="(555) 000-0000">
                        </label>
                        <label class="field">
                            <span>Email (Doctor Portal login) *</span>
                            <input type="email" name="email" placeholder="doctor@cliniccare.com" required>
                        </label>
                        <label class="field">
                            <span>Consultation duration (minutes) *</span>
                            <input type="number" name="consultation_duration" value="30" min="10" max="120" required>
                        </label>
                        <label class="field">
                            <span>Availability summary</span>
                            <input type="text" name="availability_summary" value="Mon–Fri · 8:00 AM–4:00 PM">
                        </label>
                        <label class="field">
                            <span>Temporary password</span>
                            <input type="password" name="password" value="ClinicCare2025">
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('add-doctor-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Save doctor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Doctor Modal --}}
    <div id="edit-doctor-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Edit doctor</h2>
                    <p>Update practitioner details and consultation length.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('edit-doctor-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" id="edit-doctor-form" action="">
                @csrf
                @method('PUT')
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="full_name" id="edit-doc-name" required>
                        </label>
                        <label class="field">
                            <span>Specialization *</span>
                            <input type="text" name="specialization" id="edit-doc-spec" list="doctor-specializations-list" required>
                        </label>
                        <label class="field">
                            <span>License number *</span>
                            <input type="text" name="license_number" id="edit-doc-license" required>
                        </label>
                        <label class="field">
                            <span>Phone</span>
                            <input type="text" name="phone" id="edit-doc-phone">
                        </label>
                        <label class="field">
                            <span>Email *</span>
                            <input type="email" name="email" id="edit-doc-email" required>
                        </label>
                        <label class="field">
                            <span>Consultation duration (minutes) *</span>
                            <input type="number" name="consultation_duration" id="edit-doc-duration" min="10" max="120" required>
                        </label>
                        <label class="field full">
                            <span>Availability summary</span>
                            <input type="text" name="availability_summary" id="edit-doc-avail">
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('edit-doctor-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Update doctor</button>
                </div>
            </form>
        </div>
    </div>

    <datalist id="doctor-specializations-list">
        @foreach (\App\Models\Doctor::SPECIALIZATIONS as $spec)
            <option value="{{ $spec }}">
        @endforeach
    </datalist>
@endsection

@push('scripts')
<script>
    function openEditDoctorModal(doc) {
        document.getElementById('edit-doctor-form').action = doc.action;
        document.getElementById('edit-doc-name').value = doc.full_name || '';
        document.getElementById('edit-doc-spec').value = doc.specialization || 'General Medicine';
        document.getElementById('edit-doc-license').value = doc.license_number || '';
        document.getElementById('edit-doc-phone').value = doc.phone || '';
        document.getElementById('edit-doc-email').value = doc.email || '';
        document.getElementById('edit-doc-duration').value = doc.consultation_duration || 30;
        document.getElementById('edit-doc-avail').value = doc.availability_summary || '';
        openModal('edit-doctor-modal');
    }
</script>
@endpush
