<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class JsonFileController extends Controller
{
    /**
     * Store data from a user in a JSON file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the user input
        $validatedData = $request->validate([
            'data' => 'required'
        ]);

        $fileName = $request->fileName;

        $currentProjectUuid = 'azdazdazdazdfaeff';
        $fileGroup = 'christmas';

        $storagePath = join('/', [
            'app/data',
            $currentProjectUuid,
            $fileGroup,
        ]);

        // Sanitize the user input to prevent malicious data
        $data = $this->sanitizeData($validatedData['data']);

        // Compress the data
        $compressedData = gzcompress(json_encode($data));

        // Create a directory to store the file if it doesn't exist
        $path = storage_path($storagePath);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        // Store the compressed data in a JSON file
        Storage::put("{$storagePath}/{$fileName}.json.gz", $compressedData);

        return response()->json([
            'message' => 'Data stored successfully.'
        ], HttpResponse::HTTP_OK);
    }

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
     * Sanitize the data to prevent malicious input.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function sanitizeData($data)
    {
        if (is_string($data)) {
            // Remove HTML and PHP tags from strings
            return strip_tags($data);
        }

        if (is_array($data)) {
            // Recursively sanitize arrays
            return array_map(function ($item) {
                return $this->sanitizeData($item);
            }, $data);
        }

        // No action needed for other data types
        return $data;
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
