<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'sender_id',
        'receiver_id',
        'conversation_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
    ];


    protected $dates = ['read_at', 'receiver_deleted_at', 'sender_deleted_at'];


    /* relationship */

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }


    public function isRead(): bool
    {

        return $this->read_at != null;
    }

    // E2EE Implementation
    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = Crypt::encryptString($value);
    }

    public function getBodyAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
