<?php

namespace Modules\ModuleRelease2\Models;

use Modules\ModuleRelease2\Database\Factories\LogActivityFactory;

class LogActivity extends BaseModel
{
    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config(module_release_2_meta('snake').'.tables.log_activities', 'log_activities');
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'action',
        'data_before',
        'data_after',
        'logged_at',
        'description',
        'ip_address',
        'user_agent',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
        'logged_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return LogActivityFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
