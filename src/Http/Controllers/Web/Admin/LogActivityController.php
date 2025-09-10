<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Maatwebsite\Excel\Excel;
use Modules\DesaModuleTemplate\Exporters\LogActivityExporter;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\LogActivity;
use Modules\DesaModuleTemplate\Services\Admin\LogActivityService;
use Modules\DesaModuleTemplate\Services\Admin\RoleService;
use Modules\DesaModuleTemplate\Services\Shared\ExportService;
use Modules\DesaModuleTemplate\Services\Shared\LogActivityService as SharedLogActivityService;

class LogActivityController extends Controller
{
    public function __construct(
        protected LogActivityService $logActivityService,
        protected RoleService $roleService,
        protected ExportService $exportService,
        protected SharedLogActivityService $sharedLogActivityService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = $this->logActivityService->getAllLogs();

        $data = [
            'title' => 'Log Activity',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                    'icon' => 'icon-[mage--dashboard-chart]',
                ],
                [
                    'name' => 'Log Activity',
                    'url' => '#',
                    'icon' => 'icon-[fluent--document-data-32-regular]',
                ],
            ],
            'logs' => $logs,
            'roles' => $this->roleService->getAllRoles(),
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.log-activity.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(LogActivity $log)
    {
        $logActivity = $this->logActivityService->getLogById($log->id);

        $role = desa_module_template_auth_user()?->role;

        $data = [
            'title' => 'Log Activity Detail',
            'role' => $role,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Log Activity Detail',
                    'url' => '#',
                ],
            ],
            'log' => $logActivity,
        ];

        return view(desa_module_template_meta('kebab').'::web.shared.log-activity.show', $data);
    }

    /**
     * Export log activities.
     */
    public function export(string $type)
    {
        $data = $this->logActivityService->getMappedLogs();

        $exporter = (new LogActivityExporter($this->exportService, $data))
            ->setLogCallback(fn($format, $fileName) => $this->logActivityService->log(
                action: 'export_logs',
                model: null,
                description: sprintf(
                    "The activity logs have been successfully exported in %s format. "
                    . "Your file \"%s\" is now ready to download and view.",
                    strtoupper($format),
                    $fileName
                ),
                before: [],
                after: ['file_name' => $fileName],
            ));

        $fileName = desa_module_template_fileName('log_activities_export', $type);
        $pdfView = desa_module_template_meta('kebab').'::pdf.log-activities';

        return match ($type) {
            'xlsx' => $exporter->exportToExcel(fileName: $fileName, format: Excel::XLSX),
            'csv'  => $exporter->exportToExcel(fileName: $fileName, format: Excel::CSV),
            'pdf'  => $exporter->exportToPdf(fileName: $fileName, view: $pdfView, paperSize: ['A4', 'landscape']),
            'docx' => $exporter->exportToDocx(fileName: $fileName),
            'json' => $exporter->exportToJson(fileName: $fileName),
            default => redirect()->back()->with('error', 'Invalid export type.'),
        };
    }
}
