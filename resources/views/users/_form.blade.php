@php
    /** @var \App\Models\User $user */
    $isEdit = $user->exists;
    $isSelf = $isEdit && $user->is(auth()->user());
    $selectedRole = old('role', $user->role ?? '');
@endphp

<div class="divide-y divide-slate-100" x-data="{ role: @js($selectedRole) }">
    <x-form.input name="name" label="Nama Lengkap" :value="$user->name" required />
    <x-form.input name="username" label="Nama Pengguna" :value="$user->username" required />
    <x-form.input name="email" label="Email" type="email" :value="$user->email" required />

    <x-form.field name="role" label="Peran" required>
        <select id="role" name="role" x-model="role" @disabled($isSelf)
                @class(['form-input', 'ring-red-400' => $errors->has('role')])>
            <option value="">— Pilih —</option>
            @foreach ($roles as $value => $label)
                <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($isSelf)
            {{-- Select yang disabled tidak ikut terkirim, jadi peran dikirim lewat input tersembunyi. --}}
            <input type="hidden" name="role" value="{{ $user->role }}">
        @endif
    </x-form.field>

    <x-form.input name="password" label="Kata Sandi" type="password" :required="! $isEdit"
                  :placeholder="$isEdit ? 'Kosongkan bila tidak diubah' : null" autocomplete="new-password" />
    <x-form.input name="password_confirmation" label="Konfirmasi Kata Sandi" type="password" :required="! $isEdit"
                  autocomplete="new-password" />

    <div x-show="role === 'pelanggan'" x-cloak class="divide-y divide-slate-100">
        <x-form.input name="no_hp" label="Nomor HP" :value="$user->pelanggan?->no_hp" required />
        <x-form.input name="alamat" label="Alamat" :value="$user->pelanggan?->alamat" required />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-ui.button type="submit">Simpan</x-ui.button>
    <x-ui.button variant="secondary" :href="route('users.index')">Batal</x-ui.button>
</div>
