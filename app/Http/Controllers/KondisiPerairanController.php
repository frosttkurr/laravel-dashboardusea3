<?php

namespace App\Http\Controllers;

use App\Models\KondisiPerairan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class KondisiPerairanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:kondisi-perairan', ['except' => ['indexNelayan']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kondisiPerairans = KondisiPerairan::all();
        return view('kondisi-perairan.index', compact("kondisiPerairans"));
    }

    
    public function indexNelayan()
    {
        $kondisiPerairans = KondisiPerairan::all();
        return view('kondisi-perairan.nelayan.index', compact("kondisiPerairans"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lokasis = Lokasi::all();
        return view('kondisi-perairan.create', compact("lokasis"));
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
            'tanggal' => 'required',
            'id_lokasi' => 'required',
            'kondisi' => 'required',
            'uraian' => 'required',
        ]);

        $new = new KondisiPerairan();
        $new->id_lokasi = $request->id_lokasi;
        $new->id_staff = Auth::user()->id;
        $new->tanggal = $request->tanggal;
        $new->kondisi = $request->kondisi;
        $new->uraian = $request->uraian;
        $new->save();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'create', 'Create new kondisi perairan data (ID: '.$new->id.' | Tanggal: '.$new->tanggal.')', '\App\KondisiPerairan', 'KondisiPerairanController@store');

        return redirect()->route('admin.dashboard.kondisi-perairan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KondisiPerairan  $kondisiPerairan
     * @return \Illuminate\Http\Response
     */
    public function show(KondisiPerairan $kondisiPerairan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KondisiPerairan  $kondisiPerairan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kondisiPerairan = KondisiPerairan::find($id);
        $lokasis = Lokasi::all();
        return view('kondisi-perairan.edit', compact("kondisiPerairan", "lokasis"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KondisiPerairan  $kondisiPerairan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'tanggal' => 'required',
            'id_lokasi' => 'required',
            'kondisi' => 'required',
            'uraian' => 'required',
        ]);
        
        $new = KondisiPerairan::find($id);
        $new->id_lokasi = $request->id_lokasi;
        $new->tanggal = $request->tanggal;
        $new->kondisi = $request->kondisi;
        $new->uraian = $request->uraian;
        $new->save();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'update', 'Update kondisi perairan data (ID: '.$new->id.' | Tanggal: '.$new->tanggal.')', '\App\KondisiPerairan', 'KondisiPerairanController@update');

        return redirect()->route('admin.dashboard.kondisi-perairan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KondisiPerairan  $kondisiPerairan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lokasi = KondisiPerairan::find($id);
        $lokasi->delete();

        $log = new Log();
        $log->createLog(Auth::user()->name, 'delete', 'Delete kondisi perairan data (ID: '.$lokasi->id.' | Tanggal: '.$lokasi->tanggal.')', '\App\KondisiPerairan', 'KondisiPerairanController@destroy');

        return redirect()->route('admin.dashboard.kondisi-perairan.index');
    }
}
