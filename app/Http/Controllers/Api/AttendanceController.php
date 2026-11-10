<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * Record a student's entry or exit via RFID tap
     */
    public function store(Request $request)
    {
        // 1. Validate the payload sent by the RFID hardware
        $request->validate([
            'rfid_uid' => 'required|string',
            'type' => 'required|in:masuk,keluar', // Must be 'masuk' or 'keluar'
        ]);

        // 2. Look up the student associated with this RFID card
        $user = User::where('rfid_uid', $request->rfid_uid)->first();

        // If the card is not registered in the database, reject the scan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unrecognized RFID card.'
            ], 404);
        }

        // 3. Record the attendance event
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'scan_time' => Carbon::now(),
        ]);

        // 4. Return a success message back to the scanner 
        // (which could trigger a green light or beep on the hardware)
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded.',
            'user' => $user->name,
            'type' => $attendance->type
        ], 201);
    }
}