<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserConnection;
use App\User;

class UserConnectionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $connected_to_id = $request->connected_to;

        $connected_to_user = User::find($connected_to_id);
        if (!$connected_to_user) {
            return response()->json([ 'msg' => 'user to connect to not found' ], 404);
        }

        $current_user = auth()->user();

        if ($current_user->id == $connected_to_id) {
            return response()->json([ 'msg' => 'can\'t connect to yourself' ], 400);
        }

        $check_one = UserConnection::whereRaw('connected_from = ? and connected_to = ?', array($current_user->id, $connected_to_id))->first();
        $check_two = UserConnection::whereRaw('connected_from = ? and connected_to = ?', array($connected_to_id, $current_user->id))->first();
        
        if ($check_one || $check_two) {
            return response()->json([ 'msg' => 'already connected' ], 200);
        }

        $stored = UserConnection::create([
            'connected_from' => $current_user->id,
            'connected_to' => $connected_to_id
        ]);

        if ($stored) {
            return response()->json([ 'msg' => 'connected' ], 201);
        } else {
            return response()->json([ 'msg' => 'error' ], 500);
        }
    }
}
