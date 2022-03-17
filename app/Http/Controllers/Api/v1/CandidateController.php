<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\Create;
use App\Http\Requests\Candidate\Update;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     * @param string $election_id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(string $election_id): JsonResponse
    {

        $election = Election::withoutTrashed()->with('candidates')->findOrFail($election_id);
        $this->authorize('viewAny',[Candidate::class,$election]);
        return $this->success(['candidates' =>  CandidateResource::collection($election->candidates)],200, 'Candidates retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Create $request
     * @param string $election_id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Create $request, string $election_id): JsonResponse
    {
        $election = Election::withoutTrashed()->with(['event','candidates'])->findOrFail($election_id);
        $this->authorize('create',[Candidate::class,$election]);
        $data = $request->validated();
        $data['election_id'] = $election_id;
        $data['created_by'] = $request->user()->committees()->where('event_id',$election->event_id)->first()->id;
        if(isset($data['image'])){
            $data['image_path'] = Storage::putFile('candidates', $data['image']);
            $data['image_url'] = Storage::url($data['image_path']);
        }
        $data['leader_id'] = User::where('email', $data['leader_email'])->first()->id;
        $data['vice_leader_id'] = User::where('email', $data['vice_leader_email'])->first()->id ?? null;
        $candidate = Candidate::create($data);
        return $this->success(['candidate' => new CandidateResource($candidate)], 201, 'Candidate created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param string $election_id
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $election_id, string $id): JsonResponse
    {
        $candidate = Election::withoutTrashed()->findOrFail($election_id)->candidates()->findOrFail($id);
        $this->authorize('view',$candidate);
        return $this->success(['candidate' => new CandidateResource($candidate)], 200, 'Candidate retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Update $request
     * @param  string $election_id
     * @param  string $id
     * @return JsonResponse
     */
    public function update(Update $request, string $election_id, string $id): JsonResponse
    {
        $candidate = Election::withoutTrashed()->findOrFail($election_id)->candidates()->findOrFail($id);
        $this->authorize('update',$candidate);
        $data = $request->validated();
        if($candidate->image_path){
            Storage::delete($candidate->image_path);
        }
        if(isset($data['image'])){
            $data['image_path'] = Storage::putFile('candidates', $data['image']);
            $data['image_url'] = Storage::url($data['image_path']);
        }
        $data['leader_id'] = User::where('email', $data['leader_email'])->first()->id;
        $data['vice_leader_id'] = User::where('email', $data['vice_leader_email'])->first()->id ?? null;
        $candidate->update($data);
        return $this->success(['candidate' => new CandidateResource($candidate)], 200, 'Candidate updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $election_id
     * @param  string $id
     * @return JsonResponse
     */
    public function destroy(string $election_id, string $id): JsonResponse
    {
        $candidate = Election::withoutTrashed()->findOrFail($election_id)->candidates()->findOrFail($id);
        $this->authorize('delete',$candidate);
        if ($candidate->image_path) {
            Storage::delete($candidate->image_path);
        }
        $candidate->delete();
        return $this->success(null, 204, 'Candidate deleted successfully');
    }
}
