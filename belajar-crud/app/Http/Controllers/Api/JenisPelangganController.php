<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class JenisPelangganController extends Controller
{
    /**
     * 1. METHOD GET: Mengambil semua data untuk tabel list di frontend
     */
    public function index()
    {
        // Tarik semua data dari tabel jenis_pelanggans di PostgreSQL urut paling baru
        $data = JenisPelanggan::orderBy('created_at', 'asc')->get();

        // Kembalikan dalam format JSON yang dimengerti oleh JavaScript Frontend
        return response()->json([
            'success' => true,
            'message' => 'Daftar Jenis Pelanggan Berhasil Diambil',
            'data'    => $data
        ], 200);
    }

    /**
     * 2. METHOD POST: Menerima data dari form frontend dan simpan ke database
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari frontend agar database aman dari data rusak
        $validator = Validator::make($request->all(), [
            'kd_jenis_customer'   => 'required|string|max:20|unique:jenis_pelanggans,kd_jenis_customer',
            'nama_jenis_customer' => 'required|string|max:100',
            'keterangan'          => 'nullable|string',
        ]);

        // Jika data tidak valid (misal: kode kembar atau kosong)
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Jika lolos validasi, langsung masukkan ke PostgreSQL via Model
        $jenisPelanggan = JenisPelanggan::create([
            'kd_jenis_customer'   => $request->kd_jenis_customer,
            'nama_jenis_customer' => $request->nama_jenis_customer,
            'keterangan'          => $request->keterangan,
        ]);

        // Beri laporan sukses ke frontend
        return response()->json([
            'success' => true,
            'message' => 'Data Jenis Pelanggan Berhasil Disimpan',
            'data'    => $jenisPelanggan
        ], 201);
    }
    /**
     * 3. METHOD GET (Spesifik): Mengambil 1 data berdasarkan Kode untuk modal/form Edit
     */
    public function show($kd_jenis_customer)
    {
        $jenisPelanggan = jenisPelanggan::where('kd_jenis_customer', $kd_jenis_customer)->first();

        if (!$jenisPelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $jenisPelanggan
        ], 200);
    }

    /**
     * 4. METHOD PUT: Memperbarui data yang telah diedit
     */
    public function update(Request $request, $kd_jenis_customer)
    {
        $jenisPelanggan = jenisPelanggan::where('kd_jenis_customer', $kd_jenis_customer)->first();

        if (!$jenisPelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Validasi: Karena kodenya tidak berubah/dikunci, validasi unik dikecualikan untuk data ini sendiri
        $validator = Validator::make($request->all(), [
            'nama_jenis_customer' => 'required|string|max:100',
            'keterangan'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Jalankan Update
        $jenisPelanggan->update([
            'nama_jenis_customer' => $request->nama_jenis_customer,
            'keterangan'          => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Jenis Pelanggan Berhasil Diperbarui',
            'data'    => $jenisPelanggan
        ], 200);
    }

    /**
     * 5. METHOD DELETE: Menghapus data dari PostgreSQL
     */
    public function destroy($kd_jenis_customer)
    {
        $jenisPelanggan = jenisPelanggan::where('kd_jenis_customer', $kd_jenis_customer)->first();

        if (!$jenisPelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau sudah dihapus'
            ], 404);
        }

        // Jalankan perintah hapus
        $jenisPelanggan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Jenis Pelanggan Berhasil Dihapus dari Database'
        ], 200);
    }
    /**
     * 6. EXPORT EXCEL VIA LARAVEL
     */
    public function exportExcel()
    {
        // Anonymous class untuk menyusun layout Excel instan secara berurutan
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function collection() {
                return \App\Models\jenisPelanggan::orderBy('kd_jenis_customer', 'asc')
                    ->get(['kd_jenis_customer', 'nama_jenis_customer', 'keterangan']);
            }
            public function headings(): array {
                return ["Kode Jenis Customer", "Nama Jenis Customer", "Keterangan Catatan"];
            }
        }, 'Laporan_Jenis_Pelanggan.xlsx');
    }

    /**
     * 7. EXPORT PDF VIA DOMPDF
     */
    public function exportPdf()
    {
        $data = \App\Models\jenisPelanggan::orderBy('kd_jenis_customer', 'asc')->get();
        // JIKA DATA KOSONG, JANGAN PROSES PDF AGAR TIDAK EROR
        if ($data->isEmpty()) {
            return response("<script>alert('Gagal cetak! Tidak ada data Jenis Pelanggan di database untuk diexport.'); window.close();</script>");
        }
        // Membuat layout HTML bersih langsung di dalam controller untuk diconvert ke PDF
        $html = '
        <h2 style="text-align: center; font-family: Arial, sans-serif; color: #1f4e78;">PT BIOPLAST UNGGUL</h2>
        <h3 style="text-align: center; font-family: Arial, sans-serif; margin-top: -10px;">LAPORAN DATA MASTER JENIS PELANGGAN</h3>
        <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
            <thead>
                <tr style="background-color: #2c3e50; color: white;">
                    <th>No</th>
                    <th>Kode Jenis</th>
                    <th>Nama Jenis Customer</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($data as $key => $item) {
            $html .= '<tr>
                <td style="text-align:center;">'.($key+1).'</td>
                <td style="font-weight:bold; color:#1f4e78;">'.$item->kd_jenis_customer.'</td>
                <td>'.$item->nama_jenis_customer.'</td>
                <td>'.($item->keterangan ?? '-').'</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('Laporan_Jenis_Pelanggan.pdf');
    }
}