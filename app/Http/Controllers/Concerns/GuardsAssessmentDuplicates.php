<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Assessment;
use Illuminate\Http\Request;

trait GuardsAssessmentDuplicates
{
    protected function normalizedAssessor(Request $request): string
    {
        $raw = (string) $request->input('assessor', '');
        $value = strtolower(trim(preg_replace('/\s+/u', ' ', $raw)));

        return in_array($value, ['guru', 'dosen'], true) ? $value : 'dosen';
    }

    protected function assessmentDuplicateResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Penilaian untuk kombinasi mahasiswa (plot), PLP, bentuk penilaian, urutan ke-, dan penilai ini sudah ada. Tidak boleh mengisi berulang — gunakan ubah pada entri yang ada.',
        ], 422);
    }

    protected function assertAssessmentSlotAvailable(Request $request, ?Assessment $existing = null): ?\Illuminate\Http\JsonResponse
    {
        $mapId = (int) $request->input('map_id');
        $plpOrder = (int) $request->input('plp_order');
        $formId = (string) $request->input('form_id');
        $formOrder = (int) $request->input('form_order');
        $assessor = $this->normalizedAssessor($request);

        $query = Assessment::query()
            ->where('map_id', $mapId)
            ->where('plp_order', $plpOrder)
            ->where('form_id', $formId)
            ->where('form_order', $formOrder)
            ->where('assessor', $assessor);

        if ($existing !== null) {
            $query->whereKeyNot($existing->getKey());
        }

        if ($query->exists()) {
            return $this->assessmentDuplicateResponse();
        }

        return null;
    }
}
