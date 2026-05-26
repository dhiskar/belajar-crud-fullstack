<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    // 1. GET ALL DATA + RELATION
    public function index()
    {
        $data = Pelanggan::with(['jenis_pelanggan', 'salesman'])->orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    // 2. STORE DATA (INSERT)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kd_customer' => 'required|unique:m_pelanggan,kd_customer',
            'nama_customer' => 'required|string|max:150',
            'kd_jenis_customer' => 'required',
            'kd_salesman' => 'required',
            'alamat' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pelanggan = Pelanggan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Pelanggan berhasil disimpan',
            'data' => $pelanggan
        ], 201);
    }

    // 3. SHOW DETAIL (FOR EDIT)
    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pelanggan
        ], 200);
    }

    // 4. UPDATE DATA
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_customer' => 'required|string|max:150',
            'kd_jenis_customer' => 'required',
            'kd_salesman' => 'required',
            'alamat' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pelanggan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Pelanggan berhasil diperbarui'
        ], 200);
    }

    // 5. DELETE DATA
    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $pelanggan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Pelanggan berhasil dihapus'
        ], 200);
    }
}