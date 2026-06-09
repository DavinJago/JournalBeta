<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SemanticController extends Controller
{
    public function cariJurnal(Request $request)
    {
    // 1. Ambil kata kunci yang diketik user dari frontend
        $query = $request->input('keyword');

        if (!$query) {
            return response()->json(['error' => 'Kata kunci tidak boleh kosong'], 400);
        }

        $apiSemantic = env('SEMANTIC_SCHOLAR_API_KEY');

    // 2. Tembak API Semantic Scholar (Mencari Jurnal)
        $response = Http::withHeaders([
            'x-api-key' => $apiSemantic
        ])->get("https://api.semanticscholar.org/graph/v1/paper/search", [
            'query' => $query,
            'limit' => 10,
            'fields' => 'title,url,abstract,authors',

            'openAccessPdf' => true,
            'minCitationCount' => 1
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data dari Semantic Scholar'], 500);
        }

    // 3. Kirim hasilnya yang rapi ke Frontend
        return response()->json([
            'status' => 'Sukses',
            'data' => $response->json()['data'] ?? []
        ]);
    }
}
