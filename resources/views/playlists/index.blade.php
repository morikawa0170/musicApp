@extends('layouts.app')
@section('css')
   .table td{ 
      vertical-align: middle; 
      height: 100px; 
   }
@endsection

@section('content')
   <div id="list" class="container">
      <table class="table">
            <thead class="thead-light">
               <tr><th  class="w-25">タイトル</th><th style="width: 60%;">説明</th><th style="text-align:center; width: 15%;">作成者</th></tr>
            </thead>
            <tbody>
               @foreach ($playlists as $playlist)
                  <tr>
                        <td class="w-25">
                           <a href="/musicApp/public/chat/{{ $playlist -> playlistId }}">
                              <img src="{{ $playlist -> img }}" width="100px" height="100px'">
                           {{ $playlist -> playlistName }}</a>
                        </td>
                        <td class="w-50">
                           <p style="font-size: 14px;">{{ $playlist -> description }}</p>
                        </td>
                        <td class="w-25" style="text-align:center;">
                           <p><a href="https://open.spotify.com/user/{{ $playlist->spotifyId}}">{{ $playlist -> owner }}</a></p>
                        </td>
                  </tr>
               @endforeach    
            </tbody>
      </table>
   </div>
@endsection

