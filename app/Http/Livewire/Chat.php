<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;

class Chat extends Component
{

    public $conversation_id;

    public function mount($query)
    {
        $conversation = Conversation::findOrFail($query);
        if (
            !$conversation ||
            !($conversation->sender_id === auth()->id() ||
                !$conversation->receiver_id === auth()->id())
        ) {
            // User is not a participant, redirect to general chat
            return redirect()->route('index');
        }

        #pass the conversation to the conversation_id
        $this->conversation_id = $query;

        #mark message belonging to receiver as read 
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
