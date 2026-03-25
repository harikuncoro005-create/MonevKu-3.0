@extends('layout.layout_header')

@section('admin_content')

<style>
    /* Sidebar Styling */
.modern-nav-link {
    display: flex;
    align-items: center;
    padding: 0.8rem 1.2rem !important;
    margin-bottom: 0.25rem;
    border-radius: 12px !important;
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

{{-- <div id="layoutSidenav">
    <div id="layoutSidenav_nav" style="z-index: 1040;">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="padding-top: 0;">
            <div class="sb-sidenav-menu scroll" style="background-color: #fff; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);">
                <div style="padding: 1rem; background-color: #fff;">
                    <div class="d-flex px-2 py-1 text-primary font-weight-bold" style="font-size: 1.1rem">
                        ADMIN
                        <img class="w-100 rounded" src={{ url('assets/img/monevku_logo.png') }}>
                    </div>
                </div>
                <div class="nav mx-2 d-flex flex-column">
                    <a class="nav-link nav-side px-3 py-2 rounded {{ Request::segment(1) == 'dashboard' ? 'bg-nav-active' : '' }}" href={{ url('dashboard') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
                            <span style="font-size:0.9rem">DASHBOARD</span>
                        </div>
                    </a>
                    @if (session()->has('session_instansi'))
                        
                    
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'sesi' ? 'bg-nav-active' : ''}}" href={{ url('/sesi') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span style="font-size:0.9rem">SESI</span>
                        </div>
                    </a>
                    <a class="nav-link disabled nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'renstra' ? 'bg-nav-active' : ''}}" href={{ url('/renstra') }}>
                        <div class="d-flex flex-row align-items-center text-gray-400" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                            </svg>

                            <span style="font-size:0.9rem">RENSTRA</span>
                        </div>
                    </a>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'renja' ? 'bg-nav-active' : ''}}" href={{ url('/renja') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                            </svg>

                            <span style="font-size:0.9rem">RENJA</span>
                        </div>
                    </a>
                    <a class="nav-link collapsed nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'ropk-fisik' || Request::segment(1) == 'ropk-keuangan'|| Request::segment(1) == 'ropk-belanja' || Request::segment(1) == 'rencana-keluaran' ? 'bg-nav-active' : ''}}" href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="false" aria-controls="collapse_1">
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>

                            <span style="font-size:0.9rem">RENCANA</span>
                        </div>
                        <div class="sb-sidenav-collapse-arrow text-secondary">
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div class="collapse" id="collapse_1" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'rencana-keluaran' ? 'bg-nav-active' : ''}}" href="/rencana-keluaran" style="font-size:0.9rem">Rencana Keluaran</a>
                            <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'ropk-fisik' ? 'bg-nav-active' : ''}}" href="/ropk-fisik" style="font-size:0.9rem">ROPK Fisik</a>
                            <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'ropk-keuangan' ? 'bg-nav-active' : ''}}" href="/ropk-keuangan" style="font-size:0.9rem">Anggaran KAS</a>
                        </nav>
                    </div>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'monev' ? 'bg-nav-active' : ''}}" href={{ url('/monev') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span style="font-size:0.9rem">MONEV</span>
                        </div>
                    </a>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'pelaporan' ? 'bg-nav-active' : ''}}" href={{ url('/pelaporan') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" />
                            </svg>
                            <span style="font-size:0.9rem">LAPORAN</span>
                        </div>
                    </a>

                    @endif

                    @can('admin')
                    <div><hr class="bg-light"></div>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(2) == 'penilaian-pelaporan' ? 'bg-nav-active' : ''}}" href={{ url('/panel/penilaian-pelaporan') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                            </svg>
                            <span style="font-size:0.9rem">Penilaian Pelaporan</span>
                        </div>
                    </a>
                    @endcan

                    @can('administrator')
                    <div><hr class="bg-light"></div>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'panel' ? 'bg-nav-active' : ''}}" href={{ url('/panel') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            <span style="font-size:0.9rem">Admin Panel</span>
                        </div>
                    </a>
                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded {{ Request::segment(1) == 'pengaturan' ? 'bg-nav-active' : ''}}" href={{ url('/pengaturan') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span style="font-size:0.9rem">Pengaturan</span>
                        </div>
                    </a>
                    @endcan

                    <a class="nav-link nav-side mt-1 px-3 py-2 rounded" href={{ url('/sign-out') }}>
                        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; line-height:1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span style="font-size:0.9rem">Log Out</span>
                        </div>
                    </a>

                </div>
            </div>
            
            <div class="sb-sidenav-footer text-white" style="background-color: #2565c5;">
                <div style="line-height: 1">
                    <div class="small">Sesi Aktif:</div>
                    <div class="mt-1">{{ session('session_kode')->sesi_nama }}</div>
                </div>
            </div>
            
        </nav>
    </div>

    <?php if ($this->session->flashdata('success')) { ?>
        <div class="position-fixed bottom-0 right-0 p-3 alert" style="z-index: 5; right: 0; bottom: 0;">
            <div class="toast border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-body alert-success px-4">
                    <?= $this->session->flashdata('success') ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($this->session->flashdata('error')) { ?>
        <div class="position-fixed bottom-0 right-0 p-3 alert" style="z-index: 5; right: 0; bottom: 0;">
            <div class="toast border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-body alert-danger px-4">
                    <?= $this->session->flashdata('error') ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".alert").fadeTo(4000, 0, function () {
                $(".alert").alert('close');
            });
        });
    </script>

    <div id="layoutSidenav_content">
        <main>
            <div class="m-3">
            <nav class="p-2 shadow-sm rounded mb-3 d-flex flex-row justify-content-between" style="background-color: #2565c5;">
                <button class="btn text-gray-200 order-2 order-sm-1" id="sidebarToggle" href="#!" style="box-shadow: none; z-index: 1050;"><i class="fas fa-bars"></i></button>
                <div class="order-1 order-sm-2">
                    <button type="button" class="btn px-3 w-100 text-gray-200 rounded-pill text-left h-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="appearance: none; line-height:1rem">
                        {{ auth()->user()->admin_nama }} <i class="fas fa-user-circle ml-2"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3">
                        <a href="{{ url('/profile') }}" class="dropdown-item text-blue-500">Profile</a>
                        <a href="{{ url('/sign-out') }}" class="dropdown-item text-blue-500">Log Out</a>
                    </div>
                </div>
            </nav>

            @yield('main_content')

            </div>
        </main>
    </div>
</div> --}}

{{-- <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light border-right" id="sidenavAccordion" style="background-color: #fff;">
            <div class="sb-sidenav-menu custom-scroll">
                <div class="sidebar-brand d-flex align-items-center justify-content-center p-4">
                    <div class="brand-box bg-primary-soft rounded-lg px-3 py-2">
                        <span class="text-primary font-weight-bold" style="letter-spacing: 1px;">MONEV<span class="text-dark">KU</span></span>
                    </div>
                </div>

                <div class="nav mx-3">
                    <div class="sb-sidenav-menu-heading text-muted small font-weight-bold uppercase mb-2" style="font-size: 0.7rem; opacity: 0.6;">MENU UTAMA</div>
                    
                    <a class="nav-link side-item mb-1 {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="{{ url('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-th-large"></i></div>
                        <span>DASHBOARD</span>
                    </a>

                    @if (session()->has('session_instansi'))
                        <a class="nav-link side-item mb-1 {{ Request::segment(1) == 'sesi' ? 'active' : '' }}" href="{{ url('/sesi') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                            <span>SESI</span>
                        </a>

                        <a class="nav-link side-item mb-1 {{ Request::segment(1) == 'renja' ? 'active' : '' }}" href="{{ url('/renja') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                            <span>RENJA</span>
                        </a>

                        <a class="nav-link side-item mb-1 {{ in_array(Request::segment(1), ['ropk-fisik', 'ropk-keuangan', 'rencana-keluaran']) ? 'active' : 'collapsed' }}" 
                           href="#" data-toggle="collapse" data-target="#collapseRencana" aria-expanded="false">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            <span>RENCANA</span>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ in_array(Request::segment(1), ['ropk-fisik', 'ropk-keuangan', 'rencana-keluaran']) ? 'show' : '' }}" id="collapseRencana" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav ml-4 border-left">
                                <a class="nav-link sub-side-item {{ Request::segment(1) == 'rencana-keluaran' ? 'text-primary font-weight-bold' : '' }}" href="/rencana-keluaran">Rencana Keluaran</a>
                                <a class="nav-link sub-side-item {{ Request::segment(1) == 'ropk-fisik' ? 'text-primary font-weight-bold' : '' }}" href="/ropk-fisik">ROPK Fisik</a>
                                <a class="nav-link sub-side-item {{ Request::segment(1) == 'ropk-keuangan' ? 'text-primary font-weight-bold' : '' }}" href="/ropk-keuangan">Anggaran KAS</a>
                            </nav>
                        </div>
                    @endif

                    @canany(['admin', 'administrator'])
                        <div class="sb-sidenav-menu-heading text-muted small font-weight-bold uppercase mt-4 mb-2" style="font-size: 0.7rem; opacity: 0.6;">ADMINISTRASI</div>
                        
                        @can('admin')
                        <a class="nav-link side-item mb-1 {{ Request::segment(2) == 'penilaian-pelaporan' ? 'active' : '' }}" href="{{ url('/panel/penilaian-pelaporan') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
                            <span>Penilaian</span>
                        </a>
                        @endcan

                        @can('administrator')
                        <a class="nav-link side-item mb-1 {{ Request::segment(1) == 'panel' ? 'active' : '' }}" href="{{ url('/panel') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                            <span>Admin Panel</span>
                        </a>
                        @endcan
                    @endcanany

                    <div class="mt-4">
                        <a class="nav-link side-item text-danger logout-item" href="{{ url('/sign-out') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt text-danger"></i></div>
                            <span>LOGOUT</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sb-sidenav-footer border-top bg-light">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; font-size: 0.7rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="small text-muted" style="font-size: 0.65rem;">SESI AKTIF</div>
                        <div class="text-dark font-weight-bold truncate" style="font-size: 0.75rem;">{{ session('session_kode')->sesi_nama }}</div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="m-3">
            <nav class="p-2 shadow-sm rounded mb-3 d-flex flex-row justify-content-between" style="background-color: #2565c5;">
                <button class="btn text-gray-200 order-2 order-sm-1" id="sidebarToggle" href="#!" style="box-shadow: none; z-index: 1050;"><i class="fas fa-bars"></i></button>
                <div class="order-1 order-sm-2">
                    <button type="button" class="btn px-3 w-100 text-gray-200 rounded-pill text-left h-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="appearance: none; line-height:1rem">
                        {{ auth()->user()->admin_nama }} <i class="fas fa-user-circle ml-2"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3">
                        <a href="{{ url('/profile') }}" class="dropdown-item text-blue-500">Profile</a>
                        <a href="{{ url('/sign-out') }}" class="dropdown-item text-blue-500">Log Out</a>
                    </div>
                </div>
            </nav>

            @yield('main_content')

            </div>
        </main>
    </div>
</div> --}}

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light border-right shadow-sm" id="sidenavAccordion" style="background-color: #fff; transition: all 0.3s;">
            <div class="sb-sidenav-menu custom-scroll">
                
                <div class="sidebar-brand d-flex align-items-center justify-content-center py-4 px-3">
                    <div class="brand-wrapper p-2 rounded-lg bg-primary-soft w-100 text-center">
                        <img class="img-fluid" src="{{ url('assets/img/monevku_logo.png') }}" alt="Logo" style="max-height: 40px;">
                        <div class="mt-2 small font-weight-bold text-primary">ADMIN PANEL</div>
                    </div>
                </div>

                {{-- <div class="nav mx-3">
                    <div class="sb-sidenav-menu-heading text-muted small font-weight-bold mb-2 uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">UTAMA</div>
                    
                    <a class="nav-link modern-nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="{{ url('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-th-large"></i></div>
                        <span>Dashboard</span>
                    </a>

                    @if (session()->has('session_instansi'))
                        <div class="sb-sidenav-menu-heading text-muted small font-weight-bold mt-3 mb-2 uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">PERENCANAAN</div>

                        <a class="nav-link modern-nav-link {{ Request::segment(1) == 'sesi' ? 'active' : '' }}" href="{{ url('/sesi') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                            <span>Sesi Aktif</span>
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
                    @endif

                    @canany(['admin', 'administrator'])
                    <div class="sb-sidenav-menu-heading text-muted small font-weight-bold mt-3 mb-2 uppercase" style="font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">KONTROL</div>
                    
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
                </div> --}}
            </div>

            <div class="sb-sidenav-footer mx-3 mb-3 p-3 rounded-lg text-white" style="background: linear-gradient(45deg, #1a4da1, #2565c5);">
                <div class="small opacity-75">Tahun Anggaran:</div>
                <div class="font-weight-bold mt-1" style="font-size: 0.85rem;">
                    {{ session('session_kode')->sesi_nama ?? 'Tidak Aktif' }}
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content" style="background-color: #f4f7fa;"> <main>
        <div class="container-fluid px-4"> <nav class="navbar navbar-expand navbar-light bg-white shadow-sm rounded-xl mb-4 mt-3 py-2 px-3 border-0 d-flex justify-content-between align-items-center" 
                 style="border-radius: 15px; transition: all 0.3s ease;">
                
                <div class="d-flex align-items-center">
                    <button class="btn btn-link btn-sm text-primary mr-3" id="sidebarToggle" href="#!" style="font-size: 1.2rem;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-none d-md-block">
                        <span class="text-muted small">Selamat Datang,</span>
                        <h6 class="mb-0 font-weight-bold text-dark">Monev System</h6>
                    </div>
                </div>

                <div class="navbar-nav align-items-center">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 d-flex align-items-center user-dropdown-link" 
                           id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            <div class="mr-3 text-right d-none d-sm-block">
                                <p class="mb-0 text-dark font-weight-bold" style="line-height: 1.2; font-size: 0.85rem;">
                                    {{ auth()->user()->admin_nama }}
                                </p>
                                <small class="text-muted" style="font-size: 0.75rem;">Administrator</small>
                            </div>

                            <div class="avatar-circle shadow-sm">
                                <span class="initials">{{ substr(auth()->user()->admin_nama, 0, 1) }}</span>
                            </div>
                        </a>
                        
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
                    </div>
                </div>
            </nav>

            <div class="content-wrapper">
                @yield('main_content')
            </div>

        </div>
    </main>
</div>


@endsection