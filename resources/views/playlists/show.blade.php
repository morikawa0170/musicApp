@extends('layouts.app')

@section('css')
   .back{
      background:none;
      border: none;
   }
@endsection

@section('content')
   <div class="container mt-5">
      <div class="card">
         <div class="card-header align-middle mb-0">
            <h4>{{ $playlistName }}</h4>
         </div>
         <div class="card-body">
            <div class="row">
               <img src="{{$img}}" class="card-text col-2">
               <p class="ml-3">{{ $description }}</p>
            </div>
            <p class="text-right">プレイリスト作成者：<a href="https://open.spotify.com/user/{{ $spotifyId }}">{{ $owner }}</a></p>
            <div class="row pt-2">
               @if ("$state" == "registered")
                  <form action="/musicApp/public/update" id="update" method="POST">
                     @csrf
                     <input type="submit" value="更新する" class="btn btn-primary ml-3 mr-2" onClick="return updateclick()">
                     <input type="hidden" name="id" value="{{$id}}">
                  </form>
                  <form action="/playlists/delete" method="POST">
                     @csrf
                     <input type="hidden" name="playlistId" value="{{$playlistId}}" id="delete">
                     <input type="submit" class="btn btn-danger" value="削除する" onClick="return dltclick()">
                  </form>
               @else
                  <form action="/playlists/create" method="POST">
                     @csrf
                     <input type="submit" class="btn btn-primary ml-3 mr-2" value="登録">
                     <input type="hidden" name="spotifyId"  value="{{$spotifyId}}">
                     <input type="hidden" name="playlistId"  value="{{$playlistId}}">
                     <input type="hidden" name="playlistName"  value="{{$playlistName}}">
                     <input type="hidden" name="username"  value="{{$username}}">
                     <input type="hidden" name="description"  value="{{$description}}">
                     <input type="hidden" name="img"  value="{{$img}}">
                  </form>
               @endif 
               <a href="https://open.spotify.com/playlist/{{ $playlistId }}" class="ml-3 d-block my-auto">変更</a>
            </div>
         </div>
      </div>
      <div>
         <form action="{{ route('mypage', Auth::id()) }}" method='post'>
            @csrf
            <input class="back ml-1 btn-link" type="submit" value="戻る">
         </form>
      </div>
   </div>
@endsection