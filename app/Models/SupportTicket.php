<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use SoftDeletes;

    protected $table = 'support_tickets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'reference_number', 'customer_name', 'customer_email', 'customer_phone_number' , 'problem_description' , 'status'
    ];

    protected $dates = ['deleted_at'];

    public function ticketReply()
    {
        return $this->belongsTo(TicketReply::class,'id','ticket_id');
    }

}
