<?php

namespace App\Interfaces;

interface TicketInterface
{
   public function createSupportTicket($request);
   public function getTickets($request, $perPage);
   public function getTicketsById($ticketId);
   public function getTicketsByReferenceNumber($referenceNumber);
   public function updateSupportTicketStatus($ticketId , $status);
   public function createSupportTicketReply($request, $agentId);
}


