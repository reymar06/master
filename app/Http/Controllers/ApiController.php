<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
     public function GetData(Request $request)
    {
        $json = file_get_contents("http://10.1.1.2/itab/houseStartBook/" . $request->customerCode . "/" . $request->houseTypeCode . "/boss");
        return $json;
    }

    public function GetClientName(Request $request)
    {
        /// return 'pak dis shit';
        return \DB::connection('hrdsql')
            ->table('dbo.Constructions')
            ->where('ConstructionCode', $request->customerCode)
            ->get();
    }
}
