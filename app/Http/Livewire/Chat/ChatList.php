<?php

namespace App\Http\Livewire\Chat;

use App\Events\ConversationSelected;
use App\Models\Conversation;
use App\Notifications\MessageRead;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $query;
    protected $listeners = ['refresh' => '$refresh', 'refreshChatList' => '$refresh'];

    public function selectConversation($conversationId)
    {
        event(new ConversationSelected($conversationId));
        // broadcast(new ConversationSelected($conversationId))->toOthers();
        $this->selectedConversation = $conversationId;
        $this->emit('conversationSelected', $conversationId);

        $conversation = Conversation::find($conversationId);
        if ($conversation && $conversation->unreadMessagesCount(auth()->id()) > 0) {
            $conversation->markMessagesAsRead(auth()->id());
            $conversation->getReceiver()->notify(new MessageRead($conversation)); // Notify Sender
        }

        session()->put('selectedConversation', $conversationId);
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
        return redirect(route('chat.index'));
    }



    public function render()
    {
        $user = auth()->user();
        return view('livewire.chat.chat-list', [
            'conversations' => $user->conversations()->latest('updated_at')->get()
        ]);
    }
}
