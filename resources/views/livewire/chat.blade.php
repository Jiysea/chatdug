<div
    class="fixed flex h-full bg-white border md:shadow-sm overflow-hidden inset-0 md:top-16 md:inset-0 m-auto md:h-[90%]">

    <div
        class="hidden md:block relative w-full md:w-[550px] lg:w-[600px] xl:w-[650px] overflow-y-auto no-scrollbar h-full">
        <livewire:chat-list :conversation_id="$conversation_id" wire:key="'chat-list'.$conversation_id">
    </div>

    <div class="grid w-full border-l h-full relative overflow-y-auto no-scrollbar" style="contain:content">
        <livewire:chat-box :conversation_id="$conversation_id" wire:key="'chat-box'.$conversation_id">
    </div>

</div>
