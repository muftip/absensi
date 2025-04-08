<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $harilibur = HariLibur::all();
        return view('libur.hariLibur', ["harilibur" => $harilibur]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('libur.createHariLibur');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|unique:libur,keterangan',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ]);

        $harilibur = new HariLibur([
            'keterangan' => $request->keterangan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        $harilibur->save();
        return redirect('hari-libur')->with('success', 'Data hari libur memperingati "' . $harilibur->keterangan . '" berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $harilibur = HariLibur::find($id);
        return view("libur.editHariLibur", ["harilibur" => $harilibur]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'keterangan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ]);

        // $jabatanSebelum = Jabatan::find($id);
        // $namaJabatanSebelum = $jabatanSebelum->nama;

        HariLibur::find($id)->update($validasi);
        return redirect('hari-libur')->with('success', 'Data hari libur memperingati "' . $request->keterangan . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();
        return redirect('hari-libur')->with('success', 'Data hari libur memperingati "' . $hariLibur->keterangan . '" berhasil dihapus.');
    }
}
