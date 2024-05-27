<?php

namespace App\Http\Controllers;

use App\Models\TallyCompany;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SocietyController extends Controller
{
    public function index()
    {
        return view('society.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $society = TallyCompany::latest()->get();

            return DataTables::of($society)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
