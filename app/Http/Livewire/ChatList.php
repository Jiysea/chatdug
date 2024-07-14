<?php

namespace App\Http\Livewire;

use App\Events\ConversationSelected;
use App\Models\Conversation;
use App\Notifications\MessageRead;
use Livewire\Component;

class ChatList extends Component
{
    public $conversation_id;
    protected $listeners = ['refresh' => '$refresh', 'refreshChatList' => '$refresh', 'selectConversation'];

    public function mount()
    {
        $conversation_id = $this->conversation_id;
        event(new ConversationSelected($conversation_id));
        // $this->emit('conversationSelected', $conversation_id);

        $conversation = Conversation::find($conversation_id);
        if ($conversation && $conversation->unreadMessagesCount(auth()->id()) > 0) {
            $conversation->markMessagesAsRead(auth()->id());
            $conversation->getReceiver()->notify(new MessageRead($conversation)); // Notify Sender
        }
    }

    // public function markAsRead()
    // {
    //     $conversation_id = $this->conversation_id;
    //     $conversation = Conversation::find($conversation_id);
    //     if ($conversation && $conversation->unreadMessagesCount(auth()->id()) > 0) {
    //         $conversation->markMessagesAsRead(auth()->id());
    //         $conversation->getReceiver()->notify(new MessageRead($conversation)); // Notify Sender
    //     }
    // }

    public function selectConversation($conversation_id)
    {
        $this->conversation_id = $conversation_id;
        // dd($conversation_id);
        event(new ConversationSelected($conversation_id));
        // $this->emit('conversationSelected', $conversation_id);

        $conversation = Conversation::findOrFail($conversation_id);
        if ($conversation && $conversation->unreadMessagesCount(auth()->id()) > 0) {
            $conversation->markMessagesAsRead(auth()->id());
            $conversation->getReceiver()->notify(new MessageRead($conversation)); // Notify Sender
        }
    }

    public function deleteByUser($id)
    {
        $userId = auth()->id();
        $conversation = Conversation::find(decrypt($id));
        $conversation->messages()->each(function ($message) use ($userId) {

            if ($message->sender_id === $userId) {

                $message->update(['sender_deleted_at' => now()]);
            } elseif ($message->receiver_id === $userId) {

                $message->update(['receiver_deleted_at' => now()]);
            }
        });

        $receiverAlsoDeleted = $conversation->messages()
            ->where(function ($query) use ($userId) {

                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })->where(function ($query) use ($userId) {

                $query->whereNull('sender_deleted_at')
                    ->orWhereNull('receiver_deleted_at');
            })->doesntExist();



        if ($receiverAlsoDeleted) {

            $conversation->forceDelete();
        }
        return redirect(route('index'));
    }



    public function render()
    {
        $user = auth()->user();
        return view('livewire.chat-list', [
            'conversations' => $user->conversations()->latest('updated_at')->get()
        ]);
    }
}
