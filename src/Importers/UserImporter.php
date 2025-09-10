<?php 

namespace Modules\ModuleRelease2\Importers;

use Illuminate\Http\UploadedFile;
use Modules\ModuleRelease2\Services\Admin\UserService;
use Modules\ModuleRelease2\Services\Shared\ImportService;

class UserImporter extends BaseImporter
{
    public function __construct(
        protected ImportService $importService,
        protected UserService $userService
    ) {
        parent::__construct($importService);
    }

    public function import(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $data = match ($ext) {
            'csv' => $this->importService->parseCsv($file),
            'xlsx' => $this->importService->parseXlsx($file),
            'json' => $this->importService->parseJson($file),
            'pdf' => $this->importService->parsePdfOcr($file),
            // 'pdf' => $this->importService->parsePdf($file),
            default => throw new \InvalidArgumentException("Unsupported file type: {$ext}"),
        };

        $imported = [];
        $errors = [];

        foreach ($data as $index => $row) {

            $email = strtolower($row['email'] ?? ''); 

            if (!$email) {
                $errors[] = "Row {$index}: Email is required.";
                continue;
            }

            if ($this->userService->existsByEmail($email)) {
                $errors[] = "Row {$index}: User with email {$email} already exists.";
                continue;
            }

            try {
                $user = [
                    'name' => $row['name'] ?? null,
                    'email' => $email,
                    'role' => $row['role'] ?? null,
                    'status' => $row['status'] ?? 'active',
                    'password' => 'password',
                    'province' => $row['province'] ?? null,
                    'city' => $row['city'] ?? null,
                    'district' => $row['district'] ?? null,
                    'village' => $row['village'] ?? null,
                ];

                $createdUser = $this->userService->createFromImport($user);

                $imported[] = $createdUser->id;
            } catch (\Throwable $e) {
                $errors[] = [
                    'row' => $index,
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        $this->log('import', $file->getClientOriginalName());

        return [
            'imported_ids' => $imported,
            'errors' => $errors,
        ];
    }
}