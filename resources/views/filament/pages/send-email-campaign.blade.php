<x-filament-panels::page>

    <form wire:submit.prevent="send">

        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button
                type="button"
                wire:click="send"
                wire:confirm="You are about to send emails to {{ $recipientCount }} user(s). This cannot be undone. Continue?"
                size="lg"
                icon="heroicon-o-paper-airplane"
                color="primary"
            >
                Send Campaign to {{ $recipientCount }} User(s)
            </x-filament::button>
        </div>

    </form>

</x-filament-panels::page>
