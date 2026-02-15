<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Generate dan download PDF dengan ukuran kertas dan orientasi yang bisa diatur.
     * Parameter:
     * - model: Nama model (e.g., 'Leave', 'User')
     * - id: ID record dari model
     * - view: Nama view Blade (e.g., 'leave.show', 'user.report')
     * - size: 'a4' atau 'a5' (default: 'a4')
     * - orientation: 'portrait' atau 'landscape' (default: 'portrait')
     */
    public function generatePDF(Request $request)
    {
        // Ambil parameter dari request
        $modelName = $request->get('Leave'); // e.g., 'Leave'
        $id = $request->get('id');
        $viewName = $request->get('view'); // e.g., 'leave.show'
        $paperSize = $request->get('size', 'a4'); // Default A4
        $orientation = $request->get('orientation', 'portrait'); // Default portrait

        // Validasi parameter wajib
        if (!$modelName || !$id || !$viewName) {
            return response()->json(['error' => 'Parameter model, id, dan view diperlukan.'], 400);
        }

        // Validasi ukuran kertas
        if (!in_array($paperSize, ['a4', 'a5'])) {
            $paperSize = 'a4';
        }

        // Validasi orientasi
        if (!in_array($orientation, ['portrait', 'landscape'])) {
            $orientation = 'portrait';
        }

        // Resolve model class (pastikan model ada di App\Models)
        $modelClass = 'App\\Models\\' . $modelName;
        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Model tidak ditemukan.'], 404);
        }

        // Ambil data dari model (dengan eager loading jika perlu, e.g., 'worker' untuk Leave)
        $data = $modelClass::with('worker')->findOrFail($id); // Sesuaikan eager loading berdasarkan model


        // Di PdfController.php, dalam method generatePDF()
        $pdf = Pdf::loadView('leave.pdf', compact('data')); // Ganti 'leave.show' ke 'leave.pdf'
        // Atur ukuran kertas dan orientasi
        $pdf->setPaper($paperSize, $orientation);

        // Nama file PDF: [nama_model]_[id]_[size]_[orientation].pdf
        $fileName = strtolower($modelName) . '_' . $id . '_' . $paperSize . '_' . $orientation . '.pdf';

        // Return sebagai download
        return $pdf->download($fileName);
    }
}