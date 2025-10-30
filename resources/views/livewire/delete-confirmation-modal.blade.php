<flux:modal name="delete-{{ $rowId }}" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Hapus Data</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</flux:subheading>
        </div>

        <form wire:submit="delete" class="space-y-6">
            <div class="flex">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger">Hapus</flux:button>
            </div>
        </form>
    </div>
</flux:modal>

