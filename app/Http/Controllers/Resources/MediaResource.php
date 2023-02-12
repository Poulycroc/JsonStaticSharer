<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// use Illuminate\Support\Facades\URL;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        $media = $this->resource;
        $dimension = getimagesize(base_path('public/storages/media_library').'/'.$media->file_name);

        $data = [
          'id' => (int) $media->id,
          'uuid' => (string) $media->uuid,
          'file_name' => (string) $media->file_name,
          'size' => (string) $media->file_size,
          'dimension' => [
              'width' => $dimension["0"],
              'height' => $dimension["1"],
              'size' => $dimension["3"],
              'mime' => $dimension["mime"],
              'bits' => $dimension["bits"],
          ],
          'url' => (string) url('storages/media_library/'.$media->file_name),
          'created_at' => $media->created_at,

          'name' => (string) $media->name,
          'group' => (string) $media->group,
          'disk' => (string) $media->disk,
        ];

        return collect($data);
    }
}
