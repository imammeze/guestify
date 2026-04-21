<x-guest-layout>
    <div class="flex min-h-fit py-16 items-center justify-center bg-white px-12 rounded-3xl shadow-lg">
        <div class="w-full max-w-sm">
            {{-- Logo --}}
            <div class="flex justify-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-lg overflow-hidden">
                    <img src="{{ asset('assets/images/logo-banyumas.png') }}" class="object-contain" />
                </div>
                <div class="w-12 h-12 rounded-lg overflow-hidden">
                    <img src="{{ asset('assets/images/logo-telkom.png') }}" class="object-contain" />
                </div>
            </div>

            {{-- Header --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-2">
                    Selamat Datang, Admin!
                </h1>
                <p class="text-sm text-gray-600">Silakan masukkan NIP terlebih dahulu</p>
            </div>

            @if ($errors->has('nip'))
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('nip') }}
                </div>
            @endif
            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                    <input
                        id="nip"
                        name="nip"
                        type="text"
                        inputmode="numeric"
                        required
                        autofocus
                        placeholder="Masukkan NIP anda"
                        class="w-full border border-gray-300 rounded-md p-3 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white rounded-md font-medium py-3 hover:bg-blue-700 transition"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
