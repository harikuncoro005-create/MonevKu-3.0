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

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        filter: brightness(1.1);
        color: white;
    }
    
    .btn-add:active {
        transform: translateY(0);
    }

    /* .transition-all {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .card.transition-all:hover {
        transform: translateY(-4px);
        shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    } */
</style>

<div class="card border-0 shadow-sm rounded-lg overflow-hidden my-3" style="background: #ffffff; border-left: 4px solid #4e73df !important;">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="gap: 1rem;">
            <div class="d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" 
                     style="width: 45px; height: 45px; color: #4e73df;">
                    <i class="fas fa-search fa-lg"></i>
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
                    <i class="fas fa-mouse-pointer mr-2" style="font-size: 0.8rem;"></i>
                    {{ session()->has('session_instansi') ? 'Ganti Instansi' : 'Pilih Instansi' }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- <div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="card-body alert-warning rounded">
        <div class="d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
            <div>Silakan Pilih Perangkat Daerah Untuk Ditampilkan</div>
            <div cl>
                <a href="" to="create-instansi" title="Pilih Perangkat Daerah" class="btn btn-blue-500 btn-add" style="width: 7.5rem" url="{{ $url_form }}">Pilih</a>
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
                $('#form-content').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading')
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





@endsection