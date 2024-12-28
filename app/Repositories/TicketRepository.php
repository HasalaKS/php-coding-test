<?php

namespace App\Repositories;

use App\Interfaces\TicketInterface;
use App\Models\SupportTicket;
use Illuminate\Support\Str;

class TicketRepository implements TicketInterface
{
    public function createSupportTicket($request)
    {
        $referenceNumber = 'TICKET-' . strtoupper(Str::random(6)) . '-' . substr(md5(uniqid('', true)), 0, 8);

        return SupportTicket::create([
            'reference_number' => $referenceNumber,
            'customer_name' => $request->customerName,
            'problem_description' => $request->problemDescription,
            'customer_email' => $request->customerEmail,
            'customer_phone_number' => $request->customerPhoneNumber,
        ]);
    }

    public function getTickets($request, $perPage)
    {
        if ($perPage == 0) {
            $tickets = SupportTicket::orderBy('created_at', 'desc')->with('ticketReply')->get();

            return [
                'current_page' => 1,
                'data' => $tickets,
                'last_page' => 1,
                'prev_page_url' => null,
                'next_page_url' => null,
                'path' => url('api/tickets'),
            ];
        } else {
            return SupportTicket::orderBy('created_at', 'desc')->with('ticketReply')->paginate($perPage);
        }
    }

    public function getTicketsById($ticketId)
    {
        return SupportTicket::with('ticketReply')->where('id', $ticketId)->first();
    }

    public function getTicketsByReferenceNumber($referenceNumber)
    {
        return SupportTicket::where('reference_number', $referenceNumber)
            ->with(['ticketReply' => function ($query) {
                $query->with('repliedAgent');
            }])
            ->first();
    }

    public function updateSupportTicketStatus($ticketId, $status)
    {
        SupportTicket::where('id', $ticketId)->update(['status' => $status]);
    }
}
