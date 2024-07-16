<?php

namespace App\Http\Livewire;

use App\Events\ConversationSelected;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Livewire\Component;

class ChatList extends Component
{
    public $conversation_id;
    public $conversations;
    // public $selectedConversation;

    protected $listeners = ['refresh' => '$refresh', 'refreshChatList', 'selectConversation'];

    public function mount()
    {
        // $this->selectConversation($this->conversation_id);
        $this->conversations = Conversation::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->get();
    }

    public function selectConversation($conversation_id)
    {

        $this->conversation_id = $conversation_id;

        // Mark the conversation as read
        $conversation = Conversation::find($conversation_id);
        if ($conversation) {
            // Mark the messages as read (update your logic as needed)
            $conversation->messages()->where('receiver_id', auth()->id())->update(['read_at' => now()]);
            $conversation->getConversationUser()->notify(new MessageRead($conversation)); // Notify Sender

            // Broadcast the event
            broadcast(new ConversationSelected($conversation_id, auth()->id()));
        }
    }

    public function refreshChatList($notification)
    {
        if ($notification['type'] == MessageSent::class || $notification['type'] == MessageRead::class) {
            if ($notification['conversation_id'] == $this->conversation_id) {

                $this->emitTo('chat-list', 'refresh');
            }
        }
    }

    public function removeSuccessMessage($sessionMessage, $index)
    {
        $messages = session()->get($sessionMessage, []);
        if (isset($messages[$index])) {
            unset($messages[$index]);
            session()->put($sessionMessage, array_values($messages));
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

        $messages = session()->get('deleteChatSuccess', []);
        $messages[] = 'Chat conversation has been deleted.';
        session()->put('deleteChatSuccess', $messages);

        return redirect(route('index'));
    }

    public function render()
    {
        return view('livewire.chat-list', [
            'conversations' => $this->conversations,
        ]);
    }
}
