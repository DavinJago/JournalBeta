<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SemanticController extends Controller
{
    public function cariJurnal(Request $request)
    {
        $query = $request->input('keyword');

        if (!$query) {
            return response()->json(['error' => 'Kata kunci tidak boleh kosong'], 400);
        }

        $apiSemantic = env('SEMANTIC_SCHOLAR_API_KEY');

        $response = Http::withHeaders([
            'x-api-key' => $apiSemantic
        ])->get("https://api.semanticscholar.org/graph/v1/paper/search", [
            'query' => $query,
            'limit' => 20,
            'fields' => 'title,url,abstract,authors',

            'openAccessPdf' => true,
            'minCitationCount' => 1
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data dari Semantic Scholar'], 500);
        }

        return response()->json([
            'status' => 'Sukses',
            'data' => $response->json()['data'] ?? []
        ]);
    }
}
