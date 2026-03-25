@extends('layout.layout_admin')

@section('main_content')

<style>
    .modern-card {
        height: 7rem;
        border-radius: 12px;
        padding: 1.25rem;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        align-items: center;
    }

    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }

    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        z-index: 2;
    }

    .menu-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: #4a5568;
        letter-spacing: 0.5px;
        line-height: 1.3;
        max-width: 65%;
        text-transform: uppercase;
        transition: color 0.3s ease;
    }

    .icon-wrapper {
        font-size: 3rem;
        opacity: 0.15;
        transition: all 0.4s ease;
    }

    .modern-card:hover .icon-wrapper {
        opacity: 0.4;
        transform: scale(1.1) rotate(-10deg);
    }

    /* Variasi Warna Gradasi Soft */
    .card-blue { border-bottom: 4px solid #4e73df; }
    .card-blue:hover { background: linear-gradient(135deg, #ffffff 60%, #e0e7ff 100%); }
    .card-blue .icon-wrapper { color: #4e73df; }

    .card-green { border-bottom: 4px solid #1cc88a; }
    .card-green:hover { background: linear-gradient(135deg, #ffffff 60%, #d1fae5 100%); }
    .card-green .icon-wrapper { color: #1cc88a; }

    .card-purple { border-bottom: 4px solid #6f42c1; }
    .card-purple:hover { background: linear-gradient(135deg, #ffffff 60%, #f3e8ff 100%); }
    .card-purple .icon-wrapper { color: #6f42c1; }

    .card-red { border-bottom: 4px solid #e74a3b; }
    .card-red:hover { background: linear-gradient(135deg, #ffffff 60%, #fee2e2 100%); }
    .card-red .icon-wrapper { color: #e74a3b; }

    .card-orange { border-bottom: 4px solid #f6c23e; }
    .card-orange:hover { background: linear-gradient(135deg, #ffffff 60%, #fef3c7 100%); }
    .card-orange .icon-wrapper { color: #f6c23e; }

	.card-cyan { border-bottom: 4px solid oklch(71.5% 0.143 215.221); }
    .card-cyan:hover { background: linear-gradient(135deg, #ffffff 60%, oklch(98.4% 0.019 200.873) 100%); }
    .card-cyan .icon-wrapper { color: oklch(71.5% 0.143 215.221); }

	.card-indigo { border-bottom: 4px solid oklch(67.3% 0.182 276.935); }
    .card-indigo:hover { background: linear-gradient(135deg, #ffffff 60%, oklch(96.2% 0.018 272.314) 100%); }
    .card-indigo .icon-wrapper { color: oklch(67.3% 0.182 276.935); }

	.card-teal { border-bottom: 4px solid oklch(76.5% 0.177 163.223); }
    .card-teal:hover { background: linear-gradient(135deg, #ffffff 60%, oklch(98.4% 0.014 180.72) 100%); }
    .card-teal .icon-wrapper { color: oklch(76.5% 0.177 163.223); }

	.card-dark { border-bottom: 4px solid oklch(70.4% 0.04 256.788); }
    .card-dark:hover { background: linear-gradient(135deg, #ffffff 60%, oklch(98.4% 0.003 247.858) 100%); }
    .card-dark .icon-wrapper { color: oklch(70.4% 0.04 256.788); }
</style>

{{-- <div class="row">
	<div class="col-lg-3">
		<div class="bg-white rounded px-4 py-3 my-2 shadow-sm" style="height:6rem">
		    <div class="d-flex flex-sm-row justify-content-between h-100" style="row-gap:0.5rem">
		        <div class="d-flex flex-sm-row align-items-center w-75" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div style="line-height: 1;">
		                <a href="/panel/keluaran" class="nav-link nav-side text-secondary"> KELUARAN</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/anggaran-kas" class="nav-link nav-side text-secondary"> KEUANGAN</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/fisik" class="nav-link nav-side text-secondary"> FISIK</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/dokumen-keluaran" class="nav-link nav-side text-secondary"> BUKTI DUKUNG KELUARAN</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/dokumen-fisik" class="nav-link nav-side text-secondary"> BUKTI DUKUNG FISIK</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/permasalahan" class="nav-link nav-side text-secondary"> PERMASALAHAN DAN TINDAK LANJUT</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-database text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/pelaporan" class="nav-link nav-side text-secondary"> PELAPORAN</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
		                <i class="fa-solid fa-file-lines text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
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
		                <a href="/panel/penilaian" class="nav-link nav-side text-secondary"> PENILAIAN</a>
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
		                <a href="/panel/permission" class="nav-link nav-side text-secondary"> PERMISSION</a>
		            </div>
		        </div>
		        <div class="d-flex flex-column flex-sm-row align-items-center w-25" style="column-gap:0.5rem; row-gap: 0.5rem;">
		            <div>
						<i class="fa-solid fa-user-lock text-secondary" style="font-size: 3.5rem; opacity: 0.2;"></i>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div> --}}

<div class="row">
    @php
        $menus = [
            ['title' => 'KELUARAN', 'url' => '/panel/keluaran', 'icon' => 'fa-database', 'color' => 'blue'],
            ['title' => 'KEUANGAN', 'url' => '/panel/anggaran-kas', 'icon' => 'fa-coins', 'color' => 'green'],
            ['title' => 'FISIK', 'url' => '/panel/fisik', 'icon' => 'fa-laptop-file', 'color' => 'purple'],
            ['title' => 'BUKTI DUKUNG KELUARAN', 'url' => '/panel/dokumen-keluaran', 'icon' => 'fa-file-shield', 'color' => 'orange'],
            ['title' => 'BUKTI DUKUNG FISIK', 'url' => '/panel/dokumen-fisik', 'icon' => 'fa-file-signature', 'color' => 'cyan'],
            ['title' => 'PERMASALAHAN & TINDAK LANJUT', 'url' => '/panel/permasalahan', 'icon' => 'fa-circle-exclamation', 'color' => 'red'],
            ['title' => 'PELAPORAN', 'url' => '/panel/pelaporan', 'icon' => 'fa-file-lines', 'color' => 'indigo'],
            ['title' => 'PENILAIAN', 'url' => '/panel/penilaian', 'icon' => 'fa-file-pen', 'color' => 'teal'],
            ['title' => 'PERMISSION', 'url' => '/panel/permission', 'icon' => 'fa-user-lock', 'color' => 'dark'],
        ];
    @endphp

    @foreach($menus as $menu)
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ $menu['url'] }}" class="text-decoration-none">
            <div class="modern-card card-{{ $menu['color'] }}">
                <div class="card-content">
                    <span class="menu-title">{{ $menu['title'] }}</span>
                    <div class="icon-wrapper">
                        <i class="fa-solid {{ $menu['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

@endsection