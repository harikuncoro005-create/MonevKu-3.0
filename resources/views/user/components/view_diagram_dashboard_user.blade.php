<div class="row">
    <div class="col-lg-6">
        <canvas id="diagram-keuangan" class="p-4" style="height: 370px; width: 100%;">DIAGRAM KEUANGAN</canvas>
    </div>
    <div class="col-lg-6">
        <canvas id="diagram-fisik" class="p-4" style="height: 370px; width: 100%;">DIAGRAM FISIK</canvas>
    </div>
</div>

<script>
    $(document).ready(function(){
        const ctx = document.getElementById('diagram-keuangan');
        const labels = @json($bulan);
        const data = {
            labels: labels,
            datasets: [
                {
                label: 'TARGET',
                data: @json($keuangan_target),
                fill: false,
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
                },
                {
                label: 'REALISASI',
                data: @json($keuangan_realisasi),
                fill: false,
                borderColor: 'rgb(239, 68, 68)',
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
                        text: @json($keuangan_title),
                        font: {
                            size: 20,
                            family: 'Helvetica Neue, Helvetica, Arial, sans-serif',
                            weight: 'bold'
                        },
                    }
                }
            }
        });


        const ctx2 = document.getElementById('diagram-fisik');
        const data2 = {
            labels: labels,
            datasets: [
                {
                label: 'TARGET',
                data: @json($fisik_target),
                fill: false,
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
                },
                {
                label: 'REALISASI',
                data: @json($fisik_realisasi),
                fill: false,
                borderColor: 'rgb(239, 68, 68)',
                tension: 0.1
                },
            ]
        };
        new Chart(ctx2, {
            type: 'line',
            data: data2,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: @json($fisik_title),
                        font: {
                            size: 20,
                            family: 'Helvetica Neue, Helvetica, Arial, sans-serif',
                            weight: 'bold'
                        },
                    }
                },
                scales: {
                    y: {
                        min: 0,   // Mulai dari 0
                        max: 100, // Maksimal 100
                    }
                }
            }
        });
    });
</script>

{{-- <script>
    $(document).ready(function(){
        var keuangan_target = @json($keuangan_target);
        var keuangan_realisasi = @json($keuangan_realisasi);
        var chart = new CanvasJS.Chart("diagram-keuangan", {
            animationEnabled: true,
            theme: "light1",
            title:{
                text: @json($keuangan_title),
                fontSize: 20,
            },
            axisX: {
                valueFormatString: "M"
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip:{
                shared: true
            },
            data: [{
                name: "TARGET",
                type: "spline",
                yValueFormatString: "#,##0.##",
                showInLegend: true,
                dataPoints: keuangan_target
            },
            {
                name: "REALISASI",
                type: "spline",
                yValueFormatString: "#,##0.##",
                showInLegend: true,
                dataPoints: keuangan_realisasi
            }]
        
        });
        chart.render();

        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    });
</script>

<script>
    $(document).ready(function(){
        
        var fisik_target = @json($fisik_target);
        var fisik_realisasi = @json($fisik_realisasi);
        var chart1 = new CanvasJS.Chart("diagram-fisik", {
            animationEnabled: true,
            theme: "light1",
            title:{
                text: @json($fisik_title),
                fontSize: 20,
            },
            axisX: {
                valueFormatString: "M"
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries1
            },
            toolTip:{
                shared: true
            },
            data: [{
                name: "TARGET",
                type: "spline",
                yValueFormatString: "#0.##",
                showInLegend: true,
                dataPoints: fisik_target
            },
            {
                name: "REALISASI",
                type: "spline",
                yValueFormatString: "#0.##",
                showInLegend: true,
                dataPoints: fisik_realisasi
            }]
        });
        chart1.render();

        function toggleDataSeries1(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else{
                e.dataSeries.visible = true;
            }
            chart1.render();
        }

    });
</script> --}}
