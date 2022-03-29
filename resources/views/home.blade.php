@extends('layouts.app')

@section('content')
<script>
window.onload = function () {
 
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "wykres słupkowy"
        },
        axisY:{
            includeZero: true
        },
        legend:{
            cursor: "pointer",
            verticalAlign: "center",
            horizontalAlign: "right",
            itemclick: toggleDataSeries
        },
        data: [{
            type: "column",
            name: "cena netto",
            indexLabel: "{y}",
            yValueFormatString: "$#0.##",
            showInLegend: true,
            dataPoints: <?php echo json_encode($data1, JSON_NUMERIC_CHECK); ?>
        },{
            type: "column",
            name: "cena brutto",
            indexLabel: "{y}",
            yValueFormatString: "$#0.##",
            showInLegend: true,
            dataPoints: <?php echo json_encode($data2, JSON_NUMERIC_CHECK); ?>
        }]
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form action="/search" type="get">
                    <div class="form-row m-5">
                        <H1 style="text-align: center;">Wybierz okres</H1>
                        <div class="form-group col-md-4">
                            <label>Od</label>
                            <input type="date" class="form-control" name="Od_date">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Do</label>
                            <input type="date" class="form-control" name="Do_date">
                        </div>
                        <button class="btn btn-outline-dark my-2 m-3">search</button>
                    </div>
                </form>
                <div class="card-header">{{ __('CAŁA BAZA') }}</div>
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                <div class="container">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('WITAMY, WYBIERZ OKRES DATY DLA SELEKCJI') }}
                    <div>
                        <table style="border: 1px solid black; width: 100%;">
                            <tr>
                                <th>GRUPA</th>
                                <th>DZIEŃ</th>
                                <th>Cena_netto</th>
                                <th>Cena_brutto</th>
                            </tr>
                            @foreach($results as $result)
                            <tr style="border: 1px solid black">
                                <td style="border: 1px solid black; ">{{ $result->nazwa}}</td>
                                <td style="border: 1px solid black; ">{{ $result->data}}</td>
                                <td style="border: 1px solid black; ">{{ $result->cena_netto}}zł</td>
                                <td style="border: 1px solid black; ">{{ $result->cena_brutto}}zł</td>
                            </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">SUMA</th>
                                <th>@foreach($sumNetto as $sumNetto) {{$sumNetto->cena_netto}}zł @endforeach</th>
                                <th>@foreach($sumBrutto as $sumBrutto) {{$sumBrutto->cena_netto}}zł @endforeach</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
