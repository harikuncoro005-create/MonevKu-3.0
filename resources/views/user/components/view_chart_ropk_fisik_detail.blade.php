<canvas id="diagram-fisik" class="p-4" style="height: 370px; width: 100%;">DIAGRAM</canvas>

<script>
    $(document).ready(function(){
        const ctx = document.getElementById('diagram-fisik');
        const labels = @json($bulan);
        const data = {
            labels: labels,
            datasets: [
                {
                label: 'KEUANGAN',
                data: @json($keuangan_target),
                fill: false,
                borderColor: 'rgb(239, 68, 68)',
                tension: 0.1
                },
                {
                label: 'FISIK',
                data: @json($fisik_target),
                fill: false,
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
                },
            ]
        };
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'ROPK FISIK',
                        font: {
                            size: 20,
                            family: 'Helvetica Neue, Helvetica, Arial, sans-serif',
                            weight: 'bold'
                        },
                    }
                }
            }
        });
    });
</script>
{{-- <script>
$(document).ready(function(){

    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title:{
            text: "ROPK FISIK"
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
                { y: {{ $keuangan_komulatif[1] }}, label: "JANUARI" },
                { y: {{ $keuangan_komulatif[2] }}, label: "FEBRUARI" },
                { y: {{ $keuangan_komulatif[3] }}, label: "MARET" },
                { y: {{ $keuangan_komulatif[4] }}, label: "APRIL" },
                { y: {{ $keuangan_komulatif[5] }}, label: "MEI" },
                { y: {{ $keuangan_komulatif[6] }}, label: "JUNI" },
                { y: {{ $keuangan_komulatif[7] }}, label: "JULI" },
                { y: {{ $keuangan_komulatif[8] }}, label: "AGUSTUS" },
                { y: {{ $keuangan_komulatif[9] }}, label: "SEPTEMBER" },
                { y: {{ $keuangan_komulatif[10] }}, label: "OKTOBER" },
                { y: {{ $keuangan_komulatif[11] }}, label: "NOVEMBER" },
                { y: {{ $keuangan_komulatif[12] }}, label: "DESEMBER" }
            ]
        },
        {
            name: "ROPK FISIK",
            type: "spline",
            yValueFormatString: "#0.##",
            showInLegend: true,
            dataPoints: [
                { y: {{ $fisik_komulatif[1] }}, label: "JANUARI" },
                { y: {{ $fisik_komulatif[2] }}, label: "FEBRUARI" },
                { y: {{ $fisik_komulatif[3] }}, label: "MARET" },
                { y: {{ $fisik_komulatif[4] }}, label: "APRIL" },
                { y: {{ $fisik_komulatif[5] }}, label: "MEI" },
                { y: {{ $fisik_komulatif[6] }}, label: "JUNI" },
                { y: {{ $fisik_komulatif[7] }}, label: "JULI" },
                { y: {{ $fisik_komulatif[8] }}, label: "AGUSTUS" },
                { y: {{ $fisik_komulatif[9] }}, label: "SEPTEMBER" },
                { y: {{ $fisik_komulatif[10] }}, label: "OKTOBER" },
                { y: {{ $fisik_komulatif[11] }}, label: "NOVEMBER" },
                { y: {{ $fisik_komulatif[12] }}, label: "DESEMBER" }
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

});
</script> --}}
