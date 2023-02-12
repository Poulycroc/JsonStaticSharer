<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class FileApiV1Controller extends Controller
{
    /**
     * Retrieve a JSON file by its name.
     *
     * @param  string  $fileName
     * @return \Illuminate\Http\Response
     */
    public function retrieve(string $fileName): JsonResponse
    {
        $foundFile = $this->fileExists($fileName);
        if (!$foundFile) {
            return response()->json([
                'message' => 'File not found.'
            ], 404);
        }

        // Get the file contents
        $fileContents = Storage::get($foundFile);

        // Decompress the gzip file if needed
        if (substr($fileName, -3) === '.gz') {
            $fileContents = gzdecode($fileContents);
        }

        return response()->json(json_decode($fileContents));
    }

    /**
     * Check if a file with a specific extension exists in storage.
     *
     * @param  string  $fileName
     * @param  string  $extension = 'json'
     * @return boolean
     */
    private function fileExists(string $fileName, string $extension = 'json', string $path = ''): bool
    {
        $jsonFile = "{$path}{$fileName}.{$extension}";
        $gzFile = "{$path}{$fileName}.{$extension}.gz";

        if (Storage::exists($jsonFile)) {
            return $jsonFile;
        } elseif (Storage::exists($gzFile)) {
            return $gzFile;
        }

        return false;
    }
}
