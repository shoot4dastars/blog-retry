<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(Request $request){
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
            'post_id' => ['required', 'exists:posts,id'],
        ]);

        Comment::create([
           'body' => $validated['body'],
           'post_id' => $validated['post_id'],
           'user_id' => $request->user()->id,
        ]);

        return redirect()->back()->with('success','Comment added successfully');
    }

    public function update(Request $request, Comment $comment){

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $comment->update([
            'body' => $validated['body'],
        ]);

        return redirect()->back()->with('success','Comment updated successfully');
    }

    public function destroy(Comment $comment){
        $comment->delete();

        return redirect()->back()->with('success','Comment deleted successfully');
    }
}
