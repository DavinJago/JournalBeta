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
        $apiKeyGemini = env('GEMINI_API_KEY');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKeyGemini, [
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
            'response_mentah' => $response->json()
        ]);
    }

    public function uploadDanAnalisa(Request $request)
    {
        // 1. Validasi apakah file benar-benar di-upload oleh user
        $request->validate([
            'file_jurnal' => 'required|file|mimes:pdf,txt|max:10000', // maksimal 10MB
        ]);

        if ($request->hasFile('file_jurnal')) {
            $file = $request->file('file_jurnal');
            
            // 2. Simpan file fisik ke folder 'storage/app/public/jurnals'
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathFile = $file->storeAs('public/jurnals', $namaFile);

            // 3. Ekstraksi Teks (Contoh simulasi pembacaan file txt/pdf sederhana)
            // Jika file berupa txt, kita bisa langsung baca isinya dengan file_get_contents
            if ($file->getClientOriginalExtension() == 'txt') {
                $teksJurnalMentah = file_get_contents($file->getRealPath());
            } else {
                // Untuk demo awal PDF, kita simulasikan mengambil potongan teks teks besar,
                // atau minta teman frontend mengirimkan teksnya langsung via request jika mereka pakai pdf-reader di browser.
                $teksJurnalMentah = "Ini adalah simulasi teks panjang dari Bab 1 sampai Kesimpulan file PDF: " . $namaFile;
            }

            // 4. Catat riwayat upload ke Database MySQL via XAMPP
            $idJurnalBaru = DB::table('jurnals')->insertGetId([
                'judul_file' => $namaFile,
                'path_file' => $pathFile,
                'ekstraksi_teks' => $teksJurnalMentah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. OPER TEKS JURNAL KE OTAK AI GEMINI (Prompt Full-Text kita kemarin)
            $apiKey = env('GEMINI_API_KEY');
            
            $promptSakti = "Kamu adalah pakar reviewer jurnal ilmiah internasional. ". "Tugasmu adalah merangkum seluruh isi jurnal berikut: '" . $teksJurnalMentah . "'. ". "Catatan: Jika jurnal asli berbahasa Inggris, kamu harus menerjemahkan dan merangkumnya ke dalam Bahasa Indonesia yang akademis. "
                        . "Berikan hasil analisis dalam struktur JSON kaku dengan key wajib berikut: "
                        . "{ "
                        . "'judul_dan_penulis': '...', 'latar_belakang_bab1': '...', 'landasan_teori': '...', "
                        . "'metodologi_penelitian': '...', 'hasil_dan_pembahasan': '...', 'kesimpulan_dan_saran': '...'"
                        . "}. "
                        . "Pastikan kamu HANYA mengembalikan data JSON saja, tanpa tanda petik backtick dan tanpa basa-basi teks lain.";

            $responseGemini = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
                'contents' => [
                    ['parts' => [['text' => $promptSakti]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            // 6. Kembalikan respons sukses ke Frontend
            return response()->json([
                'status' => 'Sukses Simpan dan Analisis',
                'id_database' => $idJurnalBaru,
                'nama_file_tersimpan' => $namaFile,
                'hasil_rangkuman_gemini' => $responseGemini->json()
            ]);
        }

        return response()->json(['error' => 'Gagal mengupload file'], 400);
    }
}