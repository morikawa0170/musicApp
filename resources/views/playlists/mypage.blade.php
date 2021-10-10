@extends('layouts.app')

@section('css')
.table td{
   vertical-align: middle;
}
@endsection

@section('content')
<div class="container d-flex justify-content-between">
            <h4 class="mb-5">{{ Auth::user()->name }}さんの公開プレイリスト一覧</h4>
        </div>
        <div id="list" class="container"></div>
        <script>
            var Client_ID = "c952338e635a43308c36d5ebdee12aae"; 
            var Client_Secret = "effa2a4707f948159369e7708a899c9e"; 
            
            var base64 = btoa(Client_ID + ":" + Client_Secret); 
            
            var token="";
            var type="";
            function search() {
                var spotifyId ="{{ Auth::user()->spotify_id }}"; //現在ログインしているアカウントのspotifyid
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
                    ajax2.open("get", "https://api.spotify.com/v1/users/"+ spotifyId +"/playlists");
                    ajax2.setRequestHeader( 'Authorization', 'Authorization: Bearer '+ token
                    );
                    ajax2.send();
                    ajax2.responseType = "json";
                    ajax2.addEventListener("load", function(){ // loadイベントを登録します。
                        var html="<table class='table'>"
                        html+="<thead class='thead-light'><tr><th class='w-25'>タイトル</th><th style='width: 70%;'>説明</th><th style='text-align:center; width: 5%;'>詳細</th></tr></thead>"
                        var json2 = this.response;
                        //
                        for (var i=0;i<json2.items.length;i++) {
                            var playlistName=json2.items[i].name; //プレイリスト名
                            var img=null;
                            var playlistId = json2.items[i].id;　//プレイリストID
                            var ownerId = json2.items[i].owner.id; //プレイリスト作成者のID
                            var description = json2.items[i].description;
                            var owner = json2.items[i].owner.display_name;
                            //自分の作成したプレイリストのみを表示
                            if(spotifyId == ownerId) {
                                if (json2.items[i].images.length > 0) {
                                  img=json2.items[i].images[0].url;
                                 }
                                html += "<tr><td><a href=/musicApp/public/chat/"+playlistId+">";
                                //画像がある場合は表示
                                if (img!=null) {
                                    html += "<img src='"+img+"'width=100px height=100px'>";
                                }
                                var button ="<input type='submit' class='btn btn-primary ' value='詳細'>"
                                            +"<input type='hidden' name='spotifyId' value='"+spotifyId+"'>"
                                            +"<input type='hidden' name='playlistId' value='"+playlistId+"'>"
                                            +"<input type='hidden' name='playlistName' value='"+playlistName+"'>"
                                            +"<input type='hidden' name='owner' value='"+owner+"'>"
                                            +"<input type='hidden' name='username' value='{{ Auth::user()->name }}'>"
                                            +"<input type='hidden' name='description' value='"+description+"'>"
                                            +"<input type='hidden' name='img' value='"+img+"'>"
                                            +"<input type='hidden' name='_token' value='{{ csrf_token() }}'>";
                                
                                html += playlistName+"</a></td><td><p style='font-size: 14px;'>"+description+"</p></td>"
                                        +"<td style='text-align:center;'><form action='/mypage/show/"+playlistId+"' method='POST'>"+button+"</form></td></tr>";
                            }else {
                                continue;
                            }
                        }
                        var list = document.getElementById("list");
                        list.innerHTML = html + "</table>";
                    }, false);
                }, false);
            }
            var handle=search(); 
        </script>
@endsection