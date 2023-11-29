<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class ReportController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{

    public function report(Request $request)
    {
        if ($request->ajax()){

        }
        return view('custom.reports.report');
    }
}
