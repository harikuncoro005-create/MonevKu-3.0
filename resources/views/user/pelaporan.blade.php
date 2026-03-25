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

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Perangkat Daerah
        </div>
        <div class="col-lg-8">
            <input type="hidden" id="instansi" class="form-control" param="pd" value="{{ $instansi->instansi_kode }}" readonly>
            <input type="text" class="form-control" value="{{ $instansi->instansi_nama }}" readonly>
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Tahun
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control" param="tahun" value="{{ $tahun }}" readonly>
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Jenis Pelaporan
        </div>
        <div class="col-lg-8">
            <select id="jenis" class="form-control search" param="jenis">
                <option value="" hidden></option>
                @foreach ($laporan_jenis as $index => $item)
                    <option value="{{ $index }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="view-input"></div>
    {{-- <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Bulan
        </div>
        <div class="col-lg-8">
            <select id="bulan" class="form-control search" param="bulan">
                <option value="" hidden></option>
                @foreach ($bulan as $index => $item)
                    <option value="{{ $index }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="mt-3 d-flex justify-content-end">
        <a href="#" class="btn btn-blue-500 btn-search disabled">Tampilkan</a>
    </div>
    <hr>
    <div id="view-content" style="line-height: 1;">
        <div class="card-body text-center text-gray-400 small border rounded">Pilih Jenis Pelaporan</div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var parameter = {};

        $('.btn-search').off('click').on('click', function(e){
            e.preventDefault();

            $.ytLoad({
                registerAjaxHandlers: false
            });

            parameter['jenis'] = $('#jenis').val();
            parameter['pd'] = $('#instansi').val();
            parameter['bulan'] = $('#bulan').val();
            parameter['triwulan'] = $('#tw').val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('.btn-search').addClass('disabled');
                    $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                    $.ytLoad('start');
                },
                success:function(res) {
                    console.log(res)
                    $('.btn-search').removeClass('disabled');
                    $('#view-content').html(res.html)
                    $.ytLoad('complete')
                }
            })
        })
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#jenis").select2({ 
            width: '100%',
            placeholder: 'Pilih Jenis Pelaporan',
        }).on('change', function (e) {
            console.log($(this).val())
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "/input-pelaporan-bulan",
                method:"POST",
                data: {
                    jenis: $(this).val()
                },
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('#view-input').html('<div class="card-body border mt-3"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                },
                success:function(res) {
                    setTimeout(function() {
                        $('#view-input').html(res.html)
                    }, 500)
                }
            })
        });

        $("#bulan").select2({ 
            width: '100%',
            placeholder: 'Pilih Bulan',
        })

        function checkValidation(jenis) {
            if (jenis == 2) {
                $(".btn-search").removeClass("disabled");
            } else {
                $(".btn-search").addClass("disabled");
            }
        }

        $('#jenis').on('change', function() {
            checkValidation($(this).val());
            $("#view-content").html('<div class="card-body text-center text-gray-400 small border rounded">Pilih Jenis Pelaporan</div>');
        });
    });
</script>

{{-- <div>
    <a href="/export-laporan-apbd-bulanan" class="btn-export" title="MONEVKU - Laporan APBD Bulan" url="{{ $url_export_laporan_apbd_bulanan }}">Export</a>
</div> --}}

@endsection