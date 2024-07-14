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
    public $body;
    public $loadedMessages;
    public $paginate_var = 10;
    public $selectedConversation;

    protected $listeners = [
        'loadMore', 'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->loadMessages();
        $this->selectedConversation = Conversation::find($this->conversation_id);
        // $conversation_id = $this->conversation_id;
        // if ($conversation) {
        //     foreach ($conversation->messages as $message) {
        //         if ($message->receiver_id == auth()->id() && !$message->read) {
        //             $message->update(['read' => true]);
        //         }
        //     }
        // }
        // $this->selectedConversation = $conversation;
    }

    public function loadSenderName()
    {
        $conversation = Conversation::findOrFail($this->conversation_id);
        return $conversation->getReceiver()->name;
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

    // public function conversationSelected()
    // {
    //     $conversation_id = $this->conversation_id;
    //     $this->markAsRead($conversation_id);
    // }

    // public function markAsRead($conversationId)
    // {
    //     $conversation = Conversation::find($conversationId);
    //     if ($conversation) {
    //         foreach ($conversation->messages as $message) {
    //             if ($message->receiver_id == auth()->id() && !$message->read) {
    //                 $message->update(['read' => true]);
    //             }
    //         }
    //     }
    //     #refresh chatlist
    //     $this->emitTo('chat-list', 'refresh'); // Optional: to refresh the chat list if needed
    // }

    // public function markAsRead($conversationId)
    // {
    //     $conversation = Conversation::find($conversationId);
    //     $conversation->markMessagesAsRead(auth()->id());

    //     // Emit an event to refresh the chat list if necessary
    //     $this->emit('refresh');
    // }

    public function getListeners()
    {

        $auth_id = auth()->user()->id;

        return [

            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications',
            // 'echo-private:chat' => 'conversationUpdated', // New listener

        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatchBrowserEvent('scroll-bottom');
                $newMessage = Message::find($event['message_id']);

                #push message
                $this->loadedMessages->push($newMessage);

                #mark as read
                $newMessage->read_at = now();
                $newMessage->save();

                #broadcast 
                $this->selectedConversation->getReceiver()
                    ->notify(new MessageRead($this->selectedConversation->id));
            }
        }
    }

    public function loadMore(): void
    {


        #increment 
        $this->paginate_var += 10;

        #call loadMessages()

        $this->loadMessages();


        #update the chat height 
        $this->dispatchBrowserEvent('update-chat-height');
    }

    public function sendMessage()
    {

        $this->validate(['body' => 'required|string']);

        $createdMessage = Message::create([
            'conversation_id' => $this->conversation_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,
        ]);


        $this->reset('body');

        #scroll to bottom
        $this->dispatchBrowserEvent('scroll-bottom');

        #push the message
        $this->loadedMessages->push($createdMessage);

        #update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        #refresh chatlist
        $this->emitTo('chat-list', 'refresh');

        #broadcast
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));
    }

    public function render()
    {
        return view('livewire.chat-box');
        // $conversation = Conversation::find($this->selectedConversation);
        // $messages = $conversation ? $conversation->messages() : collect();

        // return view('livewire.chat.chat-box', ['messages' => $messages]);
    }
}
