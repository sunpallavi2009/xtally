<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view ('roles.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::latest()->get();

            return DataTables::of($roles)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function create()
    {
        return view ('roles._create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }
}
