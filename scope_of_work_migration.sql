-- Scope of Work: optional rich-text section printed on its own page in the quotation document.
ALTER TABLE quotations
    ADD COLUMN scope_of_work_title VARCHAR(150) NULL DEFAULT 'Scope of Work' AFTER terms_conditions,
    ADD COLUMN scope_of_work LONGTEXT NULL DEFAULT NULL AFTER scope_of_work_title;
