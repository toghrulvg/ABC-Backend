<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            Card::where('user_id', $user->id)->get()
        );
    }
}
