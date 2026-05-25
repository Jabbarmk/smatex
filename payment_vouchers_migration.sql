-- Payment Vouchers Migration
-- Run this script to add payment voucher tables to the database

CREATE TABLE IF NOT EXISTS `payment_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(50) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(50) NOT NULL DEFAULT 'Cash',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `reference_number` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_no` (`voucher_no`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `fk_pv_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pv_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `payment_voucher_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `voucher_id` (`voucher_id`),
  CONSTRAINT `fk_pvi_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `payment_vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
