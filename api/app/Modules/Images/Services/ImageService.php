<?php

namespace App\Modules\Images\Services;

use App\Modules\Core\Services\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService extends Service
{


    public function __construct()
    {
        parent::__construct(null);
    }

    public function getCategoryIconPath($fileName): ?string
    {
        return Storage::disk('public')->exists('images/categoryIcons/' . $fileName)
            ? storage_path('app/public/images/categoryIcons/' . $fileName)
            : null;
    }

    public function upload(string $base64Image, string $categoryFolderName): string
    {
        $decodedImage = $this->decodeImage($base64Image);
        $uniqueFileName = $this->generateUniqueFileName($this->getImageExtension($base64Image));
        return $this->storeImage($decodedImage, $categoryFolderName, $uniqueFileName);
    }

    public function delete($oldImage): void
    {
        Storage::disk('public')->delete($oldImage);
    }


    private function decodeImage(string $base64Image): string
    {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
    }

    private function generateUniqueFileName(string $extension): string
    {
        return Str::random(40) . '.' . $extension;
    }

    private function getImageExtension(string $base64Image): string
    {
        $mime = explode(';', $base64Image)[0];
        return explode('/', $mime)[1];
    }

    private function storeImage(string $decodedImage, string $categoryFolderName, string $uniqueFileName): string
    {
        $path = 'images/' . $categoryFolderName . '/' . $uniqueFileName;
        Storage::disk('public')->put($path, $decodedImage);

        return $path;
    }
}
