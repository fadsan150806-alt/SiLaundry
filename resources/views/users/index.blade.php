@extends('layouts.app')

@section('title', 'Pengguna')

@php
    $roleColors = ['admin' => 'purple', 'kasir' => 'blue', 'pelanggan' => 'green'];
    $isFiltered = $search !== '' || $selectedRole !== '';
@endphp

@section('content')
    <x-ui.page-header title="Pengguna">
        <x-slot:actions>
            <x-ui.button :href="route('users.create')">+ Tambah</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div x-data="{ open: false, action: '', name: '' }">
        <x-ui.card>
            <form method="GET" action="{{ route('users.index') }}" class="mb-4 flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / email / username…"
                       class="form-input max-w-xs">
                <select name="role" class="form-input w-auto" onchange="this.form.submit()">
                    <option value="">Semua peran</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
                @if ($isFiltered)
                    <x-ui.button variant="secondary" :href="route('users.index')">Reset</x-ui.button>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 whitespace-nowrap text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Username</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2">Peran</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $item)
                            @php $isSelf = $item->is(auth()->user()); @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 py-2.5 font-medium text-slate-900">
                                    {{ $item->name }}
                                    @if ($isSelf)<x-ui.badge class="ml-1">Anda</x-ui.badge>@endif
                                </td>
                                <td class="px-3 py-2.5 font-mono text-xs text-slate-600">{{ $item->username ?? '—' }}</td>
                                <td class="px-3 py-2.5 text-slate-600">{{ $item->email }}</td>
                                <td class="px-3 py-2.5">
                                    <x-ui.badge :color="$roleColors[$item->role] ?? 'gray'">{{ $item->roleLabel() }}</x-ui.badge>
                                </td>
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('users.edit', $item) }}" class="text-brand-700 hover:underline">Ubah</a>
                                        @unless ($isSelf)
                                            <button type="button" class="text-red-600 hover:underline"
                                                    data-delete-user="{{ $item->email }}"
                                                    @click="open = true; action = @js(route('users.destroy', $item)); name = @js($item->name)">
                                                Hapus
                                            </button>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-slate-400">
                                    {{ $isFiltered ? 'Pengguna tidak ditemukan.' : 'Belum ada pengguna.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-4">{{ $users->links() }}</div>
            @endif
        </x-ui.card>

        {{-- Konfirmasi hapus --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4"
             @keydown.escape.window="open = false">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl" @click.outside="open = false">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-slate-900">Hapus pengguna?</h3>
                        <p class="truncate text-sm text-slate-600" x-text="name"></p>
                    </div>
                </div>
                <form method="POST" :action="action" class="flex justify-end gap-2">
                    @csrf
                    @method('DELETE')
                    <x-ui.button variant="secondary" x-on:click="open = false">Batal</x-ui.button>
                    <x-ui.button type="submit" variant="danger" id="confirmDeleteSubmit">Hapus</x-ui.button>
                </form>
            </div>
        </div>
    </div>
@endsection
