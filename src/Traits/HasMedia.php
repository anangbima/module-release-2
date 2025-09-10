<?php

namespace Modules\ModuleRelease2\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Modules\ModuleRelease2\Models\Media;
use Modules\ModuleRelease2\Models\MediaUsage;

trait HasMedia
{
    public function mediaUsages(): MorphMany
    {
        return $this->morphMany(MediaUsage::class, 'model');
    }

    public function media(): Collection
    {
        $mediaIds = $this->mediaUsages()->pluck('media_id');
        return Media::whereIn('id', $mediaIds)->get();
    }

    public function getMediaByUsage(string $usage): Collection
    {
        $mediaIds = $this->mediaUsages()->where('usage', $usage)->pluck('media_id');
        return Media::whereIn('id', $mediaIds)->get();
    }

    public function getSingleMedia(string $usage): ?Media
    {
        $mediaId = $this->mediaUsages()->where('usage', $usage)->value('media_id');
        return $mediaId ? Media::find($mediaId) : null;
    }
}
