<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Auth;
use App\Models\Log;
use App\Models\TrackDetail;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:lokasi');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasis = Lokasi::all();
        return view('lokasi.index', compact("lokasis"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lokasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama_lokasi' => 'required'
        ]);

        $new = new Lokasi();
        $new->nama_lokasi = $request->nama_lokasi;
        $new->save();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'create', 'Create new lokasi data (ID: '.$new->id.' | Lokasi: '.$new->nama_lokasi.')', '\App\Lokasi', 'LokasiController@store');

        return redirect()->route('admin.dashboard.lokasi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function show(Lokasi $lokasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lokasi = Lokasi::find($id);
        return view('lokasi.edit', compact("lokasi"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'nama_lokasi' => 'required'
        ]);

        $new = Lokasi::find($id);
        $new->nama_lokasi = $request->nama_lokasi;
        $new->save();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'update', 'Update lokasi data (ID: '.$new->id.' | Lokasi: '.$new->nama_lokasi.')', '\App\Lokasi', 'LokasiController@update');

        return redirect()->route('admin.dashboard.lokasi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lokasi = Lokasi::find($id);
        $lokasi->delete();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'delete', 'Delete lokasi data (ID: '.$lokasi->id.' | Lokasi: '.$lokasi->nama_lokasi.')', '\App\Lokasi', 'LokasiController@destroy');

        $track_details = TrackDetail::where('id_lokasi', $id)->get();
        foreach ($track_details as $track_detail) {
            $track_detail->delete();

            $log = new Log();
            $log->createLog(Auth::user()->name, 'delete', 'Delete track detail data (ID: '.$track_detail->id.' | ID Track: '.$track_detail->id_track.')', '\App\TrackDetail', 'TrackDetailController@destroy');
        }

        return redirect()->route('admin.dashboard.lokasi.index');
    }
}
