<div class="w-full overflow-hidden">
    <div class="border-b flex flex-col overflow-y-scroll no-scrollbar grow h-full">

        {{-- header --}}
        <header class="w-full inset-x-0 flex py-2 top-0 z-10 bg-white border-b ">
            <div class="flex w-full items-center px-4 gap-2 md:gap-5">
                <a class="shrink-0 md:hidden" href="{{ route('index') }}">
                    {{-- Arrow --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="stroke-red-600 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>
                </a>

                {{-- avatar --}}
                <div class="shrink-0 flex items-center justify-center">
                    <x-avatar class="h-8 w-8 sm:w-9 sm:h-9" />
                </div>

                <h6 class="font-bold truncate"> {{ $this->loadSenderName() }} </h6>
            </div>
        </header>

        {{-- body --}}
        <main x-data @scroll="if($el.scrollTop<=0){window.livewire.emit('loadMore');}" id="chat-box"
            class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto">
            <div class="fixed inset-x-1/2 top-20 md:top-24" wire:loading.delay>
                <svg class="w-8 h-8 md:w-10 md:h-10 mr-3 -ml-1 text-red-500 animate-spin"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            @if ($loadedMessages)

                @php
                    $previousMessage = null;
                @endphp

                @foreach ($loadedMessages as $key => $message)
                    {{-- keep track of the previous message --}}

                    @if ($key > 0)
                        @php
                            $previousMessage = $loadedMessages->get($key - 1);
                        @endphp
                    @endif


                    <div wire:key="{{ time() . $key }}" @class([
                        'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
                        'ml-auto' => $message->sender_id === auth()->id(),
                    ])>

                        {{-- avatar --}}

                        <div @class([
                            'shrink-0',
                            'invisible' => $previousMessage?->sender_id == $message->sender_id,
                            'hidden' => $message->sender_id === auth()->id(),
                        ])>

                            <x-avatar />
                        </div>
                        {{-- messsage body --}}

                        <div @class([
                            'flex flex-wrap text-[15px]  rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]',
                            'rounded-bl-none border  border-gray-200/40 ' => !(
                                $message->sender_id === auth()->id()
                            ),
                            'rounded-br-none bg-red-500/80 text-white' =>
                                $message->sender_id === auth()->id(),
                        ])>

                            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
                                {{ $message->body }}
                            </p>

                            <div class="ml-auto flex gap-2">
                                <p @class([
                                    'text-xs ',
                                    'text-gray-500' => !($message->sender_id === auth()->id()),
                                    'text-white' => $message->sender_id === auth()->id(),
                                ])>
                                    {{ $message->created_at->format('g:i a') }}
                                </p>

                                {{-- message status , only show if message belongs to auth --}}

                                @if ($message->sender_id === auth()->id())
                                    <div x-data="{ markAsRead: @json($message->isRead()) }">

                                        {{-- double ticks --}}
                                        <span x-cloak x-show="markAsRead" @class('text-gray-100')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                        </span>

                                        {{-- single ticks --}}
                                        <span x-show="!markAsRead" @class('text-gray-100')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </main>

        {{-- send message input box  --}}

        <footer class="shrink-0 z-10 bg-white inset-x-0">

            <div class=" p-2 border-t">

                <form wire:submit.prevent="sendMessage" method="POST" autocapitalize="off">
                    @csrf

                    <input type="hidden" autocomplete="false" style="display:none">

                    <div class="grid grid-cols-12">
                        <input id="bodyInput" oninput="checkIfBodyIsEmpty()" wire:model.lazy="body" type="text"
                            autocomplete="off" autofocus placeholder="Message" maxlength="1700"
                            class="col-span-10 md:col-span-11 bg-gray-200 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg focus:outline-none">

                        <button id = "sendButton" disabled
                            class="bg-gray-200 text-black translate ease-in-out duration-150 col-span-2 md:col-span-1 rounded-md mx-2 shadow-sm"
                            type='submit'>
                            <span class="items-center justify-center flex flex-row ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                @error('body')
                    <p> {{ $message }} </p>
                @enderror

            </div>
        </footer>
    </div>
    {{-- @if (!trim($body)) disabled @endif
                            class="{{ !trim($body);
                                ? 'bg-gray-200 text-black translate ease-in-out duration-150 col-span-2 md:col-span-1 rounded-md mx-2 shadow-sm'
                                : 'bg-red-500 text-gray-200 hover:bg-red-600 focus:outline-none focus:ring-0 focus:ring-offset-0 translate ease-in-out duration-150 col-span-2 md:col-span-1 rounded-md mx-2 shadow-sm' }}" --}}

    <script>
        // var messageBody = '';


        function checkIfBodyIsEmpty() {
            var bodyInput = document.getElementById('bodyInput');
            var sendButton = document.getElementById('sendButton');

            // Check if the Input body is empty
            if (!bodyInput.value.trim()) {
                sendButton.disabled = true;
                sendButton.className =
                    "bg-gray-200 text-black translate ease-in-out duration-150 col-span-2 md:col-span-1 rounded-md mx-2 shadow-sm";
            } else {
                sendButton.disabled = false;
                sendButton.className =
                    "bg-red-500 text-gray-200 hover:bg-red-600 focus:outline-none focus:ring-0 focus:ring-offset-0 translate ease-in-out duration-150 col-span-2 md:col-span-1 rounded-md mx-2 shadow-sm";
            }

            // Check if Enter key is pressed and the button is disabled
            // if (event.key === 'Enter' && sendButton.disabled) {
            //     event.preventDefault(); // Prevent form submission
            //     return false; // Exit function
            // }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var chatBox = document.getElementById('chat-box');
            var height = chatBox.scrollHeight;

            // Scroll to the bottom initially
            chatBox.scrollTop = chatBox.scrollHeight;

            Livewire.hook('message.processed', (message, component) => {
                if (component.el.id === 'chat-box') {
                    var newHeight = chatBox.scrollHeight;
                    var oldHeight = height;

                    // If a new message was added by the current user, scroll to the bottom
                    if (message.updateQueue[0].type === 'callMethod' && message.updateQueue[0].payload
                        .method === 'sendMessage') {
                        chatBox.scrollTop = newHeight;
                    } else {
                        chatBox.scrollTop = newHeight - oldHeight;
                    }
                    height = newHeight;
                }
            });

            window.addEventListener('scrollToBottom', function() {
                chatBox.scrollTop = chatBox.scrollHeight;
            });

            window.addEventListener('updateChatHeight', function() {
                var newHeight = chatBox.scrollHeight;
                var oldHeight = height;
                chatBox.scrollTop = newHeight - oldHeight;
                height = newHeight;
            });

            Echo.private('users.{{ Auth()->user()->id }}')
                .notification((notification) => {
                    Livewire.emit('handleNotification', notification);
                });

            Echo.private('conversation.{{ $this->conversation_id }}')
                .listen('ConversationSelected', (e) => {
                    Livewire.emit('handleConversationRead', e);
                });
        });
    </script>

</div>
