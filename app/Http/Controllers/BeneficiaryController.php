<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            Beneficiary::where('user_id', $user->id)->get()
        );
    }
}
