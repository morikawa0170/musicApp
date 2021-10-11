<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Playlist;
use Auth;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playlists = Playlist::all(); //プレイリストの情報を全件取得
        return view('playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $playlist = new Playlist;
        $form = $request->all();
        unset($form['_token']);
        $playlist->fill($form)->save();
        return redirect()->route('playlists.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $playlisId = $request->playlistId;
        $playlist = Playlist::where('playlistId',$playlisId)->first();
        // dd($request);
        $state = '';
        $playlistC = $playlist->count();
        
        if($playlistC >= 1) {
            $state = 'registered';
        }

        return view('playlists.show',compact('playlist','state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $playlist = Playlist::find($id);
        $form = $request->all();
        unset($form['_token']);
        $playlist->fill($form)->save();
        
        return redirect()->route('playlists.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mypage()
    {
        
        return view('playlists.mypage');
    }
}
