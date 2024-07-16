<div class="max-w-6xl w-full mx-auto my-16 px-0">
    <div class="flex flex-col items-center sm:items-start mx-4 sm:ml-3 mb-3">
        <h2 class="font-bold py-3 ml-1">ChatDug Users</h2>
        <input type="text" placeholder="Search user" maxlength="100"
            class="w-full bg-gray-200 border-0 outline-1 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg focus:outline-none"
            wire:model="searchTerm">
    </div>

    <div class="grid md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-5 p-2 ">
        @foreach ($users as $key => $user)
            {{-- child --}}
            <div class="w-full bg-white border border-gray-200 rounded-lg p-2 md:p-5 shadow flex justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    {{-- <img src="https://i.pravatar.cc/150{{ $key }}" alt="image"
                        class="w-24 h-24 mb-2.5 rounded-full shadow-lg"> --}}
                    <x-avatar class="shrink-0 w-14 h-14 md:w-24 md:h-24 mb-2.5 rounded-full shadow-lg" />
                    <div class="ml-5">
                        <h5 class="truncate text-sm md:text-lg font-medium text-gray-900 ">
                            {{ $user->name }}
                        </h5>
                        <span class="truncate text-xs md:text-sm text-gray-500">{{ $user->email }} </span>
                    </div>
                </div>
                <div class="flex flex-col shrink-0 justify-center gap-y-3">
                    <x-secondary-button wire:model.debounce.2000ms wire:click="addToChat({{ $user->id }})">
                        Add Chat
                    </x-secondary-button>
                    <x-primary-button wire:click="message({{ $user->id }})">
                        Message
                    </x-primary-button>
                </div>
            </div>
        @endforeach
    </div>

    @if (session()->has('addChatSuccess'))
        @foreach (session('addChatSuccess') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('addChatSuccess', '{{ $loop->index }}');
            }, 1500)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-sky-300 text-sky-950 rounded-sm text-sm sm:text-md font-bold px-4 py-3"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>

                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif

    @if (session()->has('alreadyAddedAlert'))
        @foreach (session('alreadyAddedAlert') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('alreadyAddedAlert', '{{ $loop->index }}');
            }, 1500)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-amber-300 text-amber-950 rounded-sm text-sm sm:text-md font-bold px-4 py-3"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>

                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif
</div>
