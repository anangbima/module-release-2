<?php

namespace Modules\ModuleRelease2\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\ModuleRelease2\Models\Media;
use Modules\ModuleRelease2\Repositories\Interfaces\MediaRepositoryInterface;
use Modules\ModuleRelease2\Repositories\Interfaces\MediaUsageRepositoryInterface;

class MediaService
{
    public function __construct(
        protected MediaRepositoryInterface $mediaRepository,
        protected MediaUsageRepositoryInterface $mediaUsageRepository,
    ){}

    protected function determineMediaType(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'webp'],
            'document' => ['pdf', 'docx', 'xls', 'xlsx', 'pptx'],
            'video' => ['mp4', 'mov', 'avi'],
            'archive' => ['zip', 'rar', '7z'],
        ];

        foreach ($types as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }

        return 'other';
    }

    public function upload(
        UploadedFile $file, 
        object $model, 
        string $usage = 'default',
        string $collection = 'default',
        string $disk = 'public',
    ){
        $modelName = lcfirst(class_basename($model));
        $mediaType = $this->determineMediaType($file);
        $path = $file->store(module_release_2_meta('kebab').'/'.$mediaType.'/'.$modelName, $disk);

        $media = $this->mediaRepository->create([
            'path' => $path,
            'disk' => $disk,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'usage' => $usage,
            'collection' => $collection,
        ]);

        $this->mediaUsageRepository->create([
            'media_id' => $media->id,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'usage' => $usage,
        ]);

        return $media;
    }

    public function remove(Media $media)
    {
        if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->usages()->delete();
        $media->delete();
    }
    
}