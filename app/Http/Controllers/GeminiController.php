<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
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
        $request->validate([
            'file_jurnal' => 'required|file|mimes:pdf,txt|max:10000', 
        ]);

        if ($request->hasFile('file_jurnal')) {
            $file = $request->file('file_jurnal');
            
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathFile = $file->storeAs('public/jurnals', $namaFile);

            if ($file->getClientOriginalExtension() == 'txt') {
                $teksJurnalMentah = file_get_contents($file->getRealPath());
            } else {
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile($file->getRealPath());
                
                // Taktik pembatasan halaman agar tidak overload token Gemini
                $pages = $pdf->getPages();
                $teksJurnalMentah = "";
                $maxHalaman = min(count($pages), 5); 

                for ($i = 0; $i < $maxHalaman; $i++) {
                    $teksJurnalMentah .= $pages[$i]->getText() . "\n";
                }
            }

            $idJurnalBaru = DB::table('jurnals')->insertGetId([
                'judul_file' => $namaFile,
                'path_file' => $pathFile,
                'ekstensi_teks' => $teksJurnalMentah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $apiKey = env('GEMINI_API_KEY');
            
            $promptSakti = "Kamu adalah pakar reviewer jurnal ilmiah internasional. "
                        . "Tugasmu adalah merangkum seluruh isi jurnal berikut: '" . $teksJurnalMentah . "'. "
                        . "Catatan: Jika jurnal asli berbahasa Inggris, kamu harus menerjemahkan dan merangkumnya ke dalam Bahasa Indonesia yang akademis. "
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

            $kontenMentahAI = $responseGemini->json()['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $dataJsonAI = json_decode($kontenMentahAI, true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($dataJsonAI)) {
                $dataJsonAI = [
                    'judul_dan_penulis' => $namaFile,
                    'latar_belakang_bab1' => 'Gagal membedah otomatis. Struktur teks PDF terlalu kompleks atau mengandung banyak simbol.',
                    'landasan_teori' => 'Tidak dapat diekstrak.',
                    'metodologi_penelitian' => 'Tidak dapat diekstrak.',
                    'hasil_dan_pembahasan' => 'Tidak dapat diekstrak.',
                    'kesimpulan_dan_saran' => 'Tidak dapat diekstrak.'
                ];
            }

            return response()->json([
                'status' => 'Sukses Simpan dan Analisis',
                'id_database' => $idJurnalBaru,
                'nama_file_tersimpan' => $namaFile,
                'hasil_rangkuman_gemini' => $dataJsonAI 
            ]);
        }

        return response()->json(['error' => 'Gagal mengupload file'], 400);
    }
}