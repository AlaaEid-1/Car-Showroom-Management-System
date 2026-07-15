<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUpload
{
    public function handle(?UploadedFile $file, string $path = 'uploads', string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        return $file->store($path, $disk);
    }

    public function multiple(?array $files, string $path = 'uploads', string $disk = 'public'): array
    {
        if (!$files) {
            return [];
        }

        $paths = [];

        foreach ($files as $file) {
            $paths[] = $file->store($path, $disk);
        }

        return $paths;
    }
}
