<?php

namespace Modules\DesaModuleTemplate\Models;

use DesaDigitalSupport\RegionManager\Services\RegionService;
use Modules\DesaModuleTemplate\Models\BaseAuthModel;
use Modules\DesaModuleTemplate\Traits\HasSlug;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as AuthMustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Modules\DesaModuleTemplate\Database\Factories\UserFactory;
use Modules\DesaModuleTemplate\Notifications\ResetPasswordNotification;
use Modules\DesaModuleTemplate\Notifications\VerifyEmailNotification;
use Modules\DesaModuleTemplate\Traits\HasMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseAuthModel implements AuthMustVerifyEmail, JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes, MustVerifyEmail, HasSlug, HasMedia;

    protected $guard_name = 'desa_module_template_web';
    protected $slugSource = 'name';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('users.tables.users', 'users');
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'status',
        'slug',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
    ];

    protected $casts = [
        'last_activity'     => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Custom= notification method to send password reset notification.
     */
    public function sendPasswordResetNotification($token, $guard = 'web')
    {
        $this->notify(new ResetPasswordNotification($token, $guard));
    }

    /**
     * Custom notification method to send email verification notification.
     */
    public function sendEmailVerificationNotification($guard = 'web')
    {
        $this->notify(new VerifyEmailNotification($guard));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the user's role.
     *
     * @return string|null
     */
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    /**
     * Check if the user has a verified email.
     *
     * @return bool
     */
    public function getIsVerifiedAttribute()
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        $media = $this->getSingleMedia('profile_image');

        if ($media) {
            return asset('storage/' . $media->path);
        }
        
        return null;
    }

    /**
     * Get province name
     */
    public function getProvinceNameAttribute()
    {
        return app(RegionService::class)->getProvinceByCode($this->province_code)->name ?? null;
    }

    /**
     * Get city name
     */
    public function getCityNameAttribute()
    {
        return app(RegionService::class)->getCityByCode($this->city_code)->name ?? null;
    }

    /**
     * Get district name
     */
    public function getDistrictNameAttribute()
    {
        return app(RegionService::class)->getDistrictByCode($this->district_code)->name ?? null;
    }

    /**
     * Get village name
     */
    public function getVillageNameAttribute()
    {
        return app(RegionService::class)->getVillageByCode($this->village_code)->name ?? null;
    }

    /**
     * Get last activity user
     *  */ 
    public function lastActivity()
    {
        return $this->hasOne(LogActivity::class)
                    ->latestOfMany('logged_at');
    }

    /**
     * Override roles relation untuk module template
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            config('desamoduletemplate.permission.table_names.model_has_roles', 'desa_module_template_model_has_roles'),
            'model_id',
            'role_id'
        )
        ->withPivot('model_type')
        ->wherePivot('model_type', static::class);
    }


    /**
     * Override permissions relation untuk module template
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            config('desamoduletemplate.permission.table_names.model_has_permissions', 'desa_module_template_model_has_permissions'),
            'model_id',
            'permission_id'
        );
    }

    /**
     * Assign a role to the user using custom roles relation.
     *
     * @param string|Role $role Name or ID (ULID) or Role instance
     */
    public function assignRole($role)
    {
        // Jika sudah instance Role
        if ($role instanceof Role) {
            $roleModel = $role;
        }
        // Jika string, coba ID dulu
        elseif (is_string($role)) {
            $roleModel = Role::where('id', $role)
                            ->where('guard_name', $this->guard_name)
                            ->first();

            // Jika ID tidak ditemukan, anggap string itu nama role
            if (!$roleModel) {
                $roleModel = Role::where('name', $role)
                                ->where('guard_name', $this->guard_name)
                                ->firstOrFail();
            }
        } else {
            throw new \InvalidArgumentException('Invalid role type passed to assignRole.');
        }

        // Attach role ke user menggunakan pivot custom
        $this->roles()->syncWithoutDetaching([
            $roleModel->id => ['model_type' => static::class]
        ]);

        return $this;
    }

    /**
     * Sync user roles using custom roles relation.
     *
     * @param array|string|int|Role $roles
     */
    public function syncRoles($roles)
    {
        $roles = collect(Arr::wrap($roles))->map(function ($role) {
            // Jika sudah instance Role
            if ($role instanceof Role) {
                return $role;
            }

            // Jika string, coba ID dulu
            if (is_string($role)) {
                $roleModel = Role::where('id', $role)
                                ->where('guard_name', $this->guard_name)
                                ->first();

                if (!$roleModel) {
                    $roleModel = Role::where('name', $role)
                                    ->where('guard_name', $this->guard_name)
                                    ->firstOrFail();
                }

                return $roleModel;
            }

            throw new \InvalidArgumentException('Invalid role type passed to syncRoles.');
        });

        // Hapus semua role lama dan attach yang baru
        $this->roles()->detach();
        foreach ($roles as $role) {
            $this->roles()->syncWithoutDetaching([
                $role->id => ['model_type' => static::class]
            ]);
        }

        return $this;
    }

    /**
     * Remove role from user using custom roles relation.
     */
    public function removeRole($role)
    {
        // Jika sudah instance Role
        if ($role instanceof Role) {
            $roleModel = $role;
        }
        // Jika string, coba ID dulu
        elseif (is_string($role)) {
            $roleModel = Role::where('id', $role)
                            ->where('guard_name', $this->guard_name)
                            ->first();

            // Jika ID tidak ditemukan, anggap string itu nama role
            if (!$roleModel) {
                $roleModel = Role::where('name', $role)
                                ->where('guard_name', $this->guard_name)
                                ->firstOrFail();
            }
        } else {
            throw new \InvalidArgumentException('Invalid role type passed to removeRole.');
        }

        // Detach role dari user
        $this->roles()->detach($roleModel->id);

        return $this;
    }

    /**
     * Remove a permission from the user using custom pivot table.
     *
     * @param string|int|Permission $permission Name, ID, atau Permission instance
     */
    public function removePermission($permission)
    {
        if ($permission instanceof Permission) {
            $perm = $permission;
        } elseif (is_string($permission) || is_int($permission)) {
            $perm = Permission::where('id', $permission)
                            ->orWhere('name', $permission)
                            ->firstOrFail();
        } else {
            throw new \InvalidArgumentException('Invalid permission type passed to removePermission.');
        }

        $this->permissions()->detach($perm->id);

        return $this;
    }
}