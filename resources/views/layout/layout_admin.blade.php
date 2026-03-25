@extends('layout.layout_header')

@section('admin_content')

<style>
/* .brand-wrapper {
    position: static !important; 
    display: block;
    z-index: 1030;
} */

/* Pastikan saat di mobile, brand tetap terlihat rapi */
@media (max-width: 768px) {
    .sidebar-brand {
        height: auto;
    }
}

/* Sembunyikan tombol secara default di mobile */
#sidebarClose {
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease-in-out;
    transform: scale(0.5) rotate(-90deg); /* Animasi awal */
    z-index: 1050;
}

/* Tampilkan tombol HANYA saat sidebar terbuka (toggled) */


/* #sidebarToggle {
    z-index: 9999 !important; 
    pointer-events: auto !important; 
    position: relative;
} */

/* body.sb-sidenav-toggled #sidebarToggle {
    position: relative;
    z-index: 9999 !important;
    color: #dc3545 !important;
} */

/* Pastikan di desktop tombol tetap benar-benar hilang */
@media (min-width: 768px) {
    #sidebarClose {
        display: none !important;
    }
}

@media (max-width: 767.98px) {
    /* Saat sidebar terbuka, sembunyikan tombol hamburger di navbar */
    body.sb-sidenav-toggled #sidebarToggle {
        opacity: 0;
        visibility: hidden;
        pointer-events: none; /* Memastikan tidak bisa diklik meski bayangannya ada */
        transition: all 0.2s ease; /* Animasi menghilang yang halus */
    }

    body.sb-sidenav-toggled #sidebarClose {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1) rotate(0deg); /* Animasi masuk */
    }
}


</style>

<script>
    $(document).ready(function() {
        $('#sidebarClose').on('click', function() {
            $('body').toggleClass('sb-sidenav-toggled');
        });
    });
</script>

{{-- <style>
.modern-sweep-card {
    /* Gradasi Biru Deep Modern */
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Animasi Light Sweep */
.light-sweep {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(
        to right, 
        rgba(255, 255, 255, 0) 0%, 
        rgba(255, 255, 255, 0.2) 50%, 
        rgba(255, 255, 255, 0) 100%
    );
    transform: skewX(-25deg);
    transition: none;
    z-index: 1;
    animation: sweep 4s infinite;
}

@keyframes sweep {
    0% { left: -110%; }
    30% { left: 150%; }
    100% { left: 150%; }
}

/* Indikator Aktif (Dot Hijau) */
.status-indicator {
    width: 8px;
    height: 8px;
    background-color: #2ecc71;
    border-radius: 50%;
    box-shadow: 0 0 8px #2ecc71;
    display: {{ session('session_kode') ? 'block' : 'none' }};
}

.modern-sweep-card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}
</style> --}}

<style>
    /* Sidebar Styling */
.modern-nav-link {
    display: flex;
    align-items: center;
    padding: 0.4rem 1.2rem !important;
    margin-bottom: 0.25rem;
    border-radius: 6px !important;
    color: #5c677d !important;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.modern-nav-link:hover {
    background-color: #f8f9fa;
    color: #2565c5 !important;
    transform: translateX(5px);
}

.modern-nav-link.active {
    background-color: #2565c5 !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(37, 101, 197, 0.3);
}

.modern-nav-link.active .sb-nav-link-icon {
    color: #fff !important;
}

.sb-nav-link-icon {
    width: 25px;
    font-size: 1.1rem;
    color: #adb5bd;
    transition: color 0.3s;
}

/* Sub-menu styling */
.sub-link {
    font-size: 0.8rem !important;
    padding: 0.5rem 1rem !important;
    color: #6c757d !important;
    position: relative;
}

.active-sub {
    color: #2565c5 !important;
    font-weight: bold;
}

.border-left {
    border-left: 2px solid #e9ecef !important;
}

/* Brand & Footer */
.bg-primary-soft { background-color: rgba(37, 101, 197, 0.05); }
.rounded-lg { border-radius: 15px !important; }
.logout-hover:hover { background-color: #fff5f5 !important; }

/* Responsive Scrollbar */
.custom-scroll::-webkit-scrollbar { width: 4px; }
.custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<style>
    /* Container Background */
#layoutSidenav_content {
    background-color: #f8f9fc;
}

/* Avatar Styling */
.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #2565c5, #4e73df);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    transition: all 0.3s ease;
}

.user-dropdown-link:hover .avatar-circle {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(37, 101, 197, 0.2);
}

.initials {
    font-size: 1rem;
    letter-spacing: 1px;
}

/* Dropdown Animation */
.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom Dropdown Item */
.dropdown-item {
    font-size: 0.85rem;
    color: #5a5c69;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fc;
    color: #2565c5;
    padding-left: 1.5rem; /* Efek geser sedikit saat hover */
}

/* Responsiveness */
@media (max-width: 576px) {
    .navbar {
        margin: 10px !important;
        padding: 0.5rem 1rem !important;
    }
}
</style>

<style>
    .session-capsule {
        position: relative;
        background: rgba(255, 255, 255, 0.08); /* Efek kaca transparan */
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 8px 12px;
    }

    .content-wrapper {
        position: relative;
        z-index: 2;
    }

    .icon-box {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.8rem;
        background: rgba(255, 255, 255, 0.1);
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .text-group {
        display: flex;
        flex-direction: column;
        line-height: 1;
    }

    .label-tiny {
        font-size: 0.55rem;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.5);
        letter-spacing: 0.8px;
        margin-bottom: 2px;
    }

    .session-name {
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    /* Indikator Status */
    .pulse-dot {
        width: 6px;
        height: 6px;
        background-color: #00f2fe; /* Warna Cyan Modern */
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 242, 254, 0.6);
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.3); }
        100% { opacity: 0.6; transform: scale(1); }
    }

    /* Kilau halus sekali lewat */
    .sweep-blur {
        position: absolute;
        top: 0; left: -100%;
        width: 40%; height: 100%;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.05), transparent);
        transform: skewX(-30deg);
        animation: subtle-sweep 6s infinite;
    }

    @keyframes subtle-sweep {
        0% { left: -100%; }
        30% { left: 150%; }
        100% { left: 150%; }
    }
</style>


