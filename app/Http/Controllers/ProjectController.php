<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProjectRepository;

use App\Models\Project;

class ProjectController extends Controller
{
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return Inertia::render('projects/index', [
            'projects' => $this->projectRepository->all(),
            'status' => session('status')
        ]);
    }

    public function lasts(): JsonResponse
    {
      return response()->json([
        'projects' => $this->projectRepository->all()
      ], HttpResponse::HTTP_OK);
    }

    public function store(Request $request): Response
    {
        $request->validate(['name' => 'required|string|max:255']);
        $project = $this->projectRepository->create($request);

        return redirect()->route('project.find', $project->uuid);
    }

    public function find(string $uuid): Response
    {
        $project = $this->projectRepository->find($uuid);

        return Inertia::render('projects/_uuid', [
            'project' => $project,
            'status' => session('status'),
        ]);
    }
}
