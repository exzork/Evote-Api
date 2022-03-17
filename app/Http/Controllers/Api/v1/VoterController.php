<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voter\Create;
use App\Http\Resources\VoterResource;
use App\Models\Event;
use App\Models\Voter;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     * @param string $event_id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(string $event_id): JsonResponse
    {
        $event = Event::withoutTrashed()->with('voters')->findOrFail($event_id);
        $this->authorize('viewAny', [Voter::class, $event]);
        return $this->success(['voters' => VoterResource::collection($event->voters)],200, 'Voters retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Create $request
     * @param string $event_id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Create $request, string $event_id): JsonResponse
    {
        $data = $request->validated();
        $event = Event::withoutTrashed()->with('voters')->findOrFail($event_id);
        $this->authorize('create', [Voter::class, $event]);
        $voter = $event->voters()->create($data);
        return $this->success(['voter' => VoterResource::make($voter)], 201, 'Voter created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $event_id
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $event_id, string $id): JsonResponse
    {
        $voter = Event::withoutTrashed()->findOrFail($event_id)->voters()->findOrFail($id);
        $this->authorize('delete', $voter);
        $voter->delete();
        return $this->success(null, 204, 'Voter deleted successfully');
    }
}
