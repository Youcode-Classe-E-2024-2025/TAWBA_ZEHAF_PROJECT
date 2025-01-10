<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelHelper {
    public static function generateProjectReport($projectId) {
        $project = new Project();
        $task = new Task();

        $projectData = $project->getProjectById($projectId);
        $tasks = $task->getTasksByProjectId($projectId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set up headers
        $sheet->setCellValue('A1', 'Project Name');
        $sheet->setCellValue('B1', $projectData['name']);
        $sheet->setCellValue('A2', 'Task Title');
        $sheet->setCellValue('B2', 'Status');
        $sheet->setCellValue('C2', 'Assigned To');

        // Add task data
        $row = 3;
        foreach ($tasks as $task) {
            $sheet->setCellValue('A' . $row, $task['title']);
            $sheet->setCellValue('B' . $row, $task['status']);
            $sheet->setCellValue('C' . $row, $task['assigned_to']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'project_report_' . $projectId . '.xlsx';
        $writer->save($filename);

        return $filename;
    }
}