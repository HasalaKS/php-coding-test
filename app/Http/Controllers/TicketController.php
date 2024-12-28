<?php

namespace App\Http\Controllers;

use App\Interfaces\TicketInterface;
use App\Jobs\SendCreatedTicketEmailJob;
use App\Jobs\SendSupportTicketReplyEmailJob;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    private $ticketInterface;

    public function __construct(TicketInterface $ticketInterface)
    {
        $this->ticketInterface = $ticketInterface;
    }

    public function createTicket(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'customerName' => 'required|string|max:100',
            'problemDescription' => 'required|string',
            'customerEmail' => 'required|email|max:100',
            'customerPhoneNumber' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket = $this->ticketInterface->createSupportTicket($request);

        if ($ticket) {
            SendCreatedTicketEmailJob::dispatch($ticket);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'data' => $ticket,
        ], 201);
    }

    public function getTickets(Request $request)
    {
        $requestParams = $request->all();
        $perPage = !empty($requestParams['perPage']) ? $requestParams['perPage'] : 0;
        $response = $this->ticketInterface->getTickets($request, $perPage);

        return response()->json($response, 200);
    }

    public function createReplyForTicket(Request $request)
    {
        $supportAgent = Auth::user();
        if ($supportAgent) {

            $validator = \Validator::make($request->all(), [
                'replyText' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $requestParams = $request->all();

            $ticketReply = TicketReply::create([
                'ticket_id' => $requestParams['ticketId'],
                'agent_id' => $supportAgent->id,
                'reply_text' => $requestParams['replyText'],
            ]);

            if ($ticketReply) {
                $this->ticketInterface->updateSupportTicketStatus($requestParams['ticketId'], $requestParams['status']);
                $ticket = $this->ticketInterface->getTicketsById($requestParams['ticketId']);
                SendSupportTicketReplyEmailJob::dispatch($ticket->toArray());
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket reply created successfully',
                'data' => $ticketReply,
            ], 201);
        }
    }

    public function searchTicket($referenceNumber)
    {
        $ticket = $this->ticketInterface->getTicketsByReferenceNumber($referenceNumber);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        return response()->json([
            'ticket' => $ticket
        ]);
    }
}
