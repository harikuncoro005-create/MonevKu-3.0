@extends('layout.layout_header')

@section('admin_content')

<div class="d-flex align-item-center justify-content-center border" style="height: 100vh;">
	<div class="d-flex align-item-center justify-content-center rounded border my-auto shadow m-2 bg-white" style="width: 25rem;">
		<div class="card-body py-5">
			<div class="d-flex justify-content-center mb-6">
                <img class="img-fluid" width="200" src="{{ URL::asset('assets/img/bapperida_logo.png') }}" alt="" />
            </div>
	        <br>
            <div id="message_error" class="alert-danger rounded text-center p-2 small d-none"></div>
            <form id="form-signin" class="shadow-md rounded px-8 pt-6 pb-8 mb-4 align-self-center">
                <div class="mb-2 mt-4">
                    <input class="form-control" name="username" id="username" placeholder="Username">
                    <small id="username_error"></small>
                </div>

                {{-- <div class="mb-2">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password">
                    <small id="password_error" class="text-red-500"></small>
                </div> --}}
				<div class="form-group mb-2 position-relative">
					<input class="form-control" name="password" id="password" type="password" placeholder="Password" style="padding-right: 40px;">
					
					<button type="button" id="togglePassword" class="btn position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%); z-index: 10; border: none; background: transparent; color: #6c757d;">
						<svg id="eyeShow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
						</svg>
						
						<svg id="eyeHide" class="d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
						</svg>
					</button>
				</div>
				<small id="password_error" class="text-danger d-block mt-1"></small>

				<div class="mb-2">
					<select class="form-control" name="tahun" id="tahun">
						<option class="text-center" value="2025" {{  date('Y') == '2025' ? 'selected' : '' }}>2025</option>
						<option class="text-center" value="2026" {{  date('Y') == '2026' ? 'selected' : '' }}>2026</option>
					</select>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-blue-500 btn-block rounded shadow-sm btn-login">
                        Login
                    </button>
                </div>
                
				<div class="mt-4 text-center">
                    <div class="text-secondary">Versi 0.1</div>
                    <div class="text-gray-400" style="font-size: 0.65rem"><i class="fa-regular fa-copyright"></i></div>
                </div>
            </form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.btn-login').on('click', function(e){
		e.preventDefault()
		var form = $('#form-signin')[0];
	    var formData = new FormData(form);
		$.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		    url: "/sign-in",
		    method:"POST",
		    data: formData,
		    processData:false,
		    contentType:false,
		    cache:false,
		    dataType:"json",
		    beforeSend: function() {
            	$('.btn-login').attr('disabled', true)
            },
		    success:function(res) {
	          	if(!res.status) {
			        $('.btn-login').removeAttr('disabled', true)

					if(res.message) {
					    $('#message_error').removeClass('d-none').html(res.message);
                        $('#username,#password').val('');
                        $('#username').val('').focus();
					}
					
	          	}

	          	if(res.status) {
                	location.reload()
	        	}
	        	
		    }
		})
	})
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('input').on('keyup', function() {
            $(this).removeClass('is-invalid');
            $('#message_error').addClass('d-none').html('');
        });
		$('#togglePassword').on('click', function() {
			const passwordField = $('#password');
			const eyeShow = $('#eyeShow');
			const eyeHide = $('#eyeHide');
			
			// Toggle tipe input
			const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
			passwordField.attr('type', type);
			
			// Toggle ikon menggunakan class Bootstrap 4
			eyeShow.toggleClass('d-none');
			eyeHide.toggleClass('d-none');
		});
    })
</script>

@endsection