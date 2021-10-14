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
            <h4>{{ $playlist->playlistName }}</h4>
         </div>
         <div class="card-body">
            <div class="row">
               <img src="{{$playlist->img}}" class="card-text col-2">
               <p class="ml-3">{{ $playlist->description }}</p>
            </div>
            <p class="text-right">プレイリスト作成者：<a href="https://open.spotify.com/user/{{ $playlist->spotifyId }}">{{ $playlist->owner }}</a></p>
            <div class="row pt-2">
               @if ($state == "registered")
                  <form action="{{ route('playlists.update',$playlist->id) }}" id="update" method="POST">
                     @csrf
                     <input type="submit" value="更新" class="btn btn-primary ml-3 mr-2" onClick="return updateclick()">
                  </form>
                  <form action="{{ route('playlists.delete',$playlist->id) }}" method="POST">
                     @csrf
                     <input type="hidden" name="playlistId" value="{{$playlist->playlistId}}" id="delete">
                     <input type="submit" class="btn btn-danger" value="削除" onClick="return dltclick()">
                  </form>
               @else
                  <form action="{{ route('playlists.store') }}" method="POST">
                     @csrf
                     <input type="submit" class="btn btn-primary ml-3 mr-2" value="登録">
                     <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                     <input type="hidden" name="spotifyId"  value="{{$playlist->spotifyId}}">
                     <input type="hidden" name="playlistId"  value="{{$playlist->playlistId}}">
                     <input type="hidden" name="playlistName"  value="{{$playlist->playlistName}}">
                     <input type="hidden" name="owner"  value="{{ Auth::user()->name }}">
                     <input type="hidden" name="description"  value="{{$playlist->description}}">
                     <input type="hidden" name="img"  value="{{$playlist->img}}">
                  </form>
               @endif 
               <a href="https://open.spotify.com/playlist/{{ $playlist->playlistId }}" class="ml-3 d-block my-auto">編集</a>
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
   <script>
      function dltclick() {
         var result = confirm('本当に削除してよろしいですか？');
         if(result == false) {
            return false;
         }
      }   
      
      function updateclick() {
         var result = confirm('プレイリストの情報を最新の状態に更新します。');
         if(result == false) {
            return false;
         }
      } 
      
      var Client_ID = "c952338e635a43308c36d5ebdee12aae"; 
      var Client_Secret = "effa2a4707f948159369e7708a899c9e"; 
      
      var base64 = btoa(Client_ID + ":" + Client_Secret); 
      
      var token="";
      var type="";
      function update() {
         var spotifyId ="{{$playlist->spotifyId}}"; //現在ログインしているアカウントのspotifyid
         var ajax = new XMLHttpRequest();
         ajax.open("post", "https://accounts.spotify.com/api/token");
         // サーバに対して解析方法を指定する
         ajax.setRequestHeader( 'Authorization', 'Basic '+ base64 );
         ajax.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
         // データをリクエスト ボディに含めて送信する
         ajax.send( "grant_type=client_credentials" );
         ajax.responseType = "json";
         ajax.addEventListener("load", function(){ // loadイベントを登録します。
            var json = this.response;
            token = json["access_token"];
            
            var ajax2 = new XMLHttpRequest();
            ajax2.open("get", "https://api.spotify.com/v1/playlists/{{$playlist->playlistId}}");
            ajax2.setRequestHeader( 'Authorization', 'Authorization: Bearer '+ token);
            ajax2.send();
            ajax2.responseType = "json";
            ajax2.addEventListener("load", function(){ // loadイベントを登録します。
               var json2 = this.response;
               var update = document.getElementById('update');
               var playlistId = json2.id;
               var playlistName = json2.name;
               var description = json2.description;
               var img = json2.images[0].url;
               var button ="<input type='hidden' name='playlistId' value='"+playlistId+"'>"
                        +"<input type='hidden' name='playlistName' value='"+playlistName+"'>"
                        +"<input type='hidden' name='description' value='"+description+"'>"
                        +"<input type='hidden' name='img' value='"+img+"'>";
               update.insertAdjacentHTML('beforeend',button);
            });
         });
      }   
      var hundle = update();
   </script>
@endsection