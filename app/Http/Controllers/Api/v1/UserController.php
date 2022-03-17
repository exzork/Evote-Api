<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);
        return $this->success(['users'=>UserResource::collection(User::all())],200,'Users retrieved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $id): JsonResponse
    {
        $this->authorize('view', User::class);
        return $this->success(['user'=>UserResource::make(User::findOrFail($id))],200,'User retrieved successfully');
    }
}
