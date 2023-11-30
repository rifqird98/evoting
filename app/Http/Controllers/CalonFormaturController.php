<?php

namespace App\Http\Controllers;


use App\Peserta;
use App\ResultVote;
use App\CalonFormatur;
use Illuminate\Http\Request;

class CalonFormaturController extends Controller
{
    public function getDataCalon(Request $request)
    {
        $data = CalonFormatur::where('pimpinan', $request->pimpinan)->get();
        return response()->json($data);
    }

    public function getPemilihPmr(Request $request)
    {
        $data = Peserta::where('status_vote', 'proses')->where('pimpinan', 'PRM')
            ->where('perangkat', $request->namaPerangkat)
            ->orderBy('updated_at', 'ASC')->first();

        return response()->json($data);
    }
    public function getPemilihPra(Request $request)
    {
        $data = Peserta::where('status_vote', 'proses')->where('pimpinan', 'PRA')
            ->where('perangkat', $request->namaPerangkat)
            ->orderBy('updated_at', 'ASC')->first();

        return response()->json($data);
    }

    public function simpanSuara(Request $request)
    {
        $dataArray = $request->all(); // Mendapatkan seluruh data dari Request
        $id_pemilih = $dataArray[0]['id_pemilih'];

        $data = Peserta::findOrFail($id_pemilih);
        $data->update([
            'status_vote' => 'true',
        ]);
        foreach ($dataArray as $item) {
            ResultVote::create([
                'id_calon_pimpinan' => $item['id_calon'],
                'id_peserta' => $item['id_pemilih'],
                // Sesuaikan kolom-kolom lainnya sesuai kebutuhan
            ]);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
