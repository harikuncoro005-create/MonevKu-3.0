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

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="width:10rem;">
            <select id="bulan" class="form-control search" param="bulan">
                @foreach ($bulan as $index => $item)
                    <option value="{{ $index }}" {{ $bulan_index == $index ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
        {{-- <div style="width:20rem;">
            <select class="form-control search" param="id">
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
            <select class="form-control search" param="tahun">
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
        </div> --}}
    </div>
    <hr>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <div id="view-content"></div>
</div>

<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "REALISASI KEUANGAN TAHUN 2025"
	},
	axisX: {
		valueFormatString: "M"
	},
	// axisY: {
	// 	title: "Temperature (in °C)",
	// 	suffix: " °C"
	// },
	legend:{
		cursor: "pointer",
		fontSize: 16,
		itemclick: toggleDataSeries
	},
	toolTip:{
		shared: true
	},
	data: [{
		name: "ANGGARAN KAS",
		type: "spline",
		yValueFormatString: "#0.##",
		showInLegend: true,
		dataPoints: [
			{ y: 2050000, label: "JANUARI" },
            { y: 4050000, label: "FEBRUARI" },
            { y: 12050000, label: "MARET" },
            { y: 16050000, label: "APRIL" },
            { y: 26050000, label: "MEI" },
            { y: 68050000, label: "JUNI" },
            { y: 98050000, label: "JULI" },
            { y: 120050000, label: "AGUSTUS" },
            { y: 137500000, label: "SEPTEMBER" },
            { y: 157500000, label: "OKTOBER" },
            { y: 157500000, label: "NOVEMBER" },
            { y: 157500000, label: "DESEMBER" }
		]
	},
	{
		name: "REALISASI",
		type: "spline",
		yValueFormatString: "#0.##",
		showInLegend: true,
		dataPoints: [
			{ y: 1800000, label: "JANUARI" },
            { y: 3050000, label: "FEBRUARI" },
            { y: 9050000, label: "MARET" },
            { y: 14050000, label: "APRIL" },
            { y: 24050000, label: "MEI" },
            { y: 62050000, label: "JUNI" },
            { y: 91050000, label: "JULI" },
            { y: 96005000, label: "AGUSTUS" },
            { y: 97500000, label: "SEPTEMBER" },
            { y: 107500000, label: "OKTOBER" },
            // { y: 107500000, label: "NOVEMBER" },
            // { y: 107500000, label: "DESEMBER" }
		]
	}]
	// {
	// 	name: "Nantucket",
	// 	type: "spline",
	// 	yValueFormatString: "#0.## °C",
	// 	showInLegend: true,
	// 	dataPoints: [
	// 		{ x: new Date(2017,6,24), y: 22 },
	// 		{ x: new Date(2017,6,25), y: 19 },
	// 		{ x: new Date(2017,6,26), y: 23 },
	// 		{ x: new Date(2017,6,27), y: 24 },
	// 		{ x: new Date(2017,6,28), y: 24 },
	// 		{ x: new Date(2017,6,29), y: 23 },
	// 		{ x: new Date(2017,6,30), y: 23 }
	// 	]
	// }]
});
chart.render();

function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}

}
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
            url: "{{ $url }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading')
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
        })

        $('.search').on('change', function(){
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
                url: "{{ $url }}",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $.ytLoad('start');
                },
                success:function(res) {
                    $('#view-content').html(res.html)
                    $.ytLoad('complete')  
                }
            })
        })

    });
</script> --}}

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>


@endsection