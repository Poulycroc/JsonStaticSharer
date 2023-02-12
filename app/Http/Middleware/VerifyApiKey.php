<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Project;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $projectId = $request->route('projectId');
        $apiKey = $request->header('X-Api-Key');

        $project = Project::where('id', $projectId)->first();
        if (!$project || $project->api_key != $apiKey) {
            return response()->json([
                'message' => 'Unauthorized Access'
            ], 401);
        }

        return $next($request);
    }
}
