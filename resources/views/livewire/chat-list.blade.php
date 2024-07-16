<div class="flex flex-col transition-all h-full overflow-hidden">

    <header class="px-3 z-10 bg-white top-0 w-full py-2 border-b">
        <div class="justify-between flex items-center">

            <div class="flex flex-row items-center gap-4 my-1">
                <h5 class="font-extrabold text-xl ml-2">Chats</h5>
            </div>

        </div>

    </header>

    <main class=" overflow-y-scroll no-scrollbar overflow-hidden grow  h-full relative " style="contain:content">

        {{-- chatlist  --}}

        <ul x-data="{ conversation_id: $wire.entangle('conversation_id') }" class="p-2 grid w-full spacey-y-2">

            @if ($conversations)
                @foreach ($conversations as $key => $conversation)
                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
                        class="my-1 py-3 hover:bg-gray-50 rounded-xl transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2 {{ $conversation->id == $conversation_id ? 'bg-gray-100/70' : '' }}">

                        <aside class="grid border-b grid-cols-12 w-full items-center justify-center">

                            <a wire:click="selectConversation({{ $conversation->id }})"
                                href="{{ route('chat', $conversation->id) }}"
                                class="col-span-11 pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">

                                {{-- name, avatar, and date  --}}
                                <div class="flex justify-between items-center mb-2">

                                    <span class="flex flex-row items-center gap-2 justify-center">
                                        {{-- <x-avatar src="https://i.pravatar.cc/150{{ $key }}" /> --}}
                                        <x-avatar class="shrink-0 mx-4 md:mx-0 h-6 w-6 lg:w-9 lg:h-9" />
                                        @if ($conversation->messages?->last()?->receiver_id == auth()->id())
                                            @if ($conversation->isLastMessageReadByUser())
                                                <h6
                                                    class="truncate font-[100] text-sm lg:text-base tracking-wider text-gray-900">
                                                    {{ $conversation->getConversationUser()->name }}
                                                </h6>
                                            @else
                                                <h6
                                                    class="truncate font-bold text-sm lg:text-base tracking-wider text-gray-900">
                                                    {{ $conversation->getConversationUser()->name }}
                                                </h6>
                                            @endif
                                        @else
                                            <h6 class="truncate font-[100] text-sm lg:text-base text-gray-900">
                                                {{ $conversation->getConversationUser()->name }}
                                            </h6>
                                        @endif
                                    </span>

                                    <small
                                        class="text-gray-700">{{ $conversation->messages?->last()?->created_at?->format('g:i') }}
                                    </small>

                                </div>

                                {{-- Message body --}}
                                <div class="flex gap-x-2 items-center">

                                    {{-- Sender View --}}
                                    @if ($conversation->messages?->last()?->sender_id == auth()->id())
                                        @if ($conversation->isLastMessageReadByUser())
                                            {{-- The Message --}}
                                            <p class="grow truncate text-sm ml-4 md:ml-0 text-gray-500 font-[100]">
                                                <span class="mr-1">You:</span>
                                                {{ $conversation->messages?->last()?->body ?? ' ' }}
                                            </p>

                                            {{-- double tick  --}}
                                            <span class="text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check-circle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                </svg>
                                            </span>
                                        @else
                                            {{-- The Message --}}
                                            <p class="grow truncate text-sm ml-4 md:ml-0 text-gray-500 font-[100]">
                                                <span class="mr-1">You:</span>
                                                {{ $conversation->messages?->last()?->body ?? ' ' }}
                                            </p>

                                            {{-- single tick  --}}
                                            <span class="text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                    <path
                                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                                </svg>
                                            </span>
                                        @endif

                                        {{-- Receiver View --}}
                                    @elseif ($conversation->messages?->last()?->receiver_id == auth()->id())
                                        @if ($conversation->isLastMessageReadByUser())
                                            <p class="grow truncate text-sm ml-4 md:ml-0 text-gray-500 font-[100]">
                                                {{ $conversation->messages?->last()?->body ?? ' ' }}
                                            </p>
                                        @else
                                            <p class="grow truncate text-sm ml-4 md:ml-0 text-red-800 font-bold">
                                                {{ $conversation->messages?->last()?->body ?? ' ' }}
                                            </p>
                                        @endif
                                    @endif

                                    {{-- unread count --}}
                                    @if ($conversation->unreadMessagesCount() > 0)
                                        <span
                                            class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-red-500 text-white">
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
                                                class="bi bi-three-dots-vertical w-4 h-4 text-gray-700"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    {{-- Options --}}
                                    <x-slot name="content">
                                        <div class="w-full p-1">
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
                @endforeach
            @else
            @endif

        </ul>

    </main>

    @if (session()->has('deleteChatSuccess'))
        @foreach (session('deleteChatSuccess') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('deleteChatSuccess', '{{ $loop->index }}');
            }, 1500)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-rose-300 text-rose-950 rounded-sm text-sm sm:text-md font-bold px-4 py-3"
                role="alert">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                        clip-rule="evenodd" />
                </svg>


                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            Echo.private('users.{{ Auth()->user()->id }}')
                .notification((notification) => {
                    Livewire.emit('refreshChatList', notification);
                });
        });
    </script>
</div>
