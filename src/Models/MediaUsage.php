<?php

namespace Modules\ModuleRelease2\Models;

class MediaUsage extends BaseModel
{
    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config('module_release_2.tables.media_usages', 'media_usages');
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'media_id',
        'model_type',
        'model_id',
        'usage',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
