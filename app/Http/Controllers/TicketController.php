<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Validator;

class TicketController extends Controller
{
    public function closeTicket($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::closeTicket($token);

        return view('close-ticket-browser');
    }

    public function resetPicture($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::resetPicture($token);

        return view('resetting-data-close-browser');
    }

    public function resetName($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::resetName($token);

        return view('resetting-data-close-browser');
    }

    public function resetDescription($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::resetDescription($token);

        return view('resetting-data-close-browser');
    }

    public function resetPassword($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::resetPassword($token);

        return view('resetting-data-close-browser');
    }

    public function resetNickname($token){

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        TicketService::resetNickname($token);

        return view('resetting-data-close-browser');
    }
}
