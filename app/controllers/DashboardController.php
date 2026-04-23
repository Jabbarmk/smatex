<?php
class DashboardController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/DashboardModel.php';
        $dashboardModel = new DashboardModel();

        // Get Summary Stats
        $stats = $dashboardModel->getCounts();

        // Get Monthly Sales (Last 6 months)
        $salesData    = $dashboardModel->getMonthlySales();
        $salesLabels  = json_encode($salesData['labels']);
        $salesRevenue = json_encode($salesData['revenue']);

        // Get Lead Distribution
        $leadStatusData = $dashboardModel->getLeadStatusDistribution();
        $leadStatuses   = [];
        $leadCounts     = [];
        foreach ($leadStatusData as $row) {
            $leadStatuses[] = $row['status'];
            $leadCounts[]   = $row['count'];
        }

        // Get Expense Breakdown
        $expenseData       = $dashboardModel->getExpenseBreakdown();
        $expenseCategories = [];
        $expenseAmounts    = [];
        foreach ($expenseData as $row) {
            $expenseCategories[] = $row['category'];
            $expenseAmounts[]    = $row['total'];
        }

        // NEW: Report Data
        $salesmanReport    = $dashboardModel->getSalesmanReport();
        $invoiceReport     = $dashboardModel->getInvoiceReport();
        $leadsReport       = $dashboardModel->getLeadsReport();
        $serviceItemReport = $dashboardModel->getServiceItemReport();

        // Get Settings
        require_once 'app/models/SettingsModel.php';
        $settingsModel  = new SettingsModel();
        $settings       = $settingsModel->getAllSettings();
        $currencySymbol = $settings['currency_symbol'] ?? 'AED';

        $data = [
            'total_leads'        => $stats['total_leads'],
            'won_leads'          => $stats['won_leads'],
            'revenue'            => $stats['revenue'],
            'expenses'           => $stats['expenses'] ?? 0,
            'expected_value'     => $stats['expected_value'] ?? 0,
            'outstanding_balance'=> $stats['outstanding_balance'] ?? 0,
            'currency_symbol'    => $currencySymbol,
            'salesLabels'        => $salesLabels,
            'salesRevenue'       => $salesRevenue,
            'leadStatusLabels'   => json_encode($leadStatuses),
            'leadStatusCounts'   => json_encode($leadCounts),
            'expenseLabels'      => json_encode($expenseCategories),
            'expenseValues'      => json_encode($expenseAmounts),
            // Report tables
            'salesman_report'     => $salesmanReport,
            'invoice_report'      => $invoiceReport,
            'leads_report'        => $leadsReport,
            'service_item_report' => $serviceItemReport,
            'title'               => 'Dashboard',
        ];
        
        $this->view('dashboard/index', $data);
    }

    /**
     * Export a report as Excel (.xlsx via CSV-compatible format)
     * URL: dashboard/exportExcel/{type}
     * type: salesman | invoices | leads
     */
    public function exportExcel($type = 'salesman') {
        require_once 'app/models/DashboardModel.php';
        require_once 'app/models/SettingsModel.php';

        $dashboardModel = new DashboardModel();
        $settingsModel  = new SettingsModel();
        $settings       = $settingsModel->getAllSettings();
        $currency       = $settings['currency_symbol'] ?? 'AED';

        $rows     = [];
        $filename = 'report';

        switch ($type) {
            case 'salesman':
                $filename = 'salesmen_report_' . date('Y-m-d');
                $rows[]   = ['Salesman', 'Role', 'Total Leads', 'Won Leads', 'Total Invoices',
                             'Paid (' . $currency . ')', 'Unpaid (' . $currency . ')',
                             'Partial (' . $currency . ')', 'Total Invoiced (' . $currency . ')'];
                foreach ($dashboardModel->getSalesmanReport() as $r) {
                    $rows[] = [
                        $r['salesman_name'], $r['role'],
                        $r['total_leads'], $r['won_leads'], $r['total_invoices'],
                        number_format($r['paid_amount'],   2, '.', ''),
                        number_format($r['unpaid_amount'],  2, '.', ''),
                        number_format($r['partial_amount'], 2, '.', ''),
                        number_format($r['total_invoiced'], 2, '.', ''),
                    ];
                }
                break;

            case 'invoices':
                $filename = 'invoice_report_' . date('Y-m-d');
                $rows[]   = ['Category', 'Count', 'Amount (' . $currency . ')'];
                $inv      = $dashboardModel->getInvoiceReport();
                $rows[]   = ['Total', $inv['total_count'],  number_format($inv['total_amount'],   2, '.', '')];
                $rows[]   = ['Paid',  $inv['paid_count'],   number_format($inv['paid_amount'],    2, '.', '')];
                $rows[]   = ['Unpaid',$inv['unpaid_count'], number_format($inv['unpaid_amount'],  2, '.', '')];
                $rows[]   = ['Partial',$inv['partial_count'],number_format($inv['partial_amount'],2, '.', '')];
                break;

            case 'leads':
            default:
                $filename = 'leads_report_' . date('Y-m-d');
                $rows[]   = ['Salesman', 'New', 'Contacted', 'Qualified', 'Proposal Sent',
                             'Won', 'Lost', 'Total', 'Expected Value (' . $currency . ')'];
                foreach ($dashboardModel->getLeadsReport() as $r) {
                    $rows[] = [
                        $r['salesman_name'],
                        $r['new_leads'], $r['contacted'], $r['qualified'], $r['proposal_sent'],
                        $r['won'], $r['lost'], $r['total_leads'],
                        number_format($r['total_expected_value'], 2, '.', ''),
                    ];
                }
                break;

            case 'service_items':
                $filename = 'service_item_report_' . date('Y-m-d');
                $rows[]   = [
                    'Service / Item', 'Total Invoices',
                    'Paid (#)', 'Paid (' . $currency . ')',
                    'Unpaid (#)', 'Unpaid (' . $currency . ')',
                    'Partial (#)', 'Partial (' . $currency . ')',
                    'Total (' . $currency . ')',
                ];
                foreach ($dashboardModel->getServiceItemReport() as $r) {
                    $rows[] = [
                        $r['service_name'],
                        $r['total_invoices'],
                        $r['paid_count'],    number_format($r['paid_amount'],    2, '.', ''),
                        $r['unpaid_count'],  number_format($r['unpaid_amount'],  2, '.', ''),
                        $r['partial_count'], number_format($r['partial_amount'], 2, '.', ''),
                        number_format($r['total_amount'], 2, '.', ''),
                    ];
                }
                break;
        }


        // Output as CSV with Excel MIME type
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");
        foreach ($rows as $row) {
            fputcsv($output, $row, "\t");
        }
        fclose($output);
        exit;
    }
}
