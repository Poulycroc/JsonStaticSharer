<?php

namespace App\Repositories;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MediaRepository
{
    /**
     * Controller constructor.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function findByUuid(string $uuid)
    {
        return $this->media->findByUuid($uuid);
    }

    public function save($request)
    {
        if (!$request->has('file')) {
            return [
              'success' => false,
              'message' => 'upload_file_not_found',
              'code' => 400,
            ];
        }

        $files = $request->input('file');

        $exploded = explode(',', $request->input('file'));
        $mime = $exploded[0];

        $allowed_mimes = ['data:image/jpeg;base64', 'data:image/png;base64'];
        if (!in_array($mime, $allowed_mimes)) {
            return [
              'success' => false,
              'message' => 'upload_wrong_file_type',
              'code' => 403,
            ];
        }

        $decoded = base64_decode($exploded[1]);

        $ext = str_contains($mime, 'jpeg') ? 'jpg' : 'png';

        $strRandom = Str::random(40);

        $fileName = $strRandom.'.'.$ext;
        $fileSize = strlen(base64_decode($request->input('file')));

        $uploads_dir = base_path('public/storages/media_library');
        $path = $uploads_dir.'/'.$fileName;

        $file = fopen($path, 'w');
        fwrite($file, $decoded);
        fclose($file);

        $uuid = Uuid::uuid4();

        $img = new Media();
        $img->name = $request->input('name');
        $img->file_name = $fileName;
        $img->file_size = $fileSize;
        $img->group = $request->has('group') ? $request->input('group') : 'banner';
        $img->disk = $request->has('disk') ? $request->input('disk') : 'campaign';
        $img->mime_type = $mime;
        $img->uuid = $uuid->toString();

        if ($img->save()) {
            return [
              'success' => true,
              'data' => $img,
              'code' => 200,
            ];
        } else {
            return [
              'success' => false,
              'message' => 'db_saving_issue',
              'code' => 500,
            ];
        }
    }

    public function attachedMedias($model): object
    {
        $medias = $model->getMedias();
        $collection = MediaResource::collection($medias);

        return $collection;
    }
}
