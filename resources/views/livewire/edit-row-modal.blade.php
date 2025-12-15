<flux:modal name="edit-{{ $rowId }}" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit Data</flux:heading>
            <flux:subheading>Edit data yang ada dalam tabel.</flux:subheading>
        </div>

        <form wire:submit="update" class="space-y-6">
            @php
                $idSelectFields = ['idpemilik', 'idras_hewan', 'idjenis_hewan', 'idkategori', 'idkategori_klinis', 'iduser', 'idrole', 'idpet', 'idkode_tindakan_terapi', 'idrekam_medis', 'idrole_user', 'dokter_pemeriksa', 'id_user'];
                $dateFields = ['waktu_daftar', 'tanggal_lahir', 'tanggal_kunjungan'];
            @endphp

            @foreach($fillable as $field)
                @if($field === 'deleted_by')
                    @continue
                @endif
                @if(in_array($field, $idSelectFields))
                    <flux:field>
                        <flux:label>{{ ucfirst(str_replace('_', ' ', $field)) }}</flux:label>
                        <flux:select wire:model="formData.{{ $field }}" placeholder="Pilih {{ ucfirst(str_replace('_', ' ', $field)) }}">
                            <option value="">-- Pilih --</option>
                            @php
                                if ($field === 'dokter_pemeriksa') {
                                    $options = \App\Models\RoleUser::whereHas('role', function ($q) {
                                        $q->where('nama_role', 'Dokter');
                                    })->with(['user', 'role'])->get();
                                } elseif ($field === 'idrole_user') {
                                    $options = \App\Models\RoleUser::whereHas('role', function ($q) {
                                        $q->where('nama_role', 'Dokter');
                                    })->with(['user', 'role'])->get();
                                } elseif ($field === 'deleted_by') {
                                    $options = \App\Models\User::whereHas('role', function ($q) {
                                        $q->whereIn('nama_role', ['Resepsionis', 'Administrator']);
                                    })->get();
                                } elseif ($field === 'id_user') {
                                    $options = \App\Models\User::all();
                                } else {
                                    $relatedModel = $this->getRelatedModel($field);
                                    $options = $relatedModel ? $relatedModel::all() : [];
                                }
                            @endphp
                            @foreach($options as $option)
                                @if($field === 'dokter_pemeriksa' || $field === 'idrole_user')
                                    <option value="{{ $option->idrole_user }}">{{ $option->user->nama ?? 'User '.$option->iduser }} ({{ $option->role->nama_role ?? 'role '.$option->idrole }})</option>
                                @elseif($field === 'idkode_tindakan_terapi')
                                    <option value="{{ $option->getKey() }}">{{ $option->kode ?? $option->getKey() }} - {{ $option->nama_tindakan ?? $this->getDisplayName($option) }}</option>
                                @elseif($field === 'id_user')
                                    <option value="{{ $option->id }}">{{ $option->nama ?? $option->email }}</option>
                                @else
                                    <option value="{{ $option->getKey() }}">{{ $option->getKey() }} - {{ $this->getDisplayName($option) }}</option>
                                @endif
                            @endforeach
                        </flux:select>
                        @error('formData.' . $field) <flux:error>{{ $message }}</flux:error> @enderror
                    </flux:field>
                @elseif($field === 'status')
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select wire:model="formData.status" placeholder="Pilih status">
                            <option value="{{ \App\Models\TemuDokter::STATUS_NEW }}">New</option>
                            <option value="{{ \App\Models\TemuDokter::STATUS_FINISHED }}">Finished</option>
                        </flux:select>
                        @error('formData.status') <flux:error>{{ $message }}</flux:error> @enderror
                    </flux:field>
                @elseif($field === 'jenis_kelamin')
                    <flux:field>
                        <flux:label>Jenis Kelamin</flux:label>
                        <flux:select wire:model="formData.jenis_kelamin" placeholder="Pilih jenis kelamin">
                            @if(str_contains($model, 'Pet'))
                                <option value="Jantan">Jantan</option>
                                <option value="Betina">Betina</option>
                            @else
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            @endif
                        </flux:select>
                        @error('formData.jenis_kelamin') <flux:error>{{ $message }}</flux:error> @enderror
                    </flux:field>
                @elseif(in_array($field, $dateFields))
                    <flux:field>
                        <flux:label>{{ ucfirst(str_replace('_', ' ', $field)) }}</flux:label>
                        <flux:input type="date" wire:model="formData.{{ $field }}" />
                        @error('formData.' . $field) <flux:error>{{ $message }}</flux:error> @enderror
                    </flux:field>
                @else
                    <flux:field>
                        <flux:label>{{ ucfirst(str_replace('_', ' ', $field)) }}</flux:label>
                        <flux:input wire:model="formData.{{ $field }}" placeholder="Masukkan {{ ucfirst(str_replace('_', ' ', $field)) }}" />
                        @error('formData.' . $field) <flux:error>{{ $message }}</flux:error> @enderror
                    </flux:field>
                @endif
            @endforeach

            @foreach($manyToManyRelationships as $relationship)
                <flux:field>
                    <flux:label>{{ ucfirst(str_replace('_', ' ', $relationship)) }}</flux:label>
                    <div class="space-y-2">
                        @php
                            $relatedModel = $this->getRelatedModelForManyToMany($relationship);
                            $options = $relatedModel ? $relatedModel::all() : [];
                        @endphp
                        @foreach($options as $option)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="formData.{{ $relationship }}" value="{{ $option->getKey() }}" class="mr-2">
                                {{ $option->getKeyName() ? $option->{$option->getKeyName()} : $option->id }} - {{ $this->getDisplayName($option) }}
                            </label>
                        @endforeach
                    </div>
                    @error('formData.' . $relationship) <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>
            @endforeach

            <div class="flex">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </div>
</flux:modal>

