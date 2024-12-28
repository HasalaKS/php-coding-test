<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $tickets = SupportTicket::orderBy('created_at', 'desc')->get();

            $response = [
                'current_page' => 1,
                'data' => $tickets,
                'last_page' => 1,
                'prev_page_url' => null,
                'next_page_url' => null,
                'path' => url('api/tickets'),
            ];
        } else {
            $response = SupportTicket::orderBy('created_at', 'desc')->paginate($perPage);
        }

        return response()->json($response, 200);
    }
}
