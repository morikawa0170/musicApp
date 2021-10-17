@extends('layouts.app')

@section('content')
   <div class="container">
      <iframe class="d-block mt-3 mx-auto w-50" style="height: 500px" src="https://open.spotify.com/embed/playlist/{{ $playlist->playlistId }}" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
      <br>
      <div class="row justify-content-center">
         <form  class="col-auto " action="{{ route('comments.store') }}" method="post">
            @csrf
            <input type="text" name="msg" size=80>
            <input type="hidden" name="id" value="{{ $playlist->id }}">
            <input type="submit" value="送信">
         </form>
      </div>
      <div id="chat" class=""></div>
   <script>
      function recvAJAX() {
         var ajax = new XMLHttpRequest();
         ajax.open("get", "/comments/{{ $playlist->id }}/commentAjax");
         ajax.responseType = "json";
         ajax.send(); // 通信させます。
         ajax.addEventListener("load", function(){ // loadイベントを登録します。
            var msg = document.getElementById("chat");
            var json = this.response;
            for(var i = json.length-1; i >= 0; i--) {
               var str = json[i].created_at;
               var replace = str.replace('T',' ');
               var created_at = replace.substr(0,19);
               var name = json[i].name;
               var comment = json[i].comment;
               var id = json[i].id;
               var form = '<form action="{{ route('comments.delete', $playlist->id) }}" method="post">'
                           +"<input type='submit' class='btn btn-link' value='削除' onClick='return delCheck()'>"
                           +"<input type='hidden' name='id' value='"+ id +"'>"
                           +"<input type='hidden' name='title' value="+ "{{ $playlist->id }}" +">"
                           +"<input type='hidden' name='_token' value='{{ csrf_token() }}'>"
                           +"</form>";
               var myPost=    "<div class='card mb-2 mt-3 w-75 mx-auto'>" 
                                 +"<div class='card-body pl-5 pb-1 d-flex bd-highlight'>" 
                                    +'<p class="mr-auto p-1 bd-highlight">'+comment +'</p>'
                                    +'<p class="p-1 bd-highlight">'+ name +' /</p>'
                                    +'<p class="text-muted p-1 bd-highlight">'+ created_at +'</p>'
                                    +form
                                 +"</div>"
                              +"</div>";
               var otherPost = "<div class='card mb-2 mt-3 w-75 mx-auto'>" 
                                 +"<div class='card-body pl-5 pb-1 d-flex bd-highlight'>" 
                                    +'<p class="mr-auto p-1 bd-highlight">'+comment +'</p>'
                                    +'<p class="p-1 bd-highlight">'+ name +' /</p>'
                                    +'<p class="text-muted p-1 bd-highlight">'+ created_at +'</p>'
                                 +"</div>"
                              +"</div>";         
               if ("{{ Auth::user()->name }}" == name){
                  msg.innerHTML += myPost;
               }else{
                  msg.innerHTML += otherPost;
               }
            }
         }, false);
      }
      // var handle = setInterval(recvAJAX, 200);
      var handle = recvAJAX();
      function delCheck(){
         var result = confirm('本当に削除してよろしいですか？');
         if(result == false){
            return false;
         }
      }
   </script>
@endsection