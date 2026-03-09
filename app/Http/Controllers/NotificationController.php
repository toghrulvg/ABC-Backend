<?php

namespace App\Http\Controllers;

use App\Models\Notification as NotificationModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAll(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            NotificationModel::where('user_id', $user->id)->get()
        );
    }
}
