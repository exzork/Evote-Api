<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Committee\Create;
use App\Http\Requests\Committee\Update;
use App\Http\Resources\CommitteeResource;
use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CommitteeController extends Controller
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
        $event = Event::withoutTrashed()->with('committees')->findOrFail($event_id);
        $this->authorize('viewAny', [Committee::class, $event]);
        return $this->success(['committees' => CommitteeResource::collection($event->committees)],200, 'Committees retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Create $request
     * @param string $event_id
     * @return JsonResponse
     */
    public function store(Create $request, string $event_id): JsonResponse
    {
        $event = Event::withoutTrashed()->with('committees')->findOrFail($event_id);
        $this->authorize('create', [Committee::class, $event]);
        $data  = $request->validated();
        $data['event_id'] = $event_id;
        $data['user_id'] = User::where('email', $data['email'])->first()->id;
        $committee = Committee::create($data);
        return $this->success(['committee' => new CommitteeResource($committee)], 201, 'Committee created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param string $event_id
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $event_id, string $id): JsonResponse
    {
        $event = Event::withoutTrashed()->with('committees')->findOrFail($event_id);
        $committee = $event->committees()->findOrFail($id);
        $this->authorize('view', $committee);
        return $this->success(['committee' => new CommitteeResource($committee)], 200, 'Committee retrieved successfully');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param string $event_id
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Update $request, string $event_id, string $id): JsonResponse
    {
        $event = Event::withoutTrashed()->with('committees')->findOrFail($event_id);
        $committee = $event->committees()->findOrFail($id);
        $this->authorize('update', $committee);
        $data = $request->validated();
        $data['user_id'] = User::where('email', $data['email'])->first()->id;
        $committee->update($data);
        return $this->success(['committee' => new CommitteeResource($committee)], 200, 'Committee updated successfully');
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
        $event = Event::withoutTrashed()->with('committees')->findOrFail($event_id);
        $committee = $event->committees()->findOrFail($id);
        $this->authorize('delete', $committee);
        $committee->delete();
        return $this->success(null, 204, 'Committee deleted successfully');
    }
}
