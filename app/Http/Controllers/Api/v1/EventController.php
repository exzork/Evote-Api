<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\Create;
use App\Http\Requests\Event\Update;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = Event::with('user')->withoutTrashed()->get();
        foreach ($events as $key => $event) {
            try {
                $this->authorize('view', $event); // Check if user can view event
            }catch (AuthorizationException $e) {
                unset($events[$key]);
            }
        }
        return $this->success(['events' => EventResource::collection($events)],200,'Events retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Create $request
     * @return JsonResponse
     */
    public function store(Create $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        if(isset($data['image'])){
            $data['image_path'] = Storage::putFile('events', $data['image']);
            $data['image_url'] = Storage::url($data['image_path']);
        }
        $event = Event::create($data);
        return $this->success(['event' => new EventResource($event)],201,'Event created successfully');
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
        $event = Event::withoutTrashed()->findOrFail($id);
        $this->authorize('view', $event);
        return $this->success(['event' => new EventResource($event)],200,'Event retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Update $request, string $id): JsonResponse
    {
        $event = Event::withoutTrashed()->findOrFail($id);
        $this->authorize('update', $event);
        $data = $request->validated();
        if(isset($data['image'])){
            $data['image_path'] = Storage::putFile('events', $data['image']);
            $data['image_url'] = Storage::url($data['image_path']);

            if($event->image_path){
                Storage::delete($event->image_path);
            }
        }else{
            $data['image_path'] = "";
        }
        $event->update($data);
        return $this->success(['event' => new EventResource($event)],200,'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $event = Event::withoutTrashed()->findOrFail($id);
        $this->authorize('delete', $event);
        if ($event->image_path) {
            Storage::delete($event->image_path);
        }
        $event->delete();
        return $this->success(null,204,'Event deleted successfully');
    }
}
