@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 5;
@endphp

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

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/panel/anggaran-kas" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
        {{-- <div class="">
            <div class="input-group">
                <input type="text" id="text-search" class="form-control" value="{{ Request::get('q') }}" placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-blue-500 btn-search" param="q">Cari</button>
                </div>
            </div>
        </div> --}}
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Target / Realisasi
        </div>
        <div class="col-lg-8">
            <select id="jenis" class="form-control search" param="jenis">
                <option value="0" selected>Target</option>
                <option value="1">Realisasi</option>
            </select>
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
            Perangkat Daerah
        </div>
        <div class="col-lg-8">
            <select id="instansi" class="form-control search" param="pd">
                <option value="" hidden></option>
                @foreach ($instansi as $item)
                    <option value="{{ $item->instansi_kode }}">{{ $item->instansi_nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="input-subkegiatan"></div>
    <hr>
    <div id="view-content" style="line-height: 1;">
        <div class="card-body text-center text-gray-400 small border rounded">Pilih Perangkat Daerah dan Sub Kegiatan</div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#jenis").select2({ 
            width: '100%',
            placeholder: 'Pilih Target / Realisasi',
        })

        $("#instansi").select2({ 
            width: '100%',
            placeholder: 'Pilih Instansi',
        }).on('change', function (e) {
            var id = $(this).val();
            console.log(id)
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_form }}",
                method:"POST",
                data: {id: id, to: 'input'},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('#input-subkegiatan').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                },
                success:function(res) {
                    console.log(res)
                    $('#input-subkegiatan').html(res.html)
                }
            })
        });
    });
</script>



{{-- <script type="text/javascript">
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
                $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                $.ytLoad('start')
            },
            success:function(res) {
                console.log(res)
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
        })

    });
</script> --}}

@endsection