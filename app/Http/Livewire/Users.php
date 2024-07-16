<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public $searchTerm;

    public function addToChat($user_id)
    {
        $authenticated_id = auth()->id();

        # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticated_id, $user_id) {
            $query->where('sender_id', $authenticated_id)
                ->where('receiver_id', $user_id);
        })
            ->orWhere(function ($query) use ($authenticated_id, $user_id) {
                $query->where('sender_id', $user_id)
                    ->where('receiver_id', $authenticated_id);
            })->first();

        $messages = session()->get('addChatSuccess', []);

        if (!$existingConversation) {
            # If Conversation doesn't exist, create new conversation
            Conversation::create([
                'sender_id' => $authenticated_id,
                'receiver_id' => $user_id,
            ]);

            $messages[] = 'Successfully added to your chats.';
            session()->put('addChatSuccess', $messages);

        } else {

            $messages[] = 'User already exists in your chats.';
            session()->put('alreadyAddedAlert', $messages);

        }

        // dd($existingConversation);
    }

    public function removeSuccessMessage($sessionMessage, $index)
    {
        $messages = session()->get($sessionMessage, []);
        if (isset($messages[$index])) {
            unset($messages[$index]);
            session()->put($sessionMessage, array_values($messages));
        }
    }
    public function message($user_id)
    {

        //  $createdConversation =   Conversation::updateOrCreate(['sender_id' => auth()->id(), 'receiver_id' => $userId]);

        $authenticated_id = auth()->id();

        # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticated_id, $user_id) {
            $query->where('sender_id', $authenticated_id)
                ->where('receiver_id', $user_id);
        })
            ->orWhere(function ($query) use ($authenticated_id, $user_id) {
                $query->where('sender_id', $user_id)
                    ->where('receiver_id', $authenticated_id);
            })->first();

        if ($existingConversation) {
            # Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }

        # Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticated_id,
            'receiver_id' => $user_id,
        ]);

        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }


    public function render()
    {
        // return view('livewire.users', [
        //     'users' => User::where('id', '!=', auth()->id())->get()
        // ]);
        // session()->forget(['addChatSuccess', 'alreadyAddedAlert']);

        return view('livewire.users', [
            'users' => User::where('id', '!=', auth()->id())
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })->get()
        ]);
    }
}
