<?php

namespace App\Repositories;

use App\Repositories\Setters\OwnerSetter;
use App\Repositories\Setters\UuidSetter;
use App\Repositories\Setters\ApiKeySetter;
use App\Http\Resources\ProjectResource;

use App\Models\User;
use App\Models\Project;

class ProjectRepository
{
    use OwnerSetter;
    use UuidSetter;
    use ApiKeySetter;

    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function all()
    {
        return $this->project->all();
    }

    public function find(string $uuid)
    {
        $project = $this->project->findByUuid($uuid);
        return ProjectResource::make($project);
    }

    public function create($request)
    {
        $project = new Project();
        $project->name = $request->input('name');
        $project->description = $request->input('description');

        if ($project->save()) {
            self::setUuid($project);

            $user = $request->has('creator_id')
                ? User::query()->find($request->input('creator_id'))
                : auth()->user();

            self::setProjectCreator($project, $user);
        }

        return $project;
    }

    public function generateApiKey(string $uuid)
    {
        $project = $this->project->findByUuid($uuid);
        self::setApiKey($project);
        return $project;
    }

    public function findByApiKey(string $apiKey)
    {
        $project = $this->project->where('api_key', $apiKey)->first();
        
    }
}
