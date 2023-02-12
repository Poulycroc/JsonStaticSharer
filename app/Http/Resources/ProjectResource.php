<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
      $campaign = $this->resource;

      $data = [
        'name' => (string) $campaign->name,
        'description' => $campaign->description,
        'uuid' => (string) $campaign->uuid,
        // 'permissions' => $this->getPermissions($campaign),
        // 'auth_roles' => $campaign->getRoleFromMember(auth()->user(), true),
        'members' => $this->getMembers($campaign),
      ];

      return collect($data);
    }

    public function getMembers($campaign)
    {
      $members = $campaign->members();
      return MemberResource::collection($members);
    }

    public function getPermissions($campaign)
    {
      $res = [];
      $roles = $campaign->getRoleFromMember(auth()->user(), true);

      foreach ($roles as $item) {
        $res = array_merge($res, $item->permissions->toArray());
      }

      return array_unique($res);
    }
}
