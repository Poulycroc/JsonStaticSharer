<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    protected static $using;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
      $user = $this->resource;
      
      return collect([
        'id' => (int) $user->id,
        'name' => (string) $user->firstname.' '.$user->lastname,
        'email' => (string) $user->email,
        'firstname' => (string) $user->firstname,
        'lastname' => (string) $user->lastname,
      ]);
    }
}
