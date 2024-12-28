<?php

namespace App\Http\Controllers;

use App\Jobs\SendCreatedTicketEmailJob;
use App\Jobs\SendSupportTicketReplyEmailJob;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportTicketCreatedMail;

class TicketController extends Controller
{
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

        $referenceNumber = 'TICKET-' . strtoupper(Str::random(6)) . '-' . substr(md5(uniqid('', true)), 0, 8);

        $ticket = SupportTicket::create([
            'reference_number' => $referenceNumber,
            'customer_name' => $request->customerName,
            'problem_description' => $request->problemDescription,
            'customer_email' => $request->customerEmail,
            'customer_phone_number' => $request->customerPhoneNumber,
        ]);

        if($ticket) {
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

        if ($perPage == 0) {
            $tickets = SupportTicket::orderBy('created_at', 'desc')->with('ticketReply')->get();

            $response = [
                'current_page' => 1,
                'data' => $tickets,
                'last_page' => 1,
                'prev_page_url' => null,
                'next_page_url' => null,
                'path' => url('api/tickets'),
            ];
        } else {
            $response = SupportTicket::orderBy('created_at', 'desc')->with('ticketReply')->paginate($perPage);
        }

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

            if($ticketReply) {
                SupportTicket::where('id', $requestParams['ticketId'])->update(['status' => $requestParams['status']]);
                $ticket = SupportTicket::with('ticketReply')->where('id', $requestParams['ticketId'])->first();
                SendSupportTicketReplyEmailJob::dispatch($ticket->toArray());
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket reply created successfully',
                'data' => $ticketReply,
            ], 201);
        }
    }
}
