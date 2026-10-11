@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Transaksi> $transactions */
    $showPelanggan = $showPelanggan ?? true;
    $statusColors = [
        'diterima' => 'blue',
        'diproses' => 'yellow',
        'selesai' => 'green',
        'diambil' => 'gray',
    ];
@endphp

<x-ui.card :title="$title">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 whitespace-nowrap text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <th class="px-3 py-2">ID</th>
                    @if ($showPelanggan)<th class="px-3 py-2">Pelanggan</th>@endif
                    <th class="px-3 py-2">Layanan</th>
                    <th class="px-3 py-2">Berat</th>
                    <th class="px-3 py-2">Total Harga</th>
                    <th class="px-3 py-2">Tanggal Masuk</th>
                    <th class="px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($transactions as $transaksi)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2.5 font-mono text-xs text-slate-600">#{{ $transaksi->id }}</td>
                        @if ($showPelanggan)
                            <td class="px-3 py-2.5 font-medium text-slate-900">{{ $transaksi->pelanggan->nama ?? '—' }}</td>
                        @endif
                        <td class="px-3 py-2.5">{{ $transaksi->layanan->nama_layanan ?? '—' }}</td>
                        <td class="px-3 py-2.5">{{ number_format((float) $transaksi->berat_kg, 2, ',', '.') }} kg</td>
                        <td class="px-3 py-2.5">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="px-3 py-2.5">{{ $transaksi->tanggal_masuk?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td class="px-3 py-2.5">
                            <x-ui.badge :color="$statusColors[$transaksi->status] ?? 'gray'">
                                {{ ucfirst($transaksi->status ?? '—') }}
                            </x-ui.badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $showPelanggan ? 7 : 6 }}" class="px-3 py-8 text-center text-slate-400">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-ui.card>
