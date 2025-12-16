@extends('dashboard.layout')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between gap-6 flex-wrap">
        <div class="space-y-2">
            <flux:heading size="xl">Halo, Perawat {{ $user->nama }} 👋</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan tugas Anda.</p>
        </div>
        <div class="flex gap-3">
            <div class="rounded-lg bg-emerald-600/10 text-emerald-600 dark:text-emerald-300 px-4 py-2 text-sm font-semibold">
                {{ $patients->count() }} pasien
            </div>
            <div class="rounded-lg bg-indigo-600/10 text-indigo-600 dark:text-indigo-300 px-4 py-2 text-sm font-semibold">
                {{ $detailRekamMedis->count() }} detail rekam medis
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Profil Perawat</h3>
            <div class="space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
                <div class="flex justify-between"><span>Nama</span><span class="font-semibold">{{ $user->nama }}</span></div>
                <div class="flex justify-between"><span>Email</span><span class="font-semibold">{{ $user->email }}</span></div>
                <div class="flex justify-between"><span>No. HP</span><span class="font-semibold">{{ $profile->no_hp ?? '-' }}</span></div>
                <div class="flex justify-between"><span>Jenis Kelamin</span><span class="font-semibold">{{ $profile->jenis_kelamin ?? '-' }}</span></div>
                <div class="flex justify-between"><span>Pendidikan</span><span class="font-semibold">{{ $profile->pendidikan ?? '-' }}</span></div>
                <div class="flex justify-between"><span>Alamat</span><span class="font-semibold text-right">{{ $profile->alamat ?? '-' }}</span></div>
            </div>
        </div>

        <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Pasien</h3>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($patients as $pet)
                    <div class="py-3 flex justify-between text-sm text-zinc-800 dark:text-zinc-200">
                        <div>
                            <div class="font-semibold">{{ $pet->nama ?? 'Tanpa nama' }}</div>
                            <div class="text-xs text-zinc-500">ID: {{ $pet->idpet }} @if($pet->pemilik && $pet->pemilik->user) · Pemilik: {{ $pet->pemilik->user->nama }} @endif</div>
                        </div>
                        <div class="text-xs text-zinc-500">Jenis kelamin: {{ $pet->jenis_kelamin ?? '-' }}</div>
                    </div>
                @empty
                    <div class="py-6 text-sm text-zinc-500">Belum ada pasien.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Detail Rekam Medis</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-zinc-500">
                        <th class="py-2 pr-4">ID Detail</th>
                        <th class="py-2 pr-4">Hewan</th>
                        <th class="py-2 pr-4">Kode Tindakan</th>
                        <th class="py-2 pr-4">Detail</th>
                        <th class="py-2 pr-4">Rekam Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-zinc-800 dark:text-zinc-200">
                    @forelse($detailRekamMedis as $detail)
                        <tr>
                            <td class="py-2 pr-4">{{ $detail->iddetail_rekam_medis }}</td>
                            <td class="py-2 pr-4">{{ optional(optional($detail->rekamMedis)->pet)->idpet }} - {{ optional(optional($detail->rekamMedis)->pet)->nama }}</td>
                            <td class="py-2 pr-4">
                                @php
                                    $kt = $detail->kodeTindakanTerapi;
                                    $code = $kt->kode ?? '';
                                    $name = $kt->nama_tindakan ?? $kt->deskripsi_tindakan_terapi ?? '';
                                @endphp
                                {{ trim($code . ' - ' . $name, ' -') }}
                            </td>
                            <td class="py-2 pr-4">{{ $detail->detail ?? '-' }}</td>
                            <td class="py-2 pr-4">{{ optional($detail->rekamMedis)->idrekam_medis }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-zinc-500">Belum ada detail rekam medis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection