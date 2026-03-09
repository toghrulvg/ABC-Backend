<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            Transfer::where('user_id', $user->id)->get()
        );
    }
}
