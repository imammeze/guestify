@extends('layouts.app')

@section('title', 'Form Buku Kunjungan')

@section('content')
<div class="min-h-screen">
    <div class="max-w-[1320px] mx-auto bg-white rounded-xl border p-10 shadow-sm mb-5">
        <h1 class="text-center text-3xl font-semibold mb-2">Form Buku Kunjungan Tamu</h1>
        <p class="text-center text-gray-500 mb-6">
            Silakan isi data diri Anda dan posisikan diri Anda untuk ambil foto
        </p>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <span class="text-xl mr-2">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Alert Error --}}
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <p class="font-bold mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="guestForm" action="{{ route('tamu.store') }}" method="POST">
            @csrf
            <input type="hidden" name="foto" id="fotoInput">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Kolom 1: Data Pengunjung --}}
                <div class="border shadow-md rounded-lg p-6 flex flex-col">
                    <h2 class="font-semibold mb-4 text-lg">Data Pengunjung</h2>
                    
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium mb-1">Nama Lengkap *</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            required
                            class="w-full border rounded-lg px-3 py-2 mt-1 @error('nama') border-red-500 @enderror"
                            value="{{ old('nama') }}"
                        >
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="instansi" class="block text-sm font-medium mb-1">
                            Asal Instansi / OPD / Umum
                        </label>
                        <input
                            type="text"
                            id="instansi"
                            name="instansi"
                            placeholder="Masukkan Asal Instansi / OPD / Umum"
                            class="w-full border rounded-lg px-3 py-2 mt-1 @error('instansi') border-red-500 @enderror"
                            value="{{ old('instansi') }}"
                        >
                        @error('instansi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nomor" class="block text-sm font-medium mb-1">
                            Nomor Yang Bisa Dihubungi
                        </label>
                        <input
                            type="text"
                            id="nomor"
                            name="nomor"
                            placeholder="Masukkan nomor Handphone / Telepon"
                            class="w-full border rounded-lg px-3 py-2 mt-1 @error('nomor') border-red-500 @enderror"
                            value="{{ old('nomor') }}"
                        >
                        @error('nomor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keperluan" class="block text-sm font-medium mb-1">Keperluan</label>
                        <textarea
                            id="keperluan"
                            name="keperluan"
                            placeholder="Masukkan keperluan kunjungan"
                            class="w-full border rounded-lg px-3 py-2 mt-1 h-32 resize-none @error('keperluan') border-red-500 @enderror"
                        >{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button
                        type="submit"
                        class="mt-6 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                    >
                        Simpan Data
                    </button>

                    <button
                        type="reset"
                        class="mt-3 w-full py-2 bg-white border border-blue-600 hover:bg-blue-100 text-blue-600 hover:text-blue-700 rounded-lg transition"
                    >
                        Reset Form
                    </button>
                </div>

                {{-- Kolom 2: Kamera Live --}}
                <div class="text-start border shadow-md rounded-lg p-6 mb-auto">
                    <h2 class="font-semibold mb-4 text-lg">Kamera Live</h2>
                    <p class="text-gray-600 text-sm mb-1">Posisikan diri agar terlihat jelas</p>

                    <video
                        id="camera"
                        autoplay
                        playsinline
                        muted
                        class="mx-auto border rounded-lg w-full aspect-video bg-black object-cover"
                    ></video>

                    <button
                        type="button"
                        id="captureBtn"
                        class="mt-6 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                    >
                        Tangkap Foto
                    </button>
                    
                    <button
                        type="button"
                        id="retakeBtn"
                        class="mt-3 w-full py-2 bg-white border border-blue-600 hover:bg-blue-100 text-blue-600 hover:text-blue-700 rounded-lg transition"
                    >
                        Ambil Ulang
                    </button>
                </div>

                {{-- Kolom 3: Preview Gambar --}}
                <div class="text-start border shadow-md rounded-lg p-6 mb-auto">
                    <h2 class="font-semibold mb-4 text-lg">Preview Gambar</h2>
                    <p class="text-gray-600 text-sm mb-1">Preview Foto</p>

                    <canvas
                        id="preview"
                        class="mx-auto border rounded-lg bg-gray-200 object-cover w-full aspect-video"
                    ></canvas>
                    
                    <div
                        id="photoStatus"
                        class="hidden mt-6 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center"
                    >
                        <span class="text-lg mr-2">✅</span>
                        <span>Foto berhasil diambil!</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const video = document.getElementById("camera");
    const canvas = document.getElementById("preview");
    const ctx = canvas.getContext("2d");
    const captureBtn = document.getElementById("captureBtn");
    const guestForm = document.getElementById('guestForm');
    const fotoInput = document.getElementById('fotoInput');
    const retakeBtn = document.getElementById("retakeBtn");
    const photoStatus = document.getElementById('photoStatus');

    // Aktifkan kamera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "user"
            }
        })
        .then(stream => {
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                canvas.width = video.videoWidth || 1280;
                canvas.height = video.videoHeight || 720;
            };
        })
        .catch(err => {
            console.error("Akses kamera ditolak:", err);
            const cameraContainer = video.parentElement;
            cameraContainer.innerHTML = `
                <div class="w-full aspect-video border rounded-lg bg-gray-100 flex flex-col items-center justify-center text-red-500 p-4">
                    <span class="text-4xl mb-2">❌</span>
                    <p class="font-semibold">Kamera tidak dapat diakses</p>
                    <p class="text-sm text-center">Pastikan browser memiliki izin untuk mengakses kamera.</p>
                </div>`;
            captureBtn.disabled = true;
            retakeBtn.disabled = true;
            captureBtn.classList.add('opacity-50', 'cursor-not-allowed');
            retakeBtn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }

    // Tangkap foto
    captureBtn.addEventListener("click", () => {
        if (!video.videoWidth || !video.videoHeight) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const photoData = canvas.toDataURL('image/png');
        fotoInput.value = photoData;

        photoStatus.classList.remove('hidden');
        captureBtn.textContent = '✅ Foto Tersimpan';
    });

    // Ambil ulang
    retakeBtn.addEventListener("click", () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        fotoInput.value = '';
        photoStatus.classList.add('hidden');
        captureBtn.textContent = 'Tangkap Foto';
    });

    // Validasi submit: wajib ada foto
    guestForm.addEventListener('submit', (e) => {
        if (!fotoInput.value) {
            e.preventDefault();
            alert('Harap ambil foto terlebih dahulu!');
        }
    });

    // Reset form juga reset foto
    guestForm.addEventListener('reset', () => {
        setTimeout(() => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            fotoInput.value = '';
            photoStatus.classList.add('hidden');
            captureBtn.textContent = 'Tangkap Foto';
        }, 50);
    });
</script>
@endpush