<div id="layoutSidenav">

    <button class="btn text-warning d-md-none" id="sidebarClose" 
        style="
            position: fixed; 
            right: 22px; 
            top: 15px; 
            z-index: 1060; 
            padding: 5px; 
            line-height: 1;
            background: rgba(0,0,0,0.05); /* Sedikit background agar terlihat di latar terang */
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        ">
        <i class="fa-solid fa-times fa-lg"></i>
    </button>

    <div id="layoutSidenav_nav" style="z-index: 1040;">
        <nav class="sb-sidenav accordion sb-sidenav-light border-right shadow-sm" id="sidenavAccordion" style="background-color: #fff; transition: all 0.3s; padding-top: 0">

            <div class="sb-sidenav-menu scroll">
        
                <div class="brand-wrapper p-3 w-100 text-center sticky-top" style="background-color: #f8f9fa; border-bottom: 1px solid #e3e6f0; min-height: 60px;">
                    <img class="img-fluid" src="{{ url('assets/img/monevku_logo.png') }}" alt="Logo" style="max-height: 40px;">
                </div>

                <div class="nav mx-3">
                    <div class="sb-sidenav-menu-heading text-muted small font-weight-bold uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">UTAMA</div>
                    
                    <a class="nav-link modern-nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="{{ url('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-th-large"></i></div>
                        <span>Dashboard</span>
                    </a>

                    @if (session()->has('session_instansi'))
                        {{-- <div class="sb-sidenav-menu-heading text-muted small font-weight-bold mt-3 mb-2 uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">PERENCANAAN</div> --}}

                        <a class="nav-link modern-nav-link {{ Request::segment(1) == 'sesi' ? 'active' : '' }}" href="{{ url('/sesi') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                            <span>Sesi Aktif</span>
                        </a>

                        <a class="nav-link modern-nav-link disabled" href="javascript:void(0)" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-signature"></i></div>
                            <span>Renstra</span>
                        </a>

                        <a class="nav-link modern-nav-link {{ Request::segment(1) == 'renja' ? 'active' : '' }}" href="{{ url('/renja') }}">
                            <div class="sb-nav-link-icon"><i class="fa-regular fa-file-lines"></i></div>
                            <span>Renja</span>
                        </a>

                        <a class="nav-link modern-nav-link {{ in_array(Request::segment(1), ['rencana-keluaran', 'ropk-fisik', 'ropk-keuangan']) ? 'active' : 'collapsed' }}" 
                           href="#" data-toggle="collapse" data-target="#collapseRencana" aria-expanded="false">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span>Rencana</span>
                            <div class="sb-sidenav-collapse-arrow ml-auto"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ in_array(Request::segment(1), ['rencana-keluaran', 'ropk-fisik', 'ropk-keuangan']) ? 'show' : '' }}" id="collapseRencana" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav ml-4 border-left">
                                <a class="nav-link sub-link {{ Request::segment(1) == 'rencana-keluaran' ? 'active-sub' : '' }}" href="/rencana-keluaran">Keluaran</a>
                                <a class="nav-link sub-link {{ Request::segment(1) == 'ropk-fisik' ? 'active-sub' : '' }}" href="/ropk-fisik">ROPK Fisik</a>
                                <a class="nav-link sub-link {{ Request::segment(1) == 'ropk-keuangan' ? 'active-sub' : '' }}" href="/ropk-keuangan">Anggaran KAS</a>
                            </nav>
                        </div>

                        <a class="nav-link modern-nav-link {{ Request::segment(1) == 'monev' ? 'active' : '' }}" href="{{ url('/monev') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            <span>Monev</span>
                        </a>

                        <a class="nav-link modern-nav-link {{ Request::segment(1) == 'pelaporan' ? 'active' : '' }}" href="{{ url('/pelaporan') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                            <span>Laporan</span>
                        </a>

                    @endif

                    @canany(['admin', 'administrator'])
                    <div class="sb-sidenav-menu-heading text-muted small font-weight-bold mt-3 mb-2 uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">Admin</div>
                    
                    <a class="nav-link modern-nav-link {{ Request::segment(1) == 'panel' ? 'active' : '' }}" href="{{ url('/panel') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                        <span>Admin Panel</span>
                    </a>
                    @endcanany

                    <div class="mt-4 pt-3 border-top">
                        <a class="nav-link modern-nav-link text-danger logout-hover" href="{{ url('/sign-out') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-power-off"></i></div>
                            <span>Keluar</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- <div class="sb-sidenav-footer mx-3 mb-4 rounded text-white shadow-sm position-relative overflow-hidden modern-sweep-card">
                <div class="light-sweep"></div>
                
                <div class="position-relative" style="z-index: 2;">
                    <div class="small opacity-75 d-flex align-items-center">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i> Sesi Aktif
                    </div>
                    <div class="font-weight-bold mt-1 d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            {{ session('session_kode')->sesi_nama ?? 'Tidak Aktif' }}
                        </span>
                        <div class="status-indicator"></div>
                    </div>
                </div>
            </div> --}}
            <div class="session-capsule mx-2 mb-3 shadow-sm overflow-hidden bg-primary">
                <div class="sweep-blur"></div>
                
                <div class="content-wrapper">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div class="text-group ml-2">
                                <span class="label-tiny">SESI</span>
                                <span class="session-name">
                                    {{ session('session_kode')->sesi_nama ?? 'OFF' }}
                                </span>
                            </div>
                        </div>
                        <div class="pulse-dot"></div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content" style="background-color: #f8f9fa;">
        <main>
            <nav class="navbar navbar-expand navbar-light bg-white sticky-top shadow-sm px-3 py-2 mb-4 d-flex flex-row justify-content-between" style="border-bottom: 1px solid #e3e6f0;">
                    
                <button class="btn btn-link btn-sm text-primary p-2" id="sidebarToggle" type="button" style="z-index: 1050; position: relative;">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="dropdown">
                    <button class="btn d-flex align-items-center border-0 px-2 rounded-pill hover-bg-light" 
                            type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
                            style="transition: all 0.3s ease;">
                        
                        <div class="text-right mr-2 d-none d-sm-block">
                            <p class="mb-0 small font-weight-bold text-dark" style="line-height: 1;">{{ auth()->user()->admin_nama }}</p>
                        </div>
                        
                        <div class="avatar-wrapper">
                            <i class="fas fa-user-circle fa-lg text-primary"></i>
                        </div>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-3 animate-slide-in" aria-labelledby="userDropdown" style="border-radius: 12px; min-width: 200px;">
                        <div class="dropdown-header text-dark font-weight-bold">Pengaturan Akun</div>
                        <a class="dropdown-item py-2" href="{{ url('/profile') }}">
                            <i class="fas fa-user-edit mr-2 text-primary"></i> Profile Saya
                        </a>
                        <a class="dropdown-item py-2" href="{{ url('/pengaturan') }}">
                            <i class="fas fa-cog mr-2 text-primary"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger" href="{{ url('/sign-out') }}">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </a>
                    </div>
                    
                    <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2 animate-slide-in" aria-labelledby="userDropdown" style="border-radius: 12px; min-width: 180px;">
                        <a href="{{ url('/profile') }}" class="dropdown-item py-2">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/sign-out') }}" class="dropdown-item py-2 text-danger">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container-desktop-only px-md-4">
                @yield('main_content')
            </div>
        </main>
    </div>
</div>





@endsection