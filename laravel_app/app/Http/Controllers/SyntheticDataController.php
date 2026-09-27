<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SyntheticDataController extends Controller
{
    public function showForm()
    {
        return view('generate');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'num_rows' => 'required|integer|min:1',
            'epochs' => 'required|integer|min:1',
        ]);

        $response = Http::attach(
            'file',
            file_get_contents($request->file('file')->getRealPath()),
            $request->file('file')->getClientOriginalName()
        )->post('http://127.0.0.1:8001/generate', [
            'num_rows' => $request->input('num_rows'),
            'epochs' => $request->input('epochs'),
        ]);

        $data = $response->json();

$response = Http::attach(
    'file',
    file_get_contents($request->file('file')->getRealPath()),
    $request->file('file')->getClientOriginalName()
)->post('http://127.0.0.1:8001/generate', [
    'num_rows' => $request->input('num_rows'),
    'epochs' => $request->input('epochs'),
]);

$data = $response->json();

return view('results', ['data' => $data]);

        return view('results', ['data' => $data]);
    }
}
