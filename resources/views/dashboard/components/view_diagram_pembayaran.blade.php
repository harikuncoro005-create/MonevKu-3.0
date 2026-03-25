@if ($status)
    <div id="chartContainer" style="height: 400px; width: 100%;"></div>

    <script>
        $(document).ready(function(){
            
            var data = @json($diagram);
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: @json($title),
                    fontSize: 20,
                },
                axisX:{
                   maximum: 125,
                },
                axisY: {
                    includeZero: true
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    //indexLabel: "{y}", //Shows y value on all Data Points
                    indexLabelFontColor: "#5A5757",
                    indexLabelFontSize: 16,
                    // indexLabelPlacement: "outside",
                    dataPoints: data
                    
                }]
            });
            chart.render();

        });
    </script>
@else
    <div class="text-center text-small text-gray-400">Belum Ada Data</div>
@endif



