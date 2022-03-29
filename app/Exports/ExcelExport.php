<?php

namespace App\Exports;

use App\Models\Products;

use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\Exportable;
use illuminate\contracts\View\View;
use Illuminate\Support\Facades\DB;
class ExcelExport implements FromView
{
    public function __construct($stats, $sumNetto)
    {
        $this->stats = $stats;
        $this->sumNetto = $sumNetto;
    }
    // Export z bibliotecy 
    public function view(): view
    {
        return view('selected', [
            'stats'=>$this->stats,
            'sumNetto'=>$this->sumNetto
        ]);
    }
}
