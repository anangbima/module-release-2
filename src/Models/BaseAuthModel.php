<?php

namespace Modules\DesaModuleTemplate\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseAuthModel extends Authenticatable
{
    use HasFactory, HasUlids;

    protected $connection;
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config(desa_module_template_meta('snake').'.database.database_connection', 'desa_module_template');
        $this->table = $this->resolveTableName();
    }

    protected function resolveTableName(): string
    {
        return $this->table ?? '';
    }
}
