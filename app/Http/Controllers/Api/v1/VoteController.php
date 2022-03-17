<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vote\Create;
use App\Http\Requests\Vote\Update;
use App\Http\Resources\VoteResource;
use App\Models\Election;
use App\Models\Event;
use App\Models\Vote;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class VoteController extends Controller
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
        $event = Event::with('votes')->findOrFail($event_id);
        $this->authorize('viewAny', [Vote::class,$event]);
        return $this->success(['votes' => VoteResource::collection($event->votes)],200, 'Votes retrieved successfully');
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
        $event = Event::withoutTrashed()->findOrFail($event_id);

        $this->authorize('create', [Vote::class,$event]);

        if ($data['images']){
            $data['image_paths'] = [];
            $data['image_urls'] = [];
            foreach ($data['images'] as $image){
                $path = Storage::putFile('votes', $image);
                $data['image_paths'][] = $path;
                $data['image_urls'][] = Storage::url($path);
            }
            $data['image_paths'] = json_encode($data['image_paths']);
        }
        $data['image_urls'] = json_encode($data['image_urls']);
        $vote = $event->votes()->create($data);
        return $this->success(['vote'=>VoteResource::make($vote)], 201, 'Vote created successfully');
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
        $vote = Event::with('votes')->findOrFail($event_id)->votes()->findOrFail($id);
        $this->authorize('view', $vote);
        return $this->success(['vote'=>VoteResource::make($vote)], 200, 'Vote retrieved successfully');
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
        $vote = Event::with('votes')->findOrFail($event_id)->votes()->findOrFail($id);
        $this->authorize('update', $vote);
        $vote->update($request->validated());
        return $this->success(['vote'=>VoteResource::make($vote)], 200, 'Vote updated successfully');
    }
}
