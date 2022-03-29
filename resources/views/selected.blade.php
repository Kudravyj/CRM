<table style="border: 1px solid black; width: 100%;">
    <tr>
        <th>GRUPA</th>
        <th>DZIEŃ</th>
        <th>Cena_netto</th>
        <th>Cena_brutto</th>
    </tr>
    @foreach($stats as $result)
    <tr style="border: 1px solid black">
        <td style="border: 1px solid black; ">{{ $result->nazwa}}</td>
        <td style="border: 1px solid black; ">{{ $result->data}}</td>
        <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
        <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
    </tr>
    @endforeach
    <tr>
        <th colspan="2">SUMA</th>
        @foreach($sumNetto as $sumNetto)<th> {{$sumNetto->cena_netto}}zł</th><th>{{$sumNetto->cena_brutto}}</th> @endforeach
    </tr>
</table>