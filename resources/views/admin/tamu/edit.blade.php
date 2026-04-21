@extends('layouts.app')

@section('title', 'Edit Tamu')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold text-blue-600 mb-4">Edit Data Tamu</h1>

    <form method="POST" action="{{ route('tamu.update', $tamu->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium mb-1">Nama:</label>
            <input type="text" name="nama" value="{{ old('nama', $tamu->nama) }}" required 
                   class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Instansi:</label>
            <input type="text" name="instansi" value="{{ old('instansi', $tamu->instansi) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Nomor:</label>
            <input type="text" name="nomor" value="{{ old('nomor', $tamu->nomor) }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Keperluan:</label>
            <textarea name="keperluan" rows="4" required 
                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old('keperluan', $tamu->keperluan) }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
