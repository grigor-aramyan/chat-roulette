<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UpdateUsersModeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // TODO: find way to ensure supplied users are really paired
        // before updating there mode to PENDING

        $paired_from_user = User::find($request->pairedFrom);
        $paired_to_user = User::find($request->pairedTo);

        $mode = $request->mode;

        if (($mode != 'IDLE') && ($mode != 'PENDING') && ($mode != 'CONNECTED')) {
            return response()->json([ 'msg' => 'don\'t have specified mode' ], 400);
        }

        if (!$paired_from_user) {
            return response()->json([ 'msg' => 'paired from user not found' ], 404);
        } else if (!$paired_to_user) {
            return response()->json([ 'msg' => 'paired to user not found' ], 404);
        } else {
            $updated = User::whereIn('id', [
                $request->pairedFrom,
                $request->pairedTo
            ])->update([ 'mode' => $mode ]);

            if ($updated > 0) {
                return response()->json([ 'msg' => 'updated' ], 200);
            } else {
                return response()->json([ 'msg' => 'something weird happened' ], 500);
            }
        }
    }
}
