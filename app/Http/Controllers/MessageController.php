<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;

class MessageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_user = auth()->user();

        $addressed_to = $request->addressed_to;

        $addressed_to_user = User::find($addressed_to);
        if (!$addressed_to_user) {
            return response()->json([ 'msg' => 'no recipient exists' ], 400);
        } else if (!$request->content) {
            return response()->json([ 'msg' => 'content required' ], 400);
        }

        $created = $current_user->messages()->create([
            'content' => $request->content,
            'addressed_to' => $request->addressed_to
        ]);

        if ($created) {
            return response()->json($created, 201);
        } else {
            return response()->json([ 'msg' => 'some error occured' ], 500);
        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json([ 'msg' => 'message not found' ], 404);
        }

        $current_user = auth()->user();
        if ($current_user->id != $message->user_id) {
            return response()->json([ 'msg' => 'only author of message can delete it' ], 400);
        }

        $destroyed = Message::destroy($id);
        if ($destroyed > 0) {
            return response()->json([ 'msg' => 'destroyed' ], 200);
        } else {
            return response()->json([ 'msg' => 'some error occured' ], 500);
        }
    }


    /**
     * Display a listing of the current and pairing users messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch($pairing_user_id)
    {
        $current_user = auth()->user();

        $pairing_user = User::find($pairing_user_id);
        if (!$pairing_user) {
            return response()->json([ 'msg' => 'no user found with this id' ], 400);
        }

        $messages = Message::where(function($query) use($current_user, $pairing_user_id)
        {
            $query->where('user_id', '=', $current_user->id)
                ->where('addressed_to', '=', $pairing_user_id);
        })
        ->orWhere(function($query) use ($current_user, $pairing_user_id)
        {
            $query->where('addressed_to', '=', $current_user->id)
                ->where('user_id', '=', $pairing_user_id);
        })
        ->orderBy('id', 'desc')
        ->take(100)
        ->get();



        return response()->json([ 'data' => $messages ]);
    }
}
