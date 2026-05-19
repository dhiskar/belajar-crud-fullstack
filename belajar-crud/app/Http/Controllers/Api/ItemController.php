<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar data persediaan',
            'data' => $items
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Hapus baris 'name' dari validasi
    $request->validate([
        'kode_persediaan' => 'required|unique:items,kode_persediaan',
        'nama_persediaan' => 'required',
        'satuan'          => 'required',
        'jumlah'          => 'required|numeric',
        'berat'           => 'required|numeric',
        'harga'           => 'required|numeric',
    ]);

    // Hapus baris 'name' dari proses create
    $item = Item::create([
        'kode_persediaan' => $request->kode_persediaan,
        'nama_persediaan' => $request->nama_persediaan,
        'satuan'          => $request->satuan,
        'jumlah'          => $request->jumlah,
        'berat'           => $request->berat,
        'harga'           => $request->harga,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Data Persediaan Barang Berhasil Disimpan!',
        'data'    => $item
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
