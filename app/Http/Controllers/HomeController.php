<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sql = 'SELECT grupy_produktow.nazwa,
                zamowienia.data, 
                SUM(produkty.cena_netto * zamowienia.ilosc) AS "cena_netto", 
                SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS "cena_brutto" 
        FROM grupy_produktow
            INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
            INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
        GROUP BY zamowienia.data, grupy_produktow.nazwa 
        ORDER BY zamowienia.data, grupy_produktow.nazwa ASC;';


        $results = DB::select( DB::raw($sql));


        $stats = DB::select( DB::raw("SELECT grupy_produktow.nazwa, SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', 
        SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow
        INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
        INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
        GROUP BY grupy_produktow.nazwa ORDER BY zamowienia.data, grupy_produktow.nazwa ASC;"));

        $sumNetto = "SELECT SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id";
        $sumBrutto = "SELECT SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id";
        
        
        $sumNetto = DB::select(DB::raw($sumNetto));
        $sumBrutto = DB::select(DB::raw($sumBrutto));

        foreach($stats as $row){
            $data1[] = array(
                'label'=>$row->nazwa,
                'y'=>$row->cena_netto
            );

        }
        foreach($stats as $row){
            $data2[] = array(
                'label'=>$row->nazwa,
                'y'=>$row->cena_brutto
            );

        }

        return view('home', compact('results', 'data1', 'data2',  'sumNetto', 'sumBrutto'));
    }
}
