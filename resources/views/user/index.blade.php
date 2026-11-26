@extends('layouts.master')

@section('title')
SIMS | User Management
@endsection

@section('contents')
<div class="grid grid-cols-12 mt-8 gap-x-6">
    <div class="col-span-12 xxl:col-span-12 mt-4">
        <div class="box">
            <div class="box-header">
                <div class="flex justify-between">
                    <div class="box-title">User List</div>
                    <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                            class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hs-dropdown-menu ti-dropdown-menu hidden">
                            <li>
                                <a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                                    href="{{ route('users.create') }}">New User</a>
                            </li>
                            <li>
                                <a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                                    href="javascript:void(0);">Import User</a>
                            </li>
                            <li>
                                <a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                                    href="javascript:void(0);">Download User</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="text-[.75rem] mb-0 text-textmuted font-normal">Manage the users in your site</p>
            </div>

            <div class="box-body">
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-auto order-table ti-custom-table table-bordered mb-0 text-nowrap w-full !border-defaultborder dark:!border-defaultborder/10">
                        <thead class="bg-light dark:bg-black/20">
                            <tr>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Full Name</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Student ID No</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Email</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Registered Date</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Status</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Role</th>
                                <th scope="col" class="border-b !border-defaultborder dark:!border-defaultborder/10">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="me-2 lh-1">
                                                <span class="avatar avatar-sm">
                                                    <img src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="" class="rounded-1">
                                                </span>
                                            </div>
                                            <div class="fs-14">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->student_no ?? 'N/A' }}</td>
                                    <td><span class="fw-semibold">{{ $user->email }}</span></td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if ($user->status === 'active')
                                            Active <i class="fa fa-circle text-success text-[0.75rem] ms-1"></i>
                                        @else
                                            Inactive <i class="fa fa-circle text-danger text-[0.75rem] ms-1"></i>
                                        @endif
                                    </td>
                                    <td><span>Admin</span></td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a aria-label="Edit" href="{{ route('user.edit', $user->id) }}"
                                                class="ti-btn ti-btn-sm ti-btn-primary !border-primary/20 btn-wave waves-effect waves-light">
                                                <i class="ri-edit-line"></i>
                                            </a>

                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Delete"
                                                    class="ti-btn ti-btn-sm ti-btn-danger !border-danger/20 btn-wave waves-effect waves-light">
                                                    <i class="ri-delete-bin-7-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-textmuted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box-footer !pt-0 !border-t-0">
                <nav aria-label="Page navigation mb-2">
                    <ul class="ti-pagination justify-center sm:justify-end">
                        <li class="page-item disabled">
                            <a class="page-link px-3 py-[0.375rem]">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link px-3 py-[0.375rem]" href="javascript:void(0);">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link px-3 py-[0.375rem]" href="javascript:void(0);">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
