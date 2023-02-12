<?php

namespace App\Http\Resources;

use App\Models\Invite;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
        /**
         * @var $item Invite
         */
        $item = $this->resource;

        $claimer = $this->claimer;

        $claimerArray = $claimer !== null
            ?
                [
                    'link' => $claimer->link,
                    'name' => $claimer->name,
                    'presentation' => $claimer->presentation,
                    'thumb' => $claimer->thumb,
                ]
            :
                [];

        return array_merge($claimerArray, [
            'id' => $item->id,
            'creator' => $item->creator,
            'invited' => $item->claimer,
            'email' => $item->email,
            'group_id' => $item->object_id,
            'group_type' => $item->object_type,
            'message' => $item->message,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ]);
    }
}
