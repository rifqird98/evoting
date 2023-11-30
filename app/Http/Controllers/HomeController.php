<?php

namespace App\Http\Controllers;

use App\Peserta;
use App\HasilVoting;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function result( Request $request)
    {
        if ($request->password == "PRM2023") {
            return view('hasil_result');
        }else{
            return response()->json(['error' => 'Failed to update record', 'message' => 'password salah'], 500);
        }
    }

    public function getVote(Request $request)
    {
        $data = HasilVoting::where('pimpinan', $request->pimpinan)->orderBy('jumlah_vote', 'DESC')->get();
        return response()->json($data);
    }
    public function getPeserta(Request $request)
    {
        $data = Peserta::where('status_vote', $request->statusPeserta)
            ->where('pimpinan', $request->namaPimpinan)
            ->orderBy('nama', 'ASC')->get();

        return response()->json($data);
    }

    public function updateStatus(Request $request)
    {
        try {
            // Attempt to find the record
            $data = Peserta::findOrFail($request->id);

            // Update the record
            $data->update([
                'status_vote' => 'proses',
                'perangkat' => $request->perangkat
            ]);

            // Return a success response
            return response()->json(['message' => 'Record updated successfully', 'data' => $data]);
        } catch (ModelNotFoundException $e) {
            // Handle the case where the record with the specified ID was not found
            return response()->json(['error' => 'Record not found'], 404);
        } catch (\Throwable $th) {
            // Handle other exceptions
            return response()->json(['error' => 'Failed to update record', 'message' => $th->getMessage()], 500);
        }
    }
}
