<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends Model
{
    use SoftDeletes;

    protected $table = 'ticket_replies';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_id', 'agent_id', 'reply_text'
    ];

    protected $dates = ['deleted_at'];
}
