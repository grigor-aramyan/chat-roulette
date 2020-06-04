<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

class FindPairController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $current_user = auth()->user();

        $idle_users = DB::table('users')
            ->where('mode', 'IDLE')
            ->where('purpose', $current_user->purpose)
            ->where('status', 'WANT_TO_CONNECT')
            ->get();

        $idle_users_except_current = array();
        foreach ($idle_users as $iu) {
            if ($iu->id != $current_user->id) {
                array_push($idle_users_except_current, $iu);
            }
        }

        $found_user = NULL;
        if (count($idle_users_except_current) == 0) {
            return response()->json([ 'msg' => 'nobody found matching your preferences' ], 200);

        } else if (count($idle_users_except_current) == 1) {
            $found_user = $idle_users_except_current[0];
            unset($found_user->password);

            return response()->json($found_user);
        } else {
            $found_user = array_rand($idle_users_except_current);
            unset($found_user->password);

            return response()->json($found_user);
        }

    }
}
