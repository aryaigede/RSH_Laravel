@extends('dashboard.layout')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between gap-6 flex-wrap">
        <div class="space-y-2">
            <flux:heading size="xl">Hai, {{ $user->nama }} 👋</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan hewan peliharaan dan kunjungan dokter.</p>
        </div>
        <div class="flex gap-3">
            <div class="rounded-lg bg-emerald-600/10 text-emerald-600 dark:text-emerald-300 px-4 py-2 text-sm font-semibold">
                {{ $pets->count() }} hewan
            </div>
            <div class="rounded-lg bg-indigo-600/10 text-indigo-600 dark:text-indigo-300 px-4 py-2 text-sm font-semibold">
                {{ $rekamMedis->count() }} rekam medis
            </div>
            <div class="rounded-lg bg-amber-600/10 text-amber-600 dark:text-amber-300 px-4 py-2 text-sm font-semibold">
                {{ $temuDokter->count() }} jadwal temu dokter
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Profil Pemilik</h3>
            <div class="space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
                <div class="flex justify-between"><span>Nama</span><span class="font-semibold">{{ $user->nama }}</span></div>
                <div class="flex justify-between"><span>Email</span><span class="font-semibold">{{ $user->email }}</span></div>
                <div class="flex justify-between"><span>No. WA</span><span class="font-semibold">{{ $pemilik->no_wa ?? '-' }}</span></div>
                <div class="flex justify-between"><span>Alamat</span><span class="font-semibold text-right">{{ $pemilik->alamat ?? '-' }}</span></div>
            </div>
        </div>

        <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Hewan Peliharaan</h3>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($pets as $pet)
                    <div class="py-3 flex justify-between text-sm text-zinc-800 dark:text-zinc-200">
                        <div>
                            <div class="font-semibold">{{ $pet->nama ?? 'Tanpa nama' }}</div>
                            <div class="text-xs text-zinc-500">ID: {{ $pet->idpet }} @if($pet->rasHewan) · Ras: {{ $pet->rasHewan->nama_ras }} @endif</div>
                        </div>
                        <div class="text-xs text-zinc-500">Jenis kelamin: {{ $pet->jenis_kelamin ?? '-' }}</div>
                    </div>
                @empty
                    <div class="py-6 text-sm text-zinc-500">Belum ada hewan terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Jadwal Temu Dokter</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-zinc-500">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">No Urut</th>
                        <th class="py-2 pr-4">Hewan</th>
                        <th class="py-2 pr-4">Dokter</th>
                        <th class="py-2 pr-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-zinc-800 dark:text-zinc-200">
                    @forelse($temuDokter as $td)
                        <tr>
                            <td class="py-2 pr-4">{{ \Carbon\Carbon::parse($td->waktu_daftar)->format('Y-m-d') }}</td>
                            <td class="py-2 pr-4">{{ $td->no_urut }}</td>
                            <td class="py-2 pr-4">{{ optional($td->pet)->idpet }} - {{ optional($td->pet)->nama }}</td>
                            <td class="py-2 pr-4">{{ optional(optional($td->roleUser)->user)->nama ?? '-' }}</td>
                            <td class="py-2 pr-4">{{ $td->status === 'F' ? 'Selesai' : 'Baru' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-zinc-500">Belum ada jadwal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Rekam Medis</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-zinc-500">
                        <th class="py-2 pr-4">ID</th>
                        <th class="py-2 pr-4">Hewan</th>
                        <th class="py-2 pr-4">Dokter</th>
                        <th class="py-2 pr-4">Diagnosa</th>
                        <th class="py-2 pr-4">Anamnesa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-zinc-800 dark:text-zinc-200">
                    @forelse($rekamMedis as $rm)
                        <tr>
                            <td class="py-2 pr-4">{{ $rm->idrekam_medis }}</td>
                            <td class="py-2 pr-4">{{ optional($rm->pet)->idpet }} - {{ optional($rm->pet)->nama }}</td>
                            <td class="py-2 pr-4">{{ optional(optional($rm->dokter)->user)->nama ?? '-' }}</td>
                            <td class="py-2 pr-4">{{ $rm->diagnosa ?? '-' }}</td>
                            <td class="py-2 pr-4">{{ $rm->anamnesa ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-zinc-500">Belum ada rekam medis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection