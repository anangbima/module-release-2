<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Modules\DesaModuleTemplate\Exporters\UserExporter;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Admin\StoreUserRequest;
use Modules\DesaModuleTemplate\Http\Requests\Web\Admin\UpdateUserRequest;
use Modules\DesaModuleTemplate\Http\Requests\Web\Shared\ImportRequest;
use Modules\DesaModuleTemplate\Importers\UserImporter;
use Modules\DesaModuleTemplate\Models\User;
use Modules\DesaModuleTemplate\Services\Admin\RoleService;
use Modules\DesaModuleTemplate\Services\Admin\UserService;
use Modules\DesaModuleTemplate\Services\Shared\ExportService;
use Modules\DesaModuleTemplate\Services\Shared\ImportService;
use Modules\DesaModuleTemplate\Services\Shared\LogActivityService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
        protected ExportService $exportService,
        protected LogActivityService $logActivityService,
        protected ImportService $importService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUser();
        $totalActive = $this->userService->countActive();
        $totalInactive = $this->userService->countInactive();

        $data = [
            'title' => 'User Management',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                    'icon' => 'icon-[mage--dashboard-chart]',
                ],
                [
                    'name' => 'User Management',
                    'url' => '#',
                    'icon' => 'icon-[stash--user-cog]',
                ],
            ],
            'users' => $users,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Add New User',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                    'icon' => 'icon-[mage--dashboard-chart]',
                ],
                [
                    'name' => 'Users',
                    'url' => route(desa_module_template_meta('kebab').'.admin.users.index'),
                    'icon' => 'icon-[stash--user-cog]',
                ],
                [
                    'name' => 'Add New User',
                    'url' => '#',
                    'icon' => 'icon-[pepicons-pop--plus]',
                ],
            ],
            'availableRoles' => $this->roleService->getAllRoles(),
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = $this->userService->getUserById($user->id);

        $data = [
            'user' => $user,
            'title' => 'User Details '.$user->name,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                    'icon' => 'icon-[mage--dashboard-chart]',
                ],
                [
                    'name' => 'Users',
                    'url' => route(desa_module_template_meta('kebab').'.admin.users.index'),
                    'icon' => 'icon-[stash--user-cog]',
                ],
                [
                    'name' => 'User Details',
                    'url' => '#',
                    'icon' => 'icon-[pepicons-pop--plus]',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.user.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = $this->userService->getUserById($user->id);

        $data = [
            'user' => $user,
            'title' => 'Edit User',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                    'icon' => 'icon-[mage--dashboard-chart]',
                ],
                [
                    'name' => 'Users',
                    'url' => route(desa_module_template_meta('kebab').'.admin.users.index'),
                    'icon' => 'icon-[stash--user-cog]',
                ],
                [
                    'name' => 'Edit User',
                    'url' => '#',
                    'icon' => 'icon-[pepicons-pop--plus]',
                ],
            ],
            'availableRoles' => $this->roleService->getAllRoles(),
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user->id, $request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user->id);

        return redirect()->route(desa_module_template_meta('kebab').'.admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
    * Export users to specified format.
    */
    public function export(string $type)
    {
        $data = $this->userService->getMappedUsers();

        $exporter = (new UserExporter($this->exportService, $data))
            ->setLogCallback(fn($format, $fileName) => $this->logActivityService->log(
                action: 'export_users',
                model: null,
                description: sprintf(
                    "The user list has been successfully exported in %s format. "
                    . "Your file \"%s\" is now ready to download and use.",
                    strtoupper($format),
                    $fileName
                ),
                before: [],
                after: ['file_name' => $fileName]
            ));

        $fileName = desa_module_template_fileName('users_export', $type);
        $pdfView = desa_module_template_meta('kebab').'::pdf.users';

        return match ($type) {
            'xlsx' => $exporter->exportToExcel(fileName: $fileName, format: Excel::XLSX),
            'csv'  => $exporter->exportToExcel(fileName: $fileName, format: Excel::CSV),
            'pdf'  => $exporter->exportToPdf(fileName: $fileName, view: $pdfView),
            'docx' => $exporter->exportToDocx(fileName: $fileName),
            'json' => $exporter->exportToJson(fileName: $fileName),
            default => redirect()->back()->with('error', 'Invalid export type.'),
        };
    }

    /**
     * Import users fr
     */
    public function import(ImportRequest $request)
    {
        $validated = $request->validated();

        $file = $validated['file'];

        $importer = (new UserImporter($this->importService, $this->userService))
            ->setLogCallback(fn($format, $fileName) => $this->logActivityService->log(
                action: 'import_users',
                model: null,
                description: sprintf(
                    "The user data has been successfully imported from a %s file. "
                    . "Source file: \"%s\".",
                    strtoupper($format),
                    $fileName
                ),
                before: [],
                after: ['file_name' => $fileName]
            ));

        $result = $importer->import($file);

        $importedCount = count($result['imported_ids'] ?? []);
        $errors = $result['errors'] ?? [];

        return redirect()->route(desa_module_template_meta('kebab').'.admin.users.index')
            ->with('success', $importedCount . ' users imported successfully.')
            ->with('import_errors', $errors);
    }
}
