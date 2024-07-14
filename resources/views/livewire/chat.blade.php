<div
    class="fixed h-full flex bg-white border lg:shadow-sm overflow-hidden inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    <div class="hidden md:grid w-full md:w-[450px] lg:w-[500px] xl:w-[600px] overflow-y-auto no-scrollbar h-full">
        <livewire:chat-list :conversation_id="$conversation_id">
    </div>

    <div class="grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        <livewire:chat-box :conversation_id="$conversation_id">
    </div>

</div>
