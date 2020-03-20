<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\LaundryPrice;
use App\LaundryType;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = LaundryPrice::with(['type', 'user'])->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $products = $products->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $products = $products->paginate(10);
        return new ProductCollection($products);
    }
    public function getLaundryType()
    {
        $type = LaundryType::orderBy('name', 'ASC')->get(); //GET DATA LAUNDRY TYPE DENGAN DI URUTKAN BERDASARKAN NAMA
        return response()->json(['status' => 'success', 'data' => $type]);
    }

    public function storeLaundryType(Request $request)
    {
        //VALIDASI DATA YANG DIKIRIM
        $this->validate($request, [
            'name_laundry_type' => 'required|unique:laundry_types,name'
        ]);

        //SIMPAN DATA BARU KE DALAM TABLE LAUNDRY_TYPES
        LaundryType::create(['name' => $request->name_laundry_type]);
        return response()->json(['status' => 'success']);
    }

    public function store(Request $request)
    {
        //VALIDASI DATA YANG DIKIRIM
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'unit_type' => 'required',
            'price' => 'required|integer',
            'laundry_type' => 'required',
            'service' => 'required|integer',
            'service_type' => 'required'
        ]);

        try {
            //SIMPAN DATA PRODUCT KE DALAM TABLE laundry_prices
            LaundryPrice::create([
                'name' => $request->name,
                'unit_type' => $request->unit_type,
                'laundry_type_id' => $request->laundry_type,
                'price' => $request->price,
                'user_id' => auth()->user()->id,
                'service' => $request->service,
                'service_type' => $request->service_type
            ]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed']);
        }
    }
    public function edit($id)
    {
        $laundry = LaundryPrice::find($id); //MENGAMBIL DATA BERDASARKAN ID
        return response()->json(['status' => 'success', 'data' => $laundry]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'unit_type' => 'required',
            'price' => 'required|integer',
            'laundry_type' => 'required',
            'service' => 'required|integer',
            'service_type' => 'required'
        ]);
        $laundry = LaundryPrice::find($id); //MENGAMBILD ATA BERDASARKAN ID
        //KEMUDIAN MENG-UPDATE DATA TERSEBUT
        $laundry->update([
            'name' => $request->name,
            'unit_type' => $request->unit_type,
            'laundry_type_id' => $request->laundry_type,
            'price' => $request->price,
            'service' => $request->service,
            'service_type' => $request->service_type
        ]);
        return response()->json(['status' => 'success']);
    }
    public function destroy($id)
    {
        $laundry = LaundryPrice::find($id);
        $laundry->delete();
        return response()->json(['status' => 'success']);
    }
}
