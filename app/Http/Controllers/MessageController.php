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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
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
