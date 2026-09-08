-- Projects Statement: link invoices to the quotation (project) they were raised against.
-- A "project" = an Approved quotation.

ALTER TABLE invoices
    ADD COLUMN quotation_id INT NULL DEFAULT NULL AFTER lead_id,
    ADD KEY idx_invoices_quotation_id (quotation_id);

-- Backfill: for clients that have exactly one Approved quotation,
-- link their existing invoices to that quotation.
UPDATE invoices i
JOIN (
    SELECT lead_id, MIN(id) AS quotation_id
    FROM quotations
    WHERE status = 'Approved'
    GROUP BY lead_id
    HAVING COUNT(*) = 1
) q ON q.lead_id = i.lead_id
SET i.quotation_id = q.quotation_id
WHERE i.quotation_id IS NULL;
