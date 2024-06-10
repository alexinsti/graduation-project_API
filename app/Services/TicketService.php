<?php

namespace App\Services;

use App\Models\Gymkhana;
use App\Models\Ticket;
use App\Models\User;

class TicketService
{

    public static function closeTicket($token): void
    {
        $ticket = Ticket::where('token', $token)->first();

        switch ($ticket->type) {
            case 1:
                CodeService::closeReportTicket($ticket->id_content);
                $ticket->delete();
                break;

            case 2:
                GymkhanaService::closeReportTicket($ticket->id_content);
                $ticket->delete();
                break;

            case 3:
                UserService::closeReportTicket($ticket->id_content);
                $ticket->delete();
                break;
        }
    }

    public static function resetPicture($token): void
    {
        $ticket = Ticket::where('token', $token)->first();

        switch ($ticket->type) {
            case 1:
                CodeService::resetPicture($ticket->id_content);
                break;

            case 2:
                GymkhanaService::resetPicture($ticket->id_content);
                break;

            case 3:
                UserService::resetPicture($ticket->id_content);
                break;
        }
    }

    public static function resetName($token): void
    {
        $ticket = Ticket::where('token', $token)->first();
        GymkhanaService::resetName($ticket->id_content);

    }

    public static function resetDescription($token): void
    {
        $ticket = Ticket::where('token', $token)->first();
        GymkhanaService::resetDescription($ticket->id_content);

    }

    public static function resetPassword($token): void
    {
        $ticket = Ticket::where('token', $token)->first();
        GymkhanaService::resetPassword($ticket->id_content);

    }

    public static function resetNickname($token): void
    {
        $ticket = Ticket::where('token', $token)->first();
        UserService::resetNickname($ticket->id_content);

    }

}
