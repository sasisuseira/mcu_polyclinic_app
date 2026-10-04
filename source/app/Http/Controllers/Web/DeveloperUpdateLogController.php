<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DeveloperUpdateLog;
use Illuminate\Http\Request;

class DeveloperUpdateLogController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'title' => 'Log Update Aplikasi',
            'breadcrumb' => ['Beranda' => route('admin.beranda')],
            'user_details' => $request->attributes->get('user_details'),
        ];

        $updateLogs = DeveloperUpdateLog::orderByDesc('released_at')
            ->orderByDesc('created_at')
            ->get();

        return view('paneladmin.developer.update_logs', compact('data', 'updateLogs'));
    }

    public function publicIndex()
    {
        $updateLogs = DeveloperUpdateLog::where('visibility', 'public')
            ->orderByDesc('released_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('public_update_logs', compact('updateLogs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $userDetails = $request->attributes->get('user_details');
        $validated['author_name'] = data_get($userDetails, 'nama_pegawai')
            ?: data_get($userDetails, 'nama')
            ?: 'Developer';

        DeveloperUpdateLog::create($validated);

        return redirect()->route('dev.update-logs.index')->with('success', 'Log update berhasil ditambahkan.');
    }

    public function update(Request $request, DeveloperUpdateLog $updateLog)
    {
        $updateLog->update($this->validatedData($request));

        return redirect()->route('dev.update-logs.index')->with('success', 'Log update berhasil diperbarui.');
    }

    public function destroy(DeveloperUpdateLog $updateLog)
    {
        $updateLog->delete();

        return redirect()->route('dev.update-logs.index')->with('success', 'Log update berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'version' => ['nullable', 'string', 'max:40'],
            'category' => ['required', 'in:Feature,Improvement,Fix,Security,Maintenance'],
            'summary' => ['required', 'string', 'max:500'],
            'details' => ['required', 'string', 'max:20000'],
            'released_at' => ['required', 'date'],
            'visibility' => ['required', 'in:internal,public'],
        ]);
    }
}