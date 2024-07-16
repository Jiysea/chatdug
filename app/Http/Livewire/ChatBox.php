<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ChatBox extends Component
{
    public $conversation_id;
    // public $height = 0;
    // public $markAsRead = false;
    public $body;
    public $loadedMessages;
    public $paginate_var = 10;
    public $selectedConversation;

    protected $listeners = [
        'refresh' => '$refresh',
        'loadMore',
        'updateChatHeight',
        'handleNotification',
        'scrollToBottom'
    ];

    public function getListeners()
    {
        $user_id = Auth()->id();

        return [
            "loadMore",
            "echo-private:users.{$user_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNotification',
            "echo:conversation.{conversation_id},ConversationSelected" => "handleConversationRead",
        ];
    }

    public function mount($conversation_id)
    {
        $this->selectedConversation = Conversation::find($this->conversation_id);
        $this->conversation_id = $conversation_id;
        $this->loadMessages();
    }

    public function handleConversationRead($payload)
    {
        // Check if the event is for the current conversation and the reader is not the current user
        if ($payload['conversation_id'] == $this->conversationId && $payload['receiver_id'] != auth()->id()) {
            // Update the messages state
            $this->loadMessages();
        }
    }

    public function handleNotification($notification)
    {
        if ($notification['type'] == MessageSent::class) {
            if ($notification['conversation_id'] == $this->selectedConversation->id) {
                $newMessage = Message::find($notification['message_id']);

                #push message
                $this->loadedMessages->push($newMessage);

                #mark as read
                $newMessage->read_at = now();
                $newMessage->save();

                # Scrolls to Bottom
                $this->emitTo('chat-list', 'refresh');
                $this->dispatchBrowserEvent('scrollToBottom');

                #broadcast 
                $this->selectedConversation->getConversationUser()
                    ->notify(new MessageRead($this->selectedConversation->id));
            }
        }
    }

    public function updateChatHeight()
    {
        $this->dispatchBrowserEvent('updateChatHeight');
    }

    public function loadSenderName()
    {
        $conversation = Conversation::findOrFail($this->conversation_id);
        return $conversation->getConversationUser()->name;
    }

    public function loadMessages()
    {
        $userId = auth()->id();
        // #get count
        $count = Message::where('conversation_id', $this->conversation_id)
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })
                    ->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->whereNull('receiver_deleted_at');
                    });
            })
            ->count();

        #skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->conversation_id)
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })
                    ->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->whereNull('receiver_deleted_at');
                    });
            })
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();

        return $this->loadedMessages;
    }

    public function loadMore()
    {
        #increment 
        $this->paginate_var += 10;

        #call loadMessages()
        $this->loadMessages();

        #update the chat height 
        $this->dispatchBrowserEvent('updateChatHeight');
    }

    public function sendMessage()
    {
        $this->validate(['body' => 'required|string']);

        $createdMessage = Message::create([
            'conversation_id' => $this->conversation_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getConversationUser()->id,
            'body' => $this->body,
        ]);

        #push the message and clear the body
        $this->loadedMessages->push($createdMessage);
        $this->reset('body');

        #update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        #scroll to bottom
        $this->emitTo('chat-list', 'refresh');
        $this->dispatchBrowserEvent('scrollToBottom');

        #broadcast
        $this->selectedConversation->getConversationUser()
            ->notify(
                new MessageSent(
                    Auth()->User(),
                    $createdMessage,
                    $this->selectedConversation,
                    $this->selectedConversation->getConversationUser()->id
                )
            );
    }

    public function render()
    {
        return view('livewire.chat-box', [
            'loadedMessages' => $this->loadedMessages,
        ]);
    }
}
