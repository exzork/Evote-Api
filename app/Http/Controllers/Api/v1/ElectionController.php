<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Election\Create;
use App\Http\Requests\Election\Update;
use App\Http\Resources\ElectionResource;
use App\Models\Election;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ElectionController extends Controller
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
        $event = Event::withoutTrashed()->with('elections')->findOrFail($event_id);
        $this->authorize('viewAny',[Election::class,$event]);
        return $this->success(['elections' => ElectionResource::collection($event->elections)],200,'Elections retrieved successfully');
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
        $event = Event::withoutTrashed()->findOrFail($event_id);
        $this->authorize('create',[Election::class,$event]);
        $data = $request->validated();
        $data['created_by'] = $request->user()->committees()->where('event_id',$event_id)->first()->id;
        $election = $event->elections()->create($data);
        return $this->success(['election' => ElectionResource::make($election)],201,'Election created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param string $event_id
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $event_id, string $id): JsonResponse
    {
        $election = Event::withoutTrashed()->with(['elections','event'])->findOrFail($event_id)->elections()->findOrFail($id);
        $this->authorize('view',$election);
        return $this->success(['election' => ElectionResource::make($election)],200,'Election retrieved successfully');
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
    public function update(Request $request,string $event_id,string $id): JsonResponse
    {
        $election = Event::withoutTrashed()->with(['elections','event'])->findOrFail($event_id)->elections()->findOrFail($id);
        $this->authorize('update',$election);
        $election->update($request->validated());
        return $this->success(['election' => ElectionResource::make($election)],200,'Election updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $event_id
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $event_id,string $id): JsonResponse
    {
        $election = Event::withoutTrashed()->with(['elections','event'])->findOrFail($event_id)->elections()->findOrFail($id);
        $this->authorize('delete',$election);
        $election->delete();
        return $this->success(null,204,'Election deleted successfully');
    }
}
