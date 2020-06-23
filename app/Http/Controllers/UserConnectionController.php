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

        $already_connected = $this->is_connected($current_user->id, $connected_to_id);
        if ($already_connected) {
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


    /**
     * return connected user ids
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current_user = auth()->user();

        $current_user_connections = UserConnection::whereRaw('connected_from = ? or connected_to = ?', array($current_user->id, $current_user->id))->get();
        
        $connection_ids = array();
        foreach ($current_user_connections as $cuc) {
            if ($cuc->connected_from == $current_user->id) {
                array_push($connection_ids, $cuc->connected_to);
            } else {
                array_push($connection_ids, $cuc->connected_from);
            }
        }

        return response()->json([ 'connections' => $connection_ids ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $current_user = auth()->user();

        $uc = UserConnection::where('connected_from', $current_user->id)
            ->where('connected_to', $id)->first();

        if ($uc) {
            $deleted = UserConnection::destroy($uc->id);

            if ($deleted) {
                return response()->json([ 'msg' => 'deleted' ], 200);
            } else {
                return response()->json([ 'msg' => 'some weird error happened' ], 500);
            }
        } else {
            return response()->json([ 'msg' => 'connection not found' ], 404);
        }

    }


    /**
     * check if users with submitted ids are connected
     * 
     * @param int $id_1
     * @param int $id_2
     * @return boolean
     */
    private function is_connected($id_1, $id_2)
    {
        $check_one = UserConnection::whereRaw('connected_from = ? and connected_to = ?', array($id_1, $id_2))->first();
        $check_two = UserConnection::whereRaw('connected_from = ? and connected_to = ?', array($id_2, $id_1))->first();
        
        if ($check_one || $check_two) {
            return true;
        }

        return false;
    }

}
