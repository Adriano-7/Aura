<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\File;
use App\Models\VoteComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Access\AuthorizationException;


class CommentController extends Controller{
    public function index(Request $request) {
        if (!$request->has('eventId')) {
            return response()->json(['message' => 'Event id is required'], 400);
        }
        $event_id = $request->query('eventId');
        if (!is_numeric($event_id)) {
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
        if (!Event::find($event_id)) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $comments = Comment::event_comments($event_id);
        return response()->json([
            'eventId' => $event_id,
            'comments' => $comments->toArray()
        ]);
    }

    public function show(int $id) {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Comment id must be an integer'], 400);
        }
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json(['comment' => $comment], 200);
    }

    public function destroy(int $id) {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Comment id must be an integer'], 400);
        }
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        try {
            $this->authorize('delete', $comment);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to delete this comment'], 403);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function store(Request $request){
        if (!$request->has('event_id')) {
            return response()->json(['message' => 'Event id is required'], 400);
        }
        $request->validate([
            'text' => 'required',
            'file' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,pdf|max:2048'
        ]);
        $event = Event::findOrFail($request->event_id);

        try{
            $this->authorize('store', [Comment::class, $event, $request->user()]);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to comment on this event'], 403);
        }

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->text = $request->text;
        $comment->event_id = $request->event_id;

        if ($request->hasFile('file')) {
            $fileRequest = $request->file('file');
            $filename = time() . "-" . $fileRequest->getClientOriginalName();
           
            $file = new File();
            $file->file_name = $filename;
            $file->comment_id = $comment->id;
            $file->save();
            $comment->file_id = $file->id;
            $fileRequest->move(public_path('assets/comments'), $filename);

            $comment->save();
            return response()->json(['message' => 'Comment added successfully', 'comment' => $comment, 'author' => Auth::user(), 'file' => $file], 200);
        }

        $comment->save();
        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment, 'author' => Auth::user()], 200);
    }

    public function update(Request $request, int $id) {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Comment id must be an integer'], 400);
        }
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        try {
            $this->authorize('update', $comment);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to update this comment'], 403);
        }
        $comment->text = $request->text;
        $comment->save();
        return response()->json(['message' => 'Comment updated successfully', 'text' => $comment->text]);
    }

    public function addLike(int $id){
        if(!is_numeric($id)){
            return response()->json(['error'=> 'Comment id must be an integer'],400);
        }
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['error'=> 'Comment not found'],404);
        }
        try{
            $this->authorize('addVote', [VoteComment::class, $comment]);
        } catch (AuthorizationException $e){
            return response()->json(['error'=> $e->getMessage()],403);
        }
        VoteComment::addVote($id, Auth::user()->id, true);
        return response()->json(['success' => true]);
    }

    public function addDislike(int $id){
        if(!is_numeric($id)){
            return response()->json(['error'=> 'Comment id must be an integer'],400);
        }
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['error'=> 'Comment not found'],404);
        }
        try{
            $this->authorize('addVote', [VoteComment::class, $comment]);
        } catch (AuthorizationException $e){
            return response()->json(['error'=> $e->getMessage()],403);
        }
        VoteComment::addVote($id, Auth::user()->id, false);
        return response()->json(['success' => true]);
    }

    public function removeVote(int $id){
        if(!is_numeric($id)){
            return response()->json(['error'=> 'Comment id must be an integer'],400);
        }
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['error'=> 'Comment not found'],404);
        }
        try{
            $this->authorize('deleteVote', [VoteComment::class, $comment]);
        } catch (AuthorizationException $e){
            return response()->json(['error'=> $e->getMessage()],403);
        }
        VoteComment::deleteVote($id, Auth::user()->id);
        return response()->json(['success'=> true]);
    }
}
