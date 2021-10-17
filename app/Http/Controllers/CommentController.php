<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Playlist;
use Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->playlist_id = $request->id;
        $comment->comment = $request-> msg;
        $comment->name = Auth::user()->name;
        $comment-> save();

        return redirect()->route('comments.show',$request->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $playlist = Playlist::find($id);
        return view('comment.show',compact('playlist'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::find($id)->delete();

        return redirect()->route('comments.show',$id);
    }

    public function commentAjax($id)
    {
        $comments = Comment::where('playlist_id',$id)->get(); 
        
 	    if ($comments) {
 		    $msg = array();
 		    foreach($comments as $row) {
      		    $msg[] = array(
      		        'id'=>$row['id'],
     				'comment'=>$row['comment'],
     				'name'=>$row['name'],
     				'created_at'=>$row['created_at']
     			);
 		    }
 		    //chatをJsonで表示させるため、Jsonに変換して表示
 		    return response()->json($msg);
        }
    }
}
