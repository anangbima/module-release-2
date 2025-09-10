<?php

namespace Modules\ModuleRelease2\Session;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleSessionHandler extends DatabaseSessionHandler
{
    /**
     * Override untuk memastikan user_id selalu terisi dari guard modul
     */
    public function write($sessionId, $data): bool
    {
        Log::info('Custom ModuleSessionHandler dipanggil', [
            'guard_user' => Auth::guard('module_release_2_web')->id(),
        ]);

        // Ambil user dari guard modul
        $userId = Auth::guard('module_release_2_web')->id();

        $payload = $this->getDefaultPayload($data);

        if ($userId) {
            $payload['user_id'] = $userId; // ULID langsung
        }

        if (! $this->exists) {
            $this->read($sessionId);
        }

        if ($this->exists) {
            $this->performUpdate($sessionId, $payload);
        } else {
            $this->performInsert($sessionId, $payload);
        }

        return $this->exists = true;
    }
}
