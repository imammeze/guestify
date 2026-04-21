<!DOCTYPE html>
<html>
<head>
    <title>Daftar Tamu</title>
    <style>
        body {
            /* DejaVu Sans PENTING untuk rendering karakter
              non-latin dan simbol di PDF. Jangan diganti 'Inter'.
            */
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px; /* Sedikit lebih kecil untuk 7 kolom */
            color: #333;
        }
        h2 {
            text-align: left; /* Sesuai desain baru */
            color: #111827; /* text-gray-900 */
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-size: 10px; /* Lebih kecil agar data muat */
        }
        table, th, td {
            /* Sesuai border-gray-100 di desain baru */
            border: 1px solid #f3f4f6; 
        }
        thead th {
            /* Sesuai bg-sky-50 di desain baru */
            background-color: #f0f9ff; 
            /* Sesuai text-gray-700 di desain baru */
            color: #374151; 
            font-weight: bold;
            text-align: left;
            text-transform: uppercase; /* Opsi: Meniru desain lama */
        }
        th, td {
            padding: 8px;
            /* Mengatur perataan vertikal agar foto terlihat rapi */
            vertical-align: middle; 
        }
        tbody tr:nth-child(even) {
            /* Desain baru tidak memiliki strip, 
              tapi ini membantu keterbacaan di PDF. 
              Gunakan warna #fdfdff (abu-abu sangat muda) 
            */
            background-color: #f9fafb;
        }
        td img {
            border-radius: 4px; /* Sedikit border-radius */
            display: block;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-wrap {
            /* Memaksa teks panjang untuk pindah baris */
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
</head>
<body>
    <h2>Daftar Tamu</h2>

    <table>
        <thead>
            <tr>
                <th class="text-left" style="width: 3%;">No</th>
                <th class="text-left" style="width: 20%;">Nama</th>
                <th class="text-center" style="width: 15%;">Foto</th>
                <th class="text-left" style="width: 17%;">Asal Instansi</th>
                <th class="text-left" style="width: 10%;">Nomor HP</th>
                <th class="text-left" style="width: 20%;">Keperluan</th>
                <th class="text-left" style="width: 15%;">Waktu</th>
                </tr>
        </thead>
        <tbody>
            @foreach ($daftarTamu as $index => $tamu)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                
                <td>{{ $tamu->nama }}</td>
                
                <td class="text-center">
                    @if($tamu->foto)
                        <img src="{{ public_path('storage/' . $tamu->foto) }}" width="60">
                    @else
                        -
                    @endif
                </td>
                
                <td>{{ $tamu->instansi }}</td>
                
                <td>{{ $tamu->nomor ?? '-' }}</td>
                
                <td class="text-wrap">
                  {{-- 
                    Di PDF, kita ingin menampilkan SEMUA teks, 
                    bukan membatasinya dengan Str::limit.
                  --}}
                  {{ $tamu->keperluan }}
                </td>
                
                <td>
                  {{-- Format waktu disamakan dengan tabel (termasuk jam) --}}
                  {{ \Carbon\Carbon::parse($tamu->created_at)->format('d-m-Y H:i') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>