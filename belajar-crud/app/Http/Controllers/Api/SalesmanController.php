<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesmanController extends Controller
{
    // 1. GET ALL DATA (Urut sesuai Kode Salesman)
    public function index()
    {
        $data = Salesman::orderBy('kd_salesman', 'asc')->get();
        return response()->json(['success' => true, 'message' => 'Daftar Salesman Berhasil Diambil', 'data' => $data], 200);
    }

    // 2. STORE / SIMPAN BARU (Validasi Kode Kembar)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kd_salesman'   => 'required|unique:salesman,kd_salesman',
            'nama_salesman' => 'required',
            'tgl_input'     => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi Gagal', 'errors' => $validator->errors()], 422);
        }

        $salesman = Salesman::create($request->all());
        return response()->json(['success' => true, 'message' => 'Data Salesman Berhasil Disimpan', 'data' => $salesman], 201);
    }

    // 3. SHOW DETAIL (Untuk Lempar Data ke Form Saat Edit)
    public function show($kd_salesman)
    {
        $salesman = Salesman::find($kd_salesman);
        if (!$salesman) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $salesman], 200);
    }

    // 4. UPDATE DATA
    public function update(Request $request, $kd_salesman)
    {
        $salesman = Salesman::find($kd_salesman);
        if (!$salesman) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_salesman' => 'required',
            'tgl_input'     => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi Gagal', 'errors' => $validator->errors()], 422);
        }

        $salesman->update($request->only(['nama_salesman', 'tgl_input', 'keterangan']));
        return response()->json(['success' => true, 'message' => 'Data Salesman Berhasil Diperbarui', 'data' => $salesman], 200);
    }

    // 5. DESTROY / HAPUS DATA
    public function destroy($kd_salesman)
    {
        $salesman = Salesman::find($kd_salesman);
        if (!$salesman) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $salesman->delete();
        return response()->json(['success' => true, 'message' => 'Data Salesman Berhasil Dihapus'], 200);
    }

    // 6. EXPORT EXCEL
    public function exportExcel()
    {
        $data = Salesman::orderBy('kd_salesman', 'asc')->get();
        if ($data->isEmpty()) {
            return response("<script>alert('Gagal cetak! Tidak ada data Salesman.'); window.close();</script>");
        }

        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function collection() {
                return Salesman::orderBy('kd_salesman', 'asc')->get(['kd_salesman', 'nama_salesman', 'tgl_input', 'keterangan']);
            }
            public function headings(): array {
                return ["Kode Salesman", "Nama Salesman", "Tanggal Bergabung", "Keterangan"];
            }
        }, 'Laporan_Master_Salesman.xlsx');
    }

    // 7. EXPORT PDF
    public function exportPdf()
    {
        $data = Salesman::orderBy('kd_salesman', 'asc')->get();
        if ($data->isEmpty()) {
            return response("<script>alert('Gagal cetak! Tidak ada data Salesman.'); window.close();</script>");
        }

        $html = '<h2 style="text-align: center; font-family: Arial, sans-serif; color: #1f4e78;">PT BIOPLAST UNGGUL</h2>
        <h3 style="text-align: center; font-family: Arial, sans-serif; margin-top: -10px;">LAPORAN DATA MASTER SALESMAN</h3>
        <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
            <thead>
                <tr style="background-color: #2c3e50; color: white;">
                    <th>No</th>
                    <th>Kode Sales</th>
                    <th>Nama Salesman</th>
                    <th>Tgl Bergabung</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($data as $key => $item) {
            $html .= '<tr>
                <td style="text-align:center;">'.($key+1).'</td>
                <td style="font-weight:bold; color:#1f4e78;">'.$item->kd_salesman.'</td>
                <td>'.$item->nama_salesman.'</td>
                <td style="text-align:center;">'.$item->tgl_input.'</td>
                <td>'.($item->keterangan ?? '-').'</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        return Pdf::loadHTML($html)->download('Laporan_Master_Salesman.pdf');
    }
}