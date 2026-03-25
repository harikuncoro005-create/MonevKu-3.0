@extends('layout.layout_admin')

@section('main_content')

<style type="text/css">
    .select2-container .select2-selection {
        height: 2.5rem;
        display: flex;
        align-items: center;
    }

    .select2-selection__rendered {
         margin: 0.25rem;
    }

    .select2-selection__arrow {
        margin: 0.30rem;
    }
</style>

@canany(['admin', 'administrator'])

<div class="card border-0 shadow-sm rounded-lg overflow-hidden my-3" style="background: #ffffff; border-left: 4px solid #4e73df !important;">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="gap: 1rem;">
            <div class="d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" 
                     style="width: 45px; height: 45px; color: #4e73df;">
                    <i class="fas fa-building fa-lg"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 0.65rem;">
                        Perangkat Daerah
                    </small>
                    <span class="text-dark font-weight-bold" style="font-size: 1.1rem;">
                        {{ session()->has('session_instansi') ? $instansi->instansi_nama : 'Silakan Pilih Perangkat Daerah Untuk Ditampilkan' }}
                    </span>
                </div>
            </div>

            <div>
                <a href="javascript:void(0)" 
                   to="create-instansi" 
                   title="Pilih Perangkat Daerah" 
                   class="btn btn-add d-flex align-items-center justify-content-center" 
                   style="
                        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
                        color: white;
                        border: none;
                        border-radius: 8px;
                        padding: 0.6rem 1.5rem;
                        font-weight: 600;
                        font-size: 0.85rem;
                        min-width: 120px;
                        transition: all 0.3s ease;
                   " 
                   url="{{ $url_form }}">
                    <i class="fas fa-sync-alt mr-2" style="font-size: 0.8rem;"></i>
                    {{ session()->has('session_instansi') ? 'Ganti Instansi' : 'Pilih Instansi' }}
                </a>
            </div>
        </div>
    </div>
</div>



{{-- <div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="card-body alert-warning rounded">
        <div class="d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
            <div>{{ session()->has('session_instansi') ? $instansi->instansi_nama : 'Silakan Pilih Perangkat Daerah Untuk Ditampilkan' }}</div>
            <div cl>
                <a href="" to="create-instansi" title="Pilih Perangkat Daerah" class="btn btn-blue-500 btn-add" style="width: 7.5rem" url="{{ $url_form }}">{{ session()->has('session_instansi') ? 'Ganti' : 'Pilih' }}</a>
            </div>
        </div>
    </div>
</div> --}}

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

<script type="text/javascript">
    $('.btn-add').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('url'),
            method:"POST",
            data: {
                title: $(this).attr('title'),
                to: $(this).attr('to'),
            },
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#form-content').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div>')
            },
            success:function(res) {
                console.log(res)
                setTimeout(function() {
                    $('#form-content').html(res.html)
                }, 500)
            }
        })
    })
</script> 
@endcanany


<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="width:10rem;">
            <select id="bulan" class="form-control search" param="bulan">
                @foreach ($bulan as $index => $item)
                    <option value="{{ $index }}" {{ $bulan_index == $index ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <hr>
    <div id="view-diagram"></div>
    <br>
    <div id="view-content"></div>
</div>


{{-- <script type="text/javascript">
    $(document).ready(function(){
        if (@json(session()->has('session_password'))) {
            Swal.fire({
                html: '<small>Anda Belum Menggunakan Strong Password, Silahkan Ganti Password</small>',
                title: '<div><h4 class="text-danger">PERHATIAN</h4></div>',
                imageWidth: 400,
                imageHeight: 180,
                allowOutsideClick: false
            })
        }
    })
</script> --}}

<script type="text/javascript">
    $(document).ready(function(){
        $.ytLoad({
            registerAjaxHandlers: false
        });

        var url = new URL($(location).attr('href'))

        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})


        $("#bulan").select2({ 
            width: '100%',
            placeholder: 'Pilih Instansi',
            // dropdownParent: $('#modal-form')
        }).on('change', function (e) {
            $.ytLoad({
                registerAjaxHandlers: false
            });

            var url = new URL($(location).attr('href'));
            var param = $(this).attr('param')
            var search_params = url.searchParams;
            search_params.delete('page');
            delete parameter['page']

            if (search_params.has(param)) {
                if ($(this).val() == '') {
                    search_params.delete(param);
                    delete parameter[param]
                } else {
                    search_params.set(param, $(this).val());
                    parameter[param] = $(this).val()
                }
            } else {
                search_params.set(param, $(this).val());
                parameter[param] = $(this).val()
            }
            
            var new_url = url.toString();
            history.pushState({}, null, new_url);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method:"POST",
                data: {
                    parameter: parameter
                },
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('#view-content').html('<div class="card-body border rounded bg-white d-flex flex-column align-items-center justify-content-center p-5"><div class="modern-spinner mb-3"></div><div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 1px;"> MEMUAT DATA...</div></div>')
                    $.ytLoad('start')
                },
                success:function(res) {
                    setTimeout(function() {
                        $('#view-content').html(res.html)
                        $('#view-diagram').html(res.diagram)
                    }, 500)
                    $.ytLoad('complete')
                }
            })
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $.ytLoad({
            registerAjaxHandlers: false
        });

        var url = new URL($(location).attr('href'))

        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<div class="card-body border rounded bg-white d-flex flex-column align-items-center justify-content-center p-5"><div class="modern-spinner mb-3"></div><div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 1px;"> MEMUAT DATA...</div></div>')
                $.ytLoad('start')
            },
            success:function(res) {
                console.log(res)
                $('#view-content').html(res.html)
                $('#view-diagram').html(res.diagram)
                $.ytLoad('complete')
            }
        })

    });
</script>

<script type="text/javascript">
    $(window).on('popstate', function() {
        var url = new URL($(location).attr('href'));
        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        if(typeof parameter.q !== 'undefined' ) {
            $('#text-search').val(url.searchParams.get('q'))
        } else {
            $('#text-search').val('')
        }

        $.each( parameter, function( key, value ) {
            $('select[param="'+key+'"]').val(value)
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $('#view-diagram').html(res.diagram)
                $.ytLoad('complete')
            }
        })
    });
</script>

{{-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


@endsection