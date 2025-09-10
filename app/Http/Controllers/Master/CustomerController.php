<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupCustomer;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('feature.master.customer.index');
    }

    public function data(Request $request)
    {
        $query = SetupCustomer::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editCustomer(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteCustomer(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';

            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:setup_customer,kode',
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'catatan' => 'nullable',
        ]);

        $customer = SetupCustomer::create([
            'kode'       => $request->kode,
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'telepon'    => $request->telepon,
            'email'      => $request->email,
            'catatan'    => $request->catatan,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function show($uuid)
    {
        $customer = SetupCustomer::where('uuid', $uuid)->firstOrFail();
        return response()->json($customer);
    }

    public function update(Request $request, $uuid)
    {
        $customer = SetupCustomer::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode' => 'required|unique:setup_customer,kode,' . $customer->id,
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'catatan' => 'nullable',
        ]);

        $customer->update($request->all());

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function destroy($uuid)
    {
        $customer = SetupCustomer::where('uuid', $uuid)->firstOrFail();
        $customer->delete();
        return response()->json(['success' => true]);
    }
}