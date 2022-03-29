<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
class products extends Controller


{


    public function view()
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
    public function search()
    {
        $Od_date = $_GET['Od_date'];
        $Do_date = $_GET['Do_date'];

        $sql = "SELECT grupy_produktow.nazwa,
            zamowienia.data, 
        SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', 
        SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' 
            FROM grupy_produktow
        INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
        INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
            WHERE zamowienia.data between '$Od_date' AND '$Do_date'
        GROUP BY zamowienia.data, grupy_produktow.nazwa 
        ORDER BY zamowienia.data, grupy_produktow.nazwa ASC;";

        $end = DB::select( DB::raw("SELECT grupy_produktow.nazwa, SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', 
        SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow
        INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
        INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
            WHERE zamowienia.data between '$Od_date' AND '$Do_date'
        GROUP BY grupy_produktow.nazwa"));

        
        $sumNetto = "SELECT SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE zamowienia.data between '$Od_date' AND '$Do_date';";
        
        
        $stats = DB::select( DB::raw($sql));
        $sumNetto = DB::select(DB::raw($sumNetto));

        foreach($end as $row){
            $data1[] = array(
                'label'=>$row->nazwa,
                'y'=>$row->cena_netto
            );

        }
        foreach($end as $row){
            $data2[] = array(
                'label'=>$row->nazwa,
                'y'=>$row->cena_brutto
            );

        }

        return view('table.search', compact('stats', 'Od_date', 'Do_date', 'sumNetto', 'data1', 'data2'));
    }

    public function export()
    {
        $Od_date = $_GET['Od_date'];
        $Do_date = $_GET['Do_date'];

        $sql = "SELECT grupy_produktow.nazwa,
            zamowienia.data, 
        SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', 
        SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' 
            FROM grupy_produktow
        INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
        INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
            WHERE zamowienia.data between '$Od_date' AND '$Do_date'
        GROUP BY zamowienia.data, grupy_produktow.nazwa 
        ORDER BY zamowienia.data, grupy_produktow.nazwa ASC;";
        
        
        $sumNetto = "SELECT SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE zamowienia.data between '$Od_date' AND '$Do_date';";

        $stats = DB::select( DB::raw($sql));    
        $sumNetto = DB::select(DB::raw($sumNetto));

        return Excel::download(new ExcelExport($stats, $sumNetto), 'table.xlsx',);
    }

    
    public function index()
    {
        $stats = DB::select( DB::raw("SELECT YEAR(zamowienia.data) as 'data', grupy_produktow.nazwa, SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', 
        SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow
        INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id
        INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id
        GROUP BY YEAR(zamowienia.data), grupy_produktow.nazwa ORDER BY zamowienia.data, grupy_produktow.nazwa ASC;"));
        
        $sql1 = "SELECT Year(zamowienia.data), SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE grupy_produktow.nazwa = 'Książki' GROUP BY YEAR(zamowienia.data);";
        $sql2 = "SELECT Year(zamowienia.data), SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE grupy_produktow.nazwa = 'Środki czystości' GROUP BY YEAR(zamowienia.data);";
        $sql3 = "SELECT Year(zamowienia.data), SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE grupy_produktow.nazwa = 'Pieczywo' GROUP BY YEAR(zamowienia.data);";
        $sql4 = "SELECT Year(zamowienia.data), SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE grupy_produktow.nazwa = 'Owoce' GROUP BY YEAR(zamowienia.data);";
        $sql5 = "SELECT Year(zamowienia.data), SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id WHERE grupy_produktow.nazwa = 'Warzywa' GROUP BY YEAR(zamowienia.data);";

        $sqlYears = "SELECT Year(`data`) AS 'date' FROM `zamowienia` GROUP BY Year(`data`) HAVING count(`data`)>1;";
        $sql1 = DB::select(DB::raw($sql1));
        $sql2 = DB::select(DB::raw($sql2));
        $sql3 = DB::select(DB::raw($sql3));
        $sql4 = DB::select(DB::raw($sql4));
        $sql5 = DB::select(DB::raw($sql5));
        $years = DB::select(DB::raw($sqlYears));

        $sumNetto = "SELECT SUM(produkty.cena_netto * zamowienia.ilosc) AS 'cena_netto', SUM(produkty.cena_netto * zamowienia.ilosc/77 * 100) AS 'cena_brutto' FROM grupy_produktow INNER JOIN produkty ON produkty.id_grupa = grupy_produktow.id INNER JOIN zamowienia ON zamowienia.id_produkt = produkty.id GROUP BY YEAR(zamowienia.data);";
        
        $sumNetto = DB::select(DB::raw($sumNetto));
        return view('table.years', compact('stats','years', 'sql1', 'sql2', 'sql3', 'sql4','sql5', 'sumNetto'));
    }
}