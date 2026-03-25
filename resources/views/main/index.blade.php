@extends('layout.layout_main')

@section('admin_content')

<style>
	.swiper {
		width: 100%;
		height: 100%;
	}

	.swiper-slide {
		width: 50%;
	}

	.img-slide {
		aspect-ratio: 4 / 5;
		}

	@media only screen and (max-width: 1024px) {
		.swiper-slide {
			width: 100%;
		}

	}

	.swiper-button-next:hover {
		background-color:white;
		opacity: 0.5;
	}

	.swiper-button-prev:hover {
		background-color:white;
		opacity: 0.5;
	}

	@media only screen and (max-width: 640px) {
		.swiper-button-next, .swiper-button-prev {
			display: none;
		}
		.img-slide {
		aspect-ratio: 16 / 9;
		}
	}
</style>

<div class="bg-dark" style="height: 100vh"><img class="img-fluid position-absolute end-0" src="{{ URL::asset('assets/plugins/boldo/assets/img/hero/hero-bg.png') }}" alt="" />
	<section class="d-flex align-items-center" style="min-height: 100vh;">
		{{-- <div class="container mt-5">
			<div class="row">
				<div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-2 pt-lg-0 order-2 order-lg-1 text-center">
					<div>
						<h1 class="text-white fs-5 fs-xl-6 text-lg-start">Pembangunan Talud</h1>
						<p class="text-white py-lg-3 py-2 text-lg-start">Dalam upaya mewujudkan Perum BPS yang aman dan nyaman terbebas dari banjir kami mengajak khususnya semua warga perumahan dan warga sekitar juga seluruh masyarakat untuk membantu kami mewujudkan Pembangunan Talud.</p>
						<div class="d-sm-flex align-items-center gap-3">
							<a href="/sumbangan" class="btn btn-sm btn-success text-black mb-3 w-75">Realisasi</a>	
						</div>
					</div>
				</div>
				<div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img">
					<div class="">
						<div id="slider" class="swiper mySwiper">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<a href="" class="shadow" style="background-size: cover; background-position: center;">
										<div class="w-100 d-flex align-items-center justify-items-center img-slide"
											style="background-image: url('assets/img/photo_1.jpg'); background-size: cover; background-position: center;">
											<div class="">
												<span></span>
											</div>
										</div>
									</a>
								</div>
								<div class="swiper-slide">
									<a href="" class="shadow" style="background-size: cover; background-position: center;">
										<div class="w-100 d-flex align-items-center justify-items-center img-slide"
											style="background-image: url('assets/plugins/boldo/assets/img/tes.jpg'); background-size: cover; background-position: center;">
											<div class="">
												<span></span>
											</div>
										</div>
									</a>
								</div>
								<div class="swiper-slide">
									<a href="" class="shadow" style="background-size: cover; background-position: center;">
										<div class="w-100 d-flex align-items-center justify-items-center img-slide"
											style="background-image: url('assets/plugins/boldo/assets/img/tes.jpg'); background-size: cover; background-position: center;">
											<div class="">
												<span></span>
											</div>
										</div>
									</a>
								</div>
								<div class="swiper-slide">
									<a href="" class="shadow" style="background-size: cover; background-position: center;">
										<div class="w-100 d-flex align-items-center justify-items-center img-slide"
											style="background-image: url('assets/plugins/boldo/assets/img/tes.jpg'); background-size: cover; background-position: center;">
											<div class="">
												<span></span>
											</div>
										</div>
									</a>
								</div>
								<div class="swiper-slide">
									<a href="" class="shadow" style="background-size: cover; background-position: center;">
										<div class="w-100 d-flex align-items-center justify-items-center img-slide"
											style="background-image: url('assets/plugins/boldo/assets/img/tes.jpg'); background-size: cover; background-position: center;">
											<div class="">
												<span></span>
											</div>
										</div>
									</a>
								</div>   
							</div>
						</div>      
					</div>
				</div>
			</div>
		</div> --}}
	</section>
</div>

{{-- <section>
	<div class="container">
		<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
			<div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
				<div style="width:20rem;">
					<select class="form-control form-control-sm search" param="id">
						@foreach ($iuran as $item)
							<option value="{{ $item->iuran_id }}" 
							@php
								if (Request::get('id') && Request::get('id') == $item->iuran_id) {
									echo 'selected';
								} else {
									if ($iuran_prioritas->iuran_id == $item->iuran_id) {
										echo 'selected';
									}
								}   
							@endphp                 
							
							>{{ $item->iuran_nama }}</option>
						@endforeach
					</select>
				</div>
				<div style="min-width:5rem">
					<select class="form-control form-control-sm search" param="tahun">
						@foreach ($tahun as $item)
							<option value="{{ $item }}" 
							@php
								if (Request::get('tahun') && Request::get('tahun') == $item) {
									echo 'selected';
								} else {
									if (date('Y') == $item) {
										echo 'selected';
									}
								}   
							@endphp                 
							
							>{{ $item }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<hr>
			<div id="view-content"></div>
		</div>


	</div>
</section> --}}



{{-- <section>
	<div class="container bg-dark overflow-hidden rounded-1">
		<div class="bg-holder" style="background-image:url(assets/plugins/boldo/assets/img/promo/promo-bg.png);"></div>
		<div class="px-5 py-7 position-relative">
			<h1 class="text-center w-lg-75 mx-auto fs-lg-4 fs-md-4 fs-3 text-white">Bantuan dana pembangunan melalui transfer hanya di rekening paguyuban perumahan</h1>
			<hr>
			<h1 class="text-center w-lg-75 mx-auto fs-lg-6 fs-md-4 fs-3 text-white">REKENING BANK BRI - 942-01-016251-53-4</h1>
			<div class="text-center text-white fs-3">
				a.n. PW BUMI PROGO SEJAHTERA
			</div>
		</div>
	</div>
</section>

<section class="pt-0">
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-lg-6 col-sm-12"><a href="/"><img class="img-fluid mt-5 mb-4" src="{{ URL::asset('assets/img/bps-black.png') }}" alt="" /></a>
				<h4>PAGUYUBAN PERUMAHAN BUMI PROGO SEJAHTERA</h4>
				<p class="w-lg-75 text-gray">Perumahan Bumi Progo Sejahtera RT 10/05, Karangtengah Kidul, Margosari, Pengasih Kulonprogo, DIY, Kode pos 55652.</p>
			</div>
				
			<div class="col-lg-4 col-sm-4">
				
				<h3 class="fw-bold fs-1 mt-5 mb-4">KONTAK KAMI</h3>
				<ul class="list-unstyled">
					<li class=""><a href="#">Joko Triyatno - KETUA - 081328714148</a></li>
					<li class=""><a href="#">Andi Nurcahya - BENDAHARA - 082136123222</a></li>
					<li class=""><a href="#">Y. Nanang Jarwadi - HUMAS - 085100299313</a></li>
				</ul>
					
				
			</div>
		</div>
		<p class="text-gray">All rights reserved.</p>
	</div>
</section> --}}

<script>
	var swiper = new Swiper(".mySwiper", {
		direction: 'horizontal',
		slidesPerView: "auto",
		// cssMode: true,
		spaceBetween: 20,
		centeredSlides: true,
		autoplay: {
			delay: 3000,
			disableOnInteraction: false,
		},
		loop: true,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		// pagination: {
		//     el: ".swiper-pagination",
		// },
		// scrollbar: {
		//     el: '.swiper-scrollbar',
		// },
		// mousewheel: true,
		// keyboard: true,
	});
</script>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

@endsection