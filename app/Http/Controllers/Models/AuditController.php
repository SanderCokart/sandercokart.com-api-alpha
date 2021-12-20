<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OwenIt\Auditing\Models\Audit;
use function response;

class AuditController extends Controller
{
    public function indexEmailChanges(): JsonResponse
    {
        return response()->json(Audit::where('old_values', 'like', '%email%')->get(['old_values', 'new_values', 'user_id']));
    }
}
