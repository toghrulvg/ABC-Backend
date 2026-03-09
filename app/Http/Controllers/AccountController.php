<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            Account::where('user_id', $user->id)->get()
        );
    }
}
