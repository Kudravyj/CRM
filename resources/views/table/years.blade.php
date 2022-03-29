@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <table style="border: 1px solid black; width: 100%; text-align: center;">
                            <tr style="th. {border: 1px solid black; }">
                                <th rowspan="2" style="border: 1px solid black;">GRUPA</th>
                                @foreach($years as $years)
                                    <th colspan="2" style="border: 1px solid black;">{{ $years->date }}</th>
                                @endforeach
                            </tr>
                            <tr style="border: 1px solid black; ">
                                <th style="border: 1px solid black;">netto</th>
                                <th style="border: 1px solid black;">brutto</th>
                                <th style="border: 1px solid black;">netto</th>
                                <th style="border: 1px solid black;">brutto</th>
                                <th style="border: 1px solid black;">netto</th>
                                <th style="border: 1px solid black;">brutto</th>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Książki</td>
                                @foreach($sql1 as $result)
                                    <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
                                    <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Środki czystości</td>
                                @foreach($sql2 as $result)
                                    <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
                                    <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Pieczywo</td>
                                @foreach($sql3 as $result)
                                    <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
                                    <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Owoce</td>
                                @foreach($sql4 as $result)
                                    <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
                                    <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Warzywa</td>
                                @foreach($sql5 as $result)
                                    <td style="border: 1px solid black; ">{{ $result->cena_netto}}</td>
                                    <td style="border: 1px solid black; ">{{ $result->cena_brutto}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th >SUMA</th>
                                @foreach($sumNetto as $sumNetto)<th> {{$sumNetto->cena_netto}}zł</th><th>{{$sumNetto->cena_brutto}}</th> @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection