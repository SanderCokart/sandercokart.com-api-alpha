<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function indexEmailChanges(): JsonResponse
    {
        return response()->json(Audit::where('old_values', 'like', '%email%')->get(['old_values', 'new_values', 'user_id']));
    }
}
