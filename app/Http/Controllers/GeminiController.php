<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function analisaJurnal(Request $request)
    {
        $apiKey = env('GEMINI_API_KEY');
    
        $abstrakUser = $request->input('abstrak', 'Artificial Intelligence (AI) in education is growing rapidly. This paper analyzes how chatbots help students learn programming languages like PHP and JavaScript.');

        $promptSakti = "Kamu adalah pakar reviewer dan penerjemah jurnal ilmiah internasional. "
             . "Tugasmu adalah merangkum seluruh isi jurnal berikut: '" . $textJurnalUser . "'. "
             . "Catatan penting: Jika jurnal asli berbahasa Inggris, kamu harus menerjemahkan dan merangkumnya ke dalam Bahasa Indonesia yang akademis dan mudah dipahami. "
             . "Berikan hasil analisis dalam struktur JSON kaku dengan key wajib berikut: "
             . "{ "
             . "'judul_dan_penulis': 'Rangkuman singkat mengenai judul dan siapa penulisnya jika terdeteksi',"
             . "'latar_belakang_bab1': 'Poin penting latar belakang masalah dan tujuan penelitian dari Bab 1',"
             . "'landasan_teori': 'Ringkasan singkat teori atau penelitian terdahulu yang digunakan',"
             . "'metodologi_penelitian': 'Desain penelitian, data, dan metode yang digunakan untuk memecahkan masalah',"
             . "'hasil_dan_pembahasan': 'Temuan utama dari penelitian dan analisis datanya',"
             . "'kesimpulan_dan_saran': 'Intisari kesimpulan akhir dari bab penutup beserta saran penelitian ke depan'"
             . "}. "
             . "Pastikan kamu HANYA mengembalikan data JSON saja, tanpa tanda petik backtick (```json) dan tanpa basa-basi teks lain.";

        $response = Http::withHeaders([
        'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $promptSakti]
                    ]
                ]
            ],

            'generatingConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ]);

    // 5. Lempar hasilnya ke browser / frontend temanmu
        return response()->json([
            'status' => 'Sukses Analisis',
            'hasil_ai' => $response->json()
        ]);
}
}