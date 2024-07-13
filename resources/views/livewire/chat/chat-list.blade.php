<div x-data="{
    type: 'all',
    query: @entangle('query'),
    markAsRead(conversationId) {
        @this.call('markAsRead', conversationId);
    }
}" x-init="setTimeout(() => {
    conversationElement = document.getElementById('conversation-' + query);

    //scroll to the element
    if (conversationElement) {
        conversationElement.scrollIntoView({ 'behavior': 'smooth' });
    }
}, 200);

Echo.private('users.{{ Auth()->User()->id }}')
    .notification((notification) => {
        if (notification['type'] == 'App\\Notifications\\MessageRead' || notification['type'] == 'App\\Notifications\\MessageSent') {

            window.Livewire.emit('refresh');
        }
    });" class="flex flex-col transition-all h-full overflow-hidden">

    <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">

        <div class="border-b justify-between flex items-center pb-2">

            <div class="flex items-center gap-2">
                <h5 class="font-extrabold text-2xl">Chats</h5>
            </div>

            <button>

                <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                </svg>

            </button>

        </div>

        {{-- Filters --}}

        <div class="flex gap-3 items-center overflow-x-scroll no-scrollbar p-2 bg-white">

            <button @click="type='all'" :class="{ 'bg-blue-100 border-0 text-black': type == 'all' }"
                class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border ">
                All
            </button>
            <button @click="type='deleted'" :class="{ 'bg-blue-100 border-0 text-black': type == 'deleted' }"
                class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border ">
                Deleted
            </button>

        </div>

    </header>


    <main class=" overflow-y-scroll no-scrollbar overflow-hidden grow  h-full relative " style="contain:content">

        {{-- chatlist  --}}

        <ul x-data="{ query: @entangle('query') }" class="p-2 grid w-full spacey-y-2">

            @if ($conversations)

                @foreach ($conversations as $key => $conversation)
                    {{-- <div x-data="{ error: null }" x-on:click.away="error = null"> --}}
                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
                        @click.away="error = null" wire:click="selectConversation('{{ $conversation->id }}')"
                        class="py-3 hover:bg-gray-50 rounded-xl transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2 {{ $conversation->id == session()->get('selectedConversation') ? 'bg-gray-100/70' : '' }}">
                        <a href="#" class="shrink-0">
                            <x-avatar src="https://i.pravatar.cc/150{{ $key }}" />
                        </a>

                        <aside class="grid grid-cols-12 w-full">

                            <a href="{{ route('chat', $conversation->id) }}"
                                class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">

                                {{-- name and date  --}}
                                <div class="flex justify-between w-full items-center">

                                    <h6 class="truncate font-medium tracking-wider text-gray-900">
                                        {{ $conversation->getReceiver()->name }}
                                    </h6>

                                    <small
                                        class="text-gray-700">{{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}
                                    </small>

                                </div>

                                {{-- Message body --}}


                                <div class="flex gap-x-2 items-center">

                                    @if ($conversation->messages?->last()?->sender_id == auth()->id())
                                        @if ($conversation->isLastMessageReadByUser())
                                            {{-- double tick  --}}
                                            <span class="text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check-circle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                </svg>
                                            </span>
                                        @else
                                            {{-- single tick  --}}
                                            <span class="text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                    <path
                                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                                </svg>
                                            </span>
                                        @endif
                                    @endif




                                    <p class="grow truncate text-sm font-[100]">
                                        {{ $conversation->messages?->last()?->body ?? ' ' }}
                                    </p>

                                    {{-- unread count --}}
                                    @if ($conversation->unreadMessagesCount() > 0)
                                        <span
                                            class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-blue-500 text-white">
                                            {{ $conversation->unreadMessagesCount() }}
                                        </span>
                                    @endif


                                </div>



                            </a>

                            {{-- Dropdown --}}

                            <div class="col-span-1 flex flex-col text-center my-auto">

                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button>

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor"
                                                class="bi bi-three-dots-vertical w-7 h-7 text-gray-700"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            </svg>


                                        </button>
                                    </x-slot>

                                    <x-slot name="content">

                                        <div class="w-full p-1">

                                            <button
                                                class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-gray-100 transition-all duration-150 ease-in-out focus:outline-none focus:bg-gray-100">

                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-person-circle"
                                                        viewBox="0 0 16 16">
                                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                        <path fill-rule="evenodd"
                                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                                    </svg>
                                                </span>

                                                View Profile

                                            </button>
                                            <button onclick="confirm('Are you sure?')||event.stopImmediatePropagation()"
                                                wire:click="deleteByUser('{{ encrypt($conversation->id) }}')"
                                                class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-gray-100 transition-all duration-150 ease-in-out focus:outline-none focus:bg-gray-100">

                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-trash-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg>
                                                </span>

                                                Delete

                                            </button>



                                        </div>

                                    </x-slot>
                                </x-dropdown>



                            </div>


                        </aside>
                    </li>
                    {{-- <div style="display:none" x-show="error" class="text-red-500">
                            An error occurred. Please try again. (Optional: Provide a link to report the issue)
                        </div> --}}
                    {{-- </div> --}}
                @endforeach
            @else
            @endif

        </ul>

    </main>
</div>
