<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            Transaction::whereHas('account', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get()
        );
    }
}
