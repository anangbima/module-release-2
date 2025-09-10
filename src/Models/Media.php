<?php

namespace Modules\ModuleRelease2\Models;

class Media extends BaseModel
{
    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config('module_release_2.tables.media', 'media');
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
        'disk',
        'collection',
        'usage',
    ];

    public function media()
    {
        return $this->morphTo();
    }

    public function usages()
    {
        return $this->hasMany(MediaUsage::class, 'media_id');
    }
}
