<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DataUsageTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // $contentLength = strlen($response->getContent());
        // $dataUsage = $contentLength / 1024 / 1024;

        // // Enregistrement du poids de données envoyé
        // // \Log::info("Data usage: {$dataUsage} MB");


        // Autre version
        // // Mise à jour des informations sur les données transférées pour ce projet
        // $project->data_transferred += $response->getContentLength();
        // $project->save();

        return $response;
    }
}
