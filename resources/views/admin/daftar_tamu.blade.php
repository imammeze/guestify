@extends('layouts.app')

@section('title', 'Daftar Tamu - Admin Buku Tamu')

@section('content')
<section id="page" class="bg-white border border-gray-100 rounded-xl shadow-sm p-8">

    <form method="GET" action="{{ route('tamu.index') }}">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gray-50 p-4 rounded-lg mb-6">
            
            <div class="flex-shrink-0">
                <h2 class="text-3xl font-extrabold text-gray-900">Daftar Tamu</h2>
            </div>
            
            <div class="flex flex-wrap items-center justify-end gap-3 flex-grow">
                
                <div class="relative flex-grow md:flex-grow-0">
                    <input name="search" type="search" placeholder="Cari nama tamu..."
                           value="{{ request('search') }}"
                           class="w-full md:w-[300px] bg-white border border-gray-200 rounded-lg py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </div>
                
                <input name="tanggal" type="date"
                       value="{{ request('tanggal') }}"
                       class="bg-white border border-gray-200 rounded-lg py-2.5 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">

                <select name="period"
                        class="bg-white border border-gray-200 rounded-lg py-2.5 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <option value="">Semua Periode</option>
                    <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="14" {{ request('period') == '14' ? 'selected' : '' }}>14 Hari Terakhir</option>
                    <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                </select>

                <button type="submit"
                        class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium">
                    🔍
                    <span>Cari</span>
                </button>
            
                @if(request('search') || request('tanggal') || request('period'))
                    <a href="{{ route('tamu.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium">
                        ♻️
                        <span>Reset</span>
                    </a>
                @endif

                <button type="submit"
                        formaction="{{ route('tamu.export.pdf') }}"
                        formmethod="GET"
                        target="_blank"
                        class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg shadow-md text-sm font-medium">
                    <img src="{{ asset('assets/icons/Icon_export.svg') }}" class="h-5 w-5" alt="Export Icon" />
                    <span>Export</span>
                </button>
            </div>
        </div>
    </form>


    <div class="rounded-lg overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead class="bg-sky-50 text-gray-700">
                <tr>
                    <th class="px-6 py-4 text-sm font-medium">No</th>
                    <th class="px-6 py-4 text-sm font-medium">Nama</th>
                    <th class="px-6 py-4 text-sm font-medium">Foto</th>
                    <th class="px-6 py-4 text-sm font-medium">Asal Instansi</th>
                    
                    <th class="px-4 py-4 text-sm font-medium">Nomor HP</th>
                    <th class="px-6 py-4 text-sm font-medium">Keperluan</th>
                    <th class="px-6 py-4 text-sm font-medium">Waktu</th>
                    <th class="px-6 py-4 text-sm font-medium">Selesai</th>
                    <th class="px-6 py-4 text-sm font-medium text-center">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="text-gray-700 text-sm">
                @forelse($tamus as $index => $tamu)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{ $tamus->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $tamu->nama }}</td>
                        <td class="px-6 py-4">
                            @if($tamu->foto)
                                <img src="{{ asset('storage/' . $tamu->foto) }}" 
                                     class="w-24 h-14 object-cover rounded-md" alt="foto tamu">
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $tamu->instansi }}</td>
                        
                        <td>
                            @if ($tamu->nomor)
                                @php
                                    $nomor = preg_replace('/[^0-9]/', '', $tamu->nomor);
                                    if (substr($nomor, 0, 1) === '0') {
                                        $nomor = '62' . substr($nomor, 1);
                                    }
                                @endphp

                                <a href="https://wa.me/{{ $nomor }}" 
                                target="_blank"
                                class="nomor-link {{ $tamu->nomor_diklik ? 'text-green-500' : 'text-black' }} hover:text-green-700 font-medium underline cursor-pointer"
                                data-tamu-id="{{ $tamu->id }}"
                                onclick="markNomorDiklik(event, {{ $tamu->id }})">
                                    {{ $tamu->nomor }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-600" title="{{ $tamu->keperluan }}">
                            {{ \Illuminate\Support\Str::limit($tamu->keperluan, 40, '...') }}
                        </td>
                        <td class="px-6 py-4">{{ $tamu->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($tamu->selesai)
                                <span class="text-green-600 font-medium">{{ \Carbon\Carbon::parse($tamu->selesai)->format('d-m-Y H:i') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('tamu.edit', $tamu->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-lg"
                                   title="Edit">
                                    <img src="{{ asset('assets/icons/Icon_edit.svg') }}" class="h-5 w-5" alt="Edit Icon" />
                                </a>

                                @if(!$tamu->selesai)
                                    <form action="{{ route('tamu.selesai', $tamu->id) }}" 
                                          method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white p-2.5 rounded-lg"
                                                title="Selesai"
                                                onclick="return confirm('Tandai tamu ini sebagai selesai?')">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" 
                                            class="bg-gray-300 text-gray-500 p-2.5 rounded-lg cursor-not-allowed"
                                            title="Sudah Selesai"
                                            disabled>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                <form action="{{ route('tamu.destroy', $tamu->id) }}" 
                                      method="POST" class="inline-block" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-white border border-red-600 p-2.5 rounded-lg"
                                            title="Hapus">
                                        <img src="{{ asset('assets/icons/Icon_hapus.svg') }}" class="h-5 w-5" alt="Delete Icon" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            Tidak ada data tamu yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tamus->withQueryString()->links() }}
    </div>

    @push('scripts')
    <script>
        function markNomorDiklik(event, tamuId) {
            const link = event.currentTarget;
            
            if (link.classList.contains('text-green-500')) {
                return;
            }
            
            // Update via AJAX
            fetch(`/admin/tamu/${tamuId}/nomor-diklik`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    link.classList.remove('text-black');
                    link.classList.add('text-green-500');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        </script>
    @endpush

</section>
@endsection