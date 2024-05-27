<?php

namespace App\Http\Controllers;

use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherEntryController extends Controller
{
    public function index(Request $request)
    {
        $voucherId = $request->query('voucher_id');
        return view('voucherentries.index', compact('voucherId'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $voucherId = $request->query('voucher_id');
            $voucherEntries = VoucherEntry::where('voucher_id', $voucherId)->latest()->get();

            return DataTables::of($voucherEntries)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
