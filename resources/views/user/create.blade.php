@extends('layouts.master')

@section('title') SIMS | Create User @endsection

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

<form method="POST" action="{{ route('user.store') }}">

    @csrf
            <div class="box-header flex justify-between">
                                    <div class="box-title">
                                        Create User
                                    </div>
                                    <div class="prism-toggle">
                                        <button class="ti-btn !py-1 !px-2 ti-btn-primary !text-[0.75rem] !font-medium">Show Code<i class="ri-code-line ms-2 d-inline-block align-middle"></i></button>
                                    </div>
                                </div>
    <div class="grid grid-cols-12 sm:gap-6 mt-8">
        
        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label for="name" class="form-label !font-normal">Full Name:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter full name" required>
        </div>

        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label for="email" class="form-label !mb-2">Email:</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email" required>
        </div>

        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label for="student_no" class="form-label">Student ID No:</label>
            <input type="text" name="student_no" class="form-control placeholder:text-textmuted" id="student_no" value="{{ old('student_no') }}" placeholder="Enter Student ID">
        </div>

        <div class="xl:col-span-4 lg:col-span-6 md:col-span-6 sm:col-span-12 col-span-12">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control placeholder:text-textmuted" id="password" placeholder="Enter password" required>
        </div>

        

        <div class="col-span-12 flex gap-3 mt-4">
            <button type="submit" class="ti-btn !text-white !bg-primary">Create User</button>
           
        </div>

    </div>
</form>
@endsection
