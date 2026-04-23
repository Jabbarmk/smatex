<?php
class SalesreportController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    /**
     * Overview: all salesmen with invoice summary
     * URL: salesreport
     */
    public function index() {
        require_once 'app/models/SalesReportModel.php';
        require_once 'app/models/SettingsModel.php';

        $model          = new SalesReportModel();
        $settingsModel  = new SettingsModel();
        $settings       = $settingsModel->getAllSettings();

        $data = [
            'title'          => 'Sales Report',
            'currency_symbol'=> $settings['currency_symbol'] ?? 'AED',
            'salesmen'       => $model->getSalesmenSummary(),
            'grand_totals'   => $model->getGrandTotals(),
        ];

        $this->view('salesreport/index', $data);
    }

    /**
     * Detail: one salesman's clients & invoices
     * URL: salesreport/detail/{id}
     */
    public function detail($id) {
        require_once 'app/models/SalesReportModel.php';
        require_once 'app/models/SettingsModel.php';

        $model         = new SalesReportModel();
        $settingsModel = new SettingsModel();
        $settings      = $settingsModel->getAllSettings();

        $salesman = $model->getSalesmanById($id);
        if (!$salesman) {
            $this->redirect('salesreport');
        }

        $clients = $model->getSalesmanDetail($id);

        // Attach individual invoices to each client row
        foreach ($clients as &$client) {
            $client['invoices'] = $model->getInvoicesForLead($client['lead_id']);
        }
        unset($client);

        // Summary totals for this salesman
        $totals = [
            'total_invoices' => array_sum(array_column($clients, 'invoice_count')),
            'paid_count'     => array_sum(array_column($clients, 'paid_count')),
            'paid_amount'    => array_sum(array_column($clients, 'paid_amount')),
            'unpaid_count'   => array_sum(array_column($clients, 'unpaid_count')),
            'unpaid_amount'  => array_sum(array_column($clients, 'unpaid_amount')),
            'partial_count'  => array_sum(array_column($clients, 'partial_count')),
            'partial_amount' => array_sum(array_column($clients, 'partial_amount')),
            'total_invoiced' => array_sum(array_column($clients, 'total_invoiced')),
        ];

        $data = [
            'title'          => 'Sales Report — ' . $salesman['name'],
            'currency_symbol'=> $settings['currency_symbol'] ?? 'AED',
            'salesman'       => $salesman,
            'clients'        => $clients,
            'totals'         => $totals,
        ];

        $this->view('salesreport/detail', $data);
    }

    /**
     * Export overview (all salesmen) to Excel
     * URL: salesreport/exportOverview
     */
    public function exportOverview() {
        require_once 'app/models/SalesReportModel.php';
        require_once 'app/models/SettingsModel.php';

        $model         = new SalesReportModel();
        $settingsModel = new SettingsModel();
        $settings      = $settingsModel->getAllSettings();
        $currency      = $settings['currency_symbol'] ?? 'AED';

        $salesmen = $model->getSalesmenSummary();
        $totals   = $model->getGrandTotals();

        $rows = [];
        $rows[] = ['Salesmen Invoice Summary Report — Generated: ' . date('d M Y H:i')];
        $rows[] = [];
        $rows[] = [
            'Salesman', 'Role', 'Total Leads', 'Won', 'Lost',
            'Total Invoices',
            'Paid (#)', 'Paid (' . $currency . ')',
            'Unpaid (#)', 'Unpaid (' . $currency . ')',
            'Partial (#)', 'Partial (' . $currency . ')',
            'Total Invoiced (' . $currency . ')',
        ];

        foreach ($salesmen as $r) {
            $rows[] = [
                $r['salesman_name'], $r['role'],
                $r['total_leads'], $r['won_leads'], $r['lost_leads'],
                $r['total_invoices'],
                $r['paid_count'],    number_format($r['paid_amount'],    2, '.', ''),
                $r['unpaid_count'],  number_format($r['unpaid_amount'],  2, '.', ''),
                $r['partial_count'], number_format($r['partial_amount'], 2, '.', ''),
                number_format($r['total_invoiced'], 2, '.', ''),
            ];
        }

        // Totals
        $rows[] = [];
        $rows[] = [
            'TOTAL', '', '', '', '',
            $totals['total_invoices'],
            $totals['paid_count'],    number_format($totals['paid_amount'],    2, '.', ''),
            $totals['unpaid_count'],  number_format($totals['unpaid_amount'],  2, '.', ''),
            $totals['partial_count'], number_format($totals['partial_amount'], 2, '.', ''),
            number_format($totals['total_invoiced'], 2, '.', ''),
        ];

        $filename = 'sales_report_overview_' . date('Y-m-d');
        $this->outputExcel($rows, $filename);
    }

    /**
     * Export detail view (one salesman's invoices) to Excel
     * URL: salesreport/exportDetail/{id}
     */
    public function exportDetail($id) {
        require_once 'app/models/SalesReportModel.php';
        require_once 'app/models/SettingsModel.php';

        $model         = new SalesReportModel();
        $settingsModel = new SettingsModel();
        $settings      = $settingsModel->getAllSettings();
        $currency      = $settings['currency_symbol'] ?? 'AED';

        $salesman = $model->getSalesmanById($id);
        if (!$salesman) $this->redirect('salesreport');

        $invoices = $model->getSalesmanInvoicesFlat($id);

        $rows = [];
        $rows[] = ['Sales Detail Report — Salesman: ' . $salesman['name'] . ' — Generated: ' . date('d M Y H:i')];
        $rows[] = [];
        $rows[] = [
            'Client Name', 'Company', 'Phone', 'Invoice #',
            'Invoice Total (' . $currency . ')',
            'Status',
            'Amount Received (' . $currency . ')',
            'Balance Due (' . $currency . ')',
            'Due Date', 'Invoice Date',
        ];

        $totalInvoiced = 0;
        $totalReceived = 0;
        $totalBalance  = 0;

        foreach ($invoices as $r) {
            $rows[] = [
                $r['client_name'],
                $r['company_name'] ?? '',
                $r['client_phone'] ?? '',
                $r['invoice_no'],
                number_format($r['grand_total'],      2, '.', ''),
                $r['invoice_status'],
                number_format($r['amount_received'],  2, '.', ''),
                number_format($r['balance_due'],      2, '.', ''),
                $r['due_date'] ?? '',
                date('d M Y', strtotime($r['created_at'])),
            ];
            $totalInvoiced += $r['grand_total'];
            $totalReceived += $r['amount_received'];
            $totalBalance  += $r['balance_due'];
        }

        $rows[] = [];
        $rows[] = [
            'TOTAL', '', '', '',
            number_format($totalInvoiced, 2, '.', ''),
            '',
            number_format($totalReceived, 2, '.', ''),
            number_format($totalBalance,  2, '.', ''),
            '', '',
        ];

        $filename = 'sales_detail_' . preg_replace('/[^a-z0-9]/i', '_', $salesman['name']) . '_' . date('Y-m-d');
        $this->outputExcel($rows, $filename);
    }

    /**
     * Helper: stream tab-delimited XLS file
     */
    private function outputExcel($rows, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM
        foreach ($rows as $row) {
            fputcsv($output, $row, "\t");
        }
        fclose($output);
        exit;
    }
}
