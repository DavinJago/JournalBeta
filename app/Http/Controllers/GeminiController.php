<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GeminiController extends Controller
{
    public function testKoneksi()
    {
        $apiKey = env('GEMINI_API_KEY');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Katakan "Koneksi Baru XAMPP Sukses!"']
                    ]
                ]
            ]
        ]);

        return response()->json([
            'status_gemini_api' => $response->successful() ? 'Koneksi Berhasil!' : 'Gagal, cek .env lagi',
            'response_mentah'   => $response->json()
        ]);
    }

    public function uploadDanAnalisa(Request $request)
    {
        // ── 1. VALIDASI FILE ────────────────────────────────────────────────
        $request->validate([
            'file_jurnal' => 'required|file|mimes:pdf,txt|max:10000',
        ]);

        if (!$request->hasFile('file_jurnal')) {
            return response()->json(['error' => 'Gagal mengupload file'], 400);
        }

        $file      = $request->file('file_jurnal');
        $namaFile  = time() . '_' . $file->getClientOriginalName();
        $pathFile  = $file->storeAs('public/jurnals', $namaFile);
        $ekstensi  = strtolower($file->getClientOriginalExtension());
        $mimeType  = ($ekstensi === 'txt') ? 'text/plain' : 'application/pdf';
        $apiKey    = env('GEMINI_API_KEY');

        // ── 2. UPLOAD FILE KE GEMINI FILE API ──────────────────────────────
        $uploadResponse = Http::timeout(60)->withHeaders([
            'X-Goog-Upload-Protocol'          => 'raw',
            'X-Goog-Upload-Header-Content-Type' => $mimeType,
            'Content-Type'                    => $mimeType,
        ])->send('POST', "https://generativelanguage.googleapis.com/upload/v1beta/files?key={$apiKey}", [
            'body' => file_get_contents($file->getRealPath())
        ]);

        if (!$uploadResponse->successful()) {
            return response()->json([
                'error'  => 'Gagal upload ke Gemini File API',
                'detail' => $uploadResponse->json()
            ], 500);
        }

        $fileUri  = $uploadResponse->json()['file']['uri']  ?? null;
        $fileName = $uploadResponse->json()['file']['name'] ?? null;

        if (!$fileUri || !$fileName) {
            return response()->json(['error' => 'URI/name file Gemini tidak ditemukan'], 500);
        }

        // ── 3. POLLING STATUS FILE SAMPAI "ACTIVE" ─────────────────────────
        // Gemini File API bersifat async — file masuk status PROCESSING dulu,
        // baru ACTIVE. Kalau langsung dipakai sebelum ACTIVE, Gemini gagal baca.
        $maxRetry   = 15;   // maksimal 15x coba (± 30 detik)
        $fileStatus = 'PROCESSING';

        for ($i = 0; $i < $maxRetry; $i++) {
            $statusResponse = Http::timeout(10)
                ->get("https://generativelanguage.googleapis.com/v1beta/{$fileName}?key={$apiKey}");

            $fileStatus = $statusResponse->json()['state'] ?? 'PROCESSING';

            if ($fileStatus === 'ACTIVE') {
                break;
            }

            // Jika status FAILED, hentikan langsung
            if ($fileStatus === 'FAILED') {
                return response()->json([
                    'error'  => 'Gemini gagal memproses file (status: FAILED)',
                    'detail' => $statusResponse->json()
                ], 500);
            }

            sleep(2); // tunggu 2 detik sebelum cek ulang
        }

        if ($fileStatus !== 'ACTIVE') {
            return response()->json([
                'error' => 'File Gemini tidak kunjung ACTIVE setelah ' . ($maxRetry * 2) . ' detik. Coba lagi.'
            ], 500);
        }

        // ── 4. SIMPAN RIWAYAT KE DATABASE ──────────────────────────────────
        $idJurnalBaru = DB::table('jurnals')->insertGetId([
            'judul_file'    => $namaFile,
            'path_file'     => $pathFile,
            'ekstensi_teks' => '[' . strtoupper($ekstensi) . ' diproses langsung via Gemini File API]',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ── 5. PROMPT & GENERATE CONTENT ───────────────────────────────────
        $promptSakti = "Kamu adalah pakar reviewer jurnal ilmiah internasional. "
            . "Tugasmu adalah merangkum seluruh isi jurnal pada file dokumen yang dilampirkan. "
            . "Catatan: Jika jurnal asli berbahasa Inggris, terjemahkan dan rangkum ke dalam Bahasa Indonesia yang akademis. "
            . "Berikan hasil analisis dalam struktur JSON kaku dengan key wajib berikut: "
            . "{ "
            . "'judul_dan_penulis': '...', 'latar_belakang_bab1': '...', 'landasan_teori': '...', "
            . "'metodologi_penelitian': '...', 'hasil_dan_pembahasan': '...', 'kesimpulan_dan_saran': '...', "
            . "'ide_penelitian_baru': '...' "
            . "}. "
            . "Pada bagian 'ide_penelitian_baru', analisis celah (research gap) dari jurnal tersebut, lalu rumuskan minimal 1 ide baru yang konkret, lengkap dengan usulan judul baru dan metode pengembangannya agar bisa dijadikan acuan penelitian lanjutan. "
            . "Pastikan kamu HANYA mengembalikan data JSON saja, tanpa backtick dan tanpa teks lain.";

        $responseGemini = Http::timeout(120)->withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        [
                            'file_data' => [
                                'mime_type' => $mimeType,
                                'file_uri'  => $fileUri,
                            ]
                        ],
                        ['text' => $promptSakti]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ]);

        // ── 6. PARSING HASIL ────────────────────────────────────────────────
        $kontenMentahAI = $responseGemini->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$kontenMentahAI) {
            return response()->json([
                'error'      => 'Gemini tidak mengembalikan konten',
                'gemini_raw' => $responseGemini->json(),
            ], 500);
        }

        // Bersihkan backtick kalau Gemini masih ikutkan markdown fence
        $kontenBersih = trim(preg_replace('/^```json|^```|```$/m', '', $kontenMentahAI));

        $dataJsonAI = json_decode($kontenBersih, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($dataJsonAI)) {
            return response()->json([
                'error'      => 'Gagal parse JSON dari Gemini',
                'raw_output' => $kontenMentahAI,
            ], 500);
        }

        return response()->json([
            'status'                 => 'Sukses Simpan dan Analisis',
            'id_database'            => $idJurnalBaru,
            'nama_file_tersimpan'    => $namaFile,
            'hasil_rangkuman_gemini' => $dataJsonAI
        ]);
    }
}