<div class="mt-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    @foreach($columns as $column)
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            {{ ucfirst(str_replace('_', ' ', $column)) }}
                        </th>
                    @endforeach
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($data as $row)
                    <tr>
                        @foreach($columns as $column)
                            <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100 whitespace-pre-line">
                                @if(str_contains($column, '.'))
                                    @php
                                        $parts = explode('.', $column);
                                        $value = $row;
                                        foreach($parts as $part) {
                                            if (is_numeric($part)) {
                                                $value = $value[$part] ?? '';
                                            } else {
                                                $value = $value->$part ?? '';
                                            }
                                        }
                                        echo $value;
                                    @endphp
                                @else
                                    {{ $row->$column }}
                                @endif
                            </td>
                        @endforeach
                        <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100 whitespace-nowrap">
                            <div class="flex gap-2">
                                <flux:modal.trigger name="edit-{{ $row->getKey() }}">
                                    <flux:button variant="ghost" size="sm" icon="pencil" class="cursor-pointer"></flux:button>
                                </flux:modal.trigger>
                                
                                <flux:modal.trigger name="delete-{{ $row->getKey() }}">
                                    <flux:button variant="danger" size="sm" icon="trash" class="cursor-pointer"></flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @foreach($data as $row)
        @livewire('edit-row-modal', ['model' => $model, 'rowId' => $row->getKey()], key('edit-'.$row->getKey()))
        @livewire('delete-confirmation-modal', ['model' => $model, 'rowId' => $row->getKey()], key('delete-'.$row->getKey()))
    @endforeach
</div>