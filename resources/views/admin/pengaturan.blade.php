@extends('layout.layout_admin')

@section('main_content')

<div class="row">
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="/pengaturan/admin" class="nav-link nav-side text-secondary"> Admin</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
                        <i class="fa-solid fa-users text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	@can('administrator')
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="/pengaturan/admin-sesi" class="nav-link nav-side text-secondary"> Sesi</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
                        <i class="fa-regular fa-clock text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	@endcan
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="/pengaturan/nomenklatur-perencanaan" class="nav-link nav-side text-secondary"> Nomenklatur Perencanaan</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
                        <i class="fa-regular fa-file-lines text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	{{-- <div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="" class="nav-link nav-side text-secondary"> Usulan Pelatihan</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-envelopes-bulk text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="" class="nav-link nav-side text-secondary"> Rumpun Pelatihan</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-layer-group text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="" class="nav-link nav-side text-secondary"> Kompetensi Pegawai</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-chart-line text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="" class="nav-link nav-side text-secondary"> Laporan Pengembangan Kompetensi</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-file-pen text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="" class="nav-link nav-side text-secondary"> Kegiatan Pelatihan</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-school text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div> --}}
</div>

@endsection