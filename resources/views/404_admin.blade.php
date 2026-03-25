@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="text-center text-gray-500">
        <div class="mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#fbc02d" viewBox="0 0 16 16" style="display: inline-block;">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>
        <div class="text-gray-400 mb-3" style="font-size: 1.1rem; font-weight: 500;">
            {{ $message }}
        </div>
    </div>
    <hr>
    <div class="text-center">
        <a href="{{ $url }}" class="btn btn-outline-secondary shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-top: -2px;">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

@endsection