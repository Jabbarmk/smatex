-- 1. Date-wise Report (Leads, Sales, Expenses, Profit)
-- Replace :from_date and :to_date with actual values
SELECT 
    DATE(created_at) as date,
    (SELECT COUNT(*) FROM leads WHERE DATE(created_at) = date) as total_leads,
    (SELECT COALESCE(SUM(grand_total), 0) FROM invoices WHERE status = 'Paid' AND DATE(updated_at) = date) as total_sales,
    (SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE DATE(expense_date) = date) as total_expenses,
    (
        (SELECT COALESCE(SUM(grand_total), 0) FROM invoices WHERE status = 'Paid' AND DATE(updated_at) = date) - 
        (SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE DATE(expense_date) = date)
    ) as net_profit
FROM leads 
WHERE created_at BETWEEN '2023-01-01' AND '2023-12-31'
GROUP BY DATE(created_at);


-- 2. Month-wise Report (Revenue, Expense, Net)
SELECT 
    DATE_FORMAT(updated_at, '%Y-%m') as month,
    SUM(grand_total) as revenue,
    (SELECT SUM(amount) FROM expenses WHERE DATE_FORMAT(expense_date, '%Y-%m') = month) as expense,
    (SUM(grand_total) - (SELECT COALESCE(SUM(amount),0) FROM expenses WHERE DATE_FORMAT(expense_date, '%Y-%m') = month)) as net_profit
FROM invoices 
WHERE status = 'Paid'
GROUP BY month;


-- 3. Sales Manager Report (Leads, Won, Revenue, Conversion Rate)
SELECT 
    u.name as sales_manager,
    COUNT(l.id) as total_leads,
    SUM(CASE WHEN l.status = 'Won' THEN 1 ELSE 0 END) as won_leads,
    COALESCE(SUM(i.grand_total), 0) as revenue_generated,
    (SUM(CASE WHEN l.status = 'Won' THEN 1 ELSE 0 END) / COUNT(l.id)) * 100 as conversion_rate
FROM users u
LEFT JOIN leads l ON u.id = l.sales_manager_id
LEFT JOIN invoices i ON l.id = i.lead_id AND i.status = 'Paid'
WHERE u.role IN ('Sales', 'Admin')
GROUP BY u.id;


-- 4. Emirates-wise Report
SELECT 
    emirates,
    COUNT(*) as lead_count,
    SUM(CASE WHEN status = 'Won' THEN 1 ELSE 0 END) as won_count,
    COALESCE(SUM(expected_value), 0) as total_expected_value
FROM leads
GROUP BY emirates;
