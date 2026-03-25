@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
        </svg>
        <span>PROFIL</span>
    </div>
    <hr>
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Nama
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control bg-white" value="{{ $result->admin_nama }}" readonly>
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Username
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control bg-white" value="{{ $result->username }}" readonly>
        </div>
    </div>
    <hr>
    <div class="mt-3 d-flex justify-content-start">
        <a href="#" class="btn btn-sm btn-blue-500 btn-password"><i class="fa-solid fa-lock fa-sm"></i> Ganti Password</a>
    </div>
</div>


@endsection