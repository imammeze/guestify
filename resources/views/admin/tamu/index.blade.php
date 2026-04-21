@extends('layouts.app')

@section('title', 'Daftar Tamu')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">Daftar Tamu</h1>

        <!-- Tombol Logout (sudah ada di navbar layout, bisa dihapus di sini kalau pakai layout) -->
        {{-- 
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Logout
            </button>
        </form> 
        --}}
    </div>

    <!-- Form Cari -->
    <form method="GET" action="{{ route('tamu.index') }}" class="flex mb-4 gap-2">
        <input type="text" name="search" placeholder="Cari nama / instansi / keperluan" 
               value="{{ request('search') }}"
               class="border border-gray-300 rounded px-3 py-2 w-full">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Cari
        </button>
    </form>

    <!-- Tombol Export PDF -->
    <div class="mb-4">
        <a href="{{ route('tamu.export.pdf') }}" target="_blank">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Export PDF
            </button>
        </a>
    </div>

    <!-- Tabel Data Tamu -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border-b">No</th>
                    <th class="py-2 px-4 border-b">Nama</th>
                    <th class="py-2 px-4 border-b">Instansi</th>
                    <th class="py-2 px-4 border-b">Keperluan</th>
                    <th class="py-2 px-4 border-b">Waktu</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataTamu as $index => $tamu)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border-b">{{ $tamu->nama }}</td>
                        <td class="py-2 px-4 border-b">{{ $tamu->instansi }}</td>
                        <td class="py-2 px-4 border-b">{{ $tamu->keperluan }}</td>
                        <td class="py-2 px-4 border-b">{{ $tamu->created_at->format('d-m-Y H:i') }}</td>
                        <td class="py-2 px-4 border-b space-x-2">
                            <a href="{{ route('tamu.edit', $tamu->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data tamu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
