@extends('layouts.master')

@section('title') SIMS | Edit User Status @endsection

@section('contents')
@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0 list-disc ps-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('user.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-12 sm:gap-6">
        
        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label class="form-label !font-normal">Full Name:</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label for="status" class="form-label">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-span-12 flex gap-3 mt-4">
            <button type="submit" class="ti-btn !text-white !bg-primary">Update Status</button>
            <a href="{{ route('user.index') }}" class="ti-btn ti-btn-light">Cancel</a>
        </div>

    </div>
</form>
@endsection
