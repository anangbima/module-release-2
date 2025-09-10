<?php

namespace Modules\ModuleRelease2\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasFactory, HasUlids;

    protected $connection;
    protected $table;
    
    /**
     * Connection for every model
     * 
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config(module_release_2_meta('snake').'.database.database_connection', 'module_release_2');
        $this->table = $this->resolveTableName();
    }

    /**
     * Resolve table name from config, override this in child class if needed.
     * 
     */
    protected function resolveTableName(): string
    {
        // Default behavior, override in child if custom logic needed
        return $this->table ?? '';
    }
}