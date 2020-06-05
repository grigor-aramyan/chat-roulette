<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UpdateUserModeController extends Controller
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

        $current_user->mode = $request->mode;
        $updated = $current_user->save();

        if ($updated) {
            return response()->json([
                'msg' => 'updated',
                'mode' => $request->mode
            ], 200);
        } else {
            return response()->json([
                'msg' => 'failed',
                'mode' => $request->mode
            ], 400);
        }

    }
}
