<?php

/**
 * Ensures book_calls table exists (idempotent).
 */
function book_call_ensure_table(mysqli $conn): void
{
    $conn->query(
        "CREATE TABLE IF NOT EXISTS book_calls (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            email VARCHAR(255) NOT NULL,
            scheduled_start_utc DATETIME NOT NULL,
            scheduled_end_utc DATETIME NOT NULL,
            odoo_event_id VARCHAR(64) DEFAULT NULL,
            odoo_lead_id VARCHAR(64) DEFAULT NULL,
            verification_token CHAR(64) NOT NULL,
            reminder_sent TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            KEY idx_user (user_id),
            KEY idx_start (scheduled_start_utc),
            KEY idx_reminder (reminder_sent, scheduled_start_utc)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );
    book_call_migrate_odoo_lead_column($conn);
}

/**
 * Add odoo_lead_id for DBs created before CRM sync existed.
 */
function book_call_migrate_odoo_lead_column(mysqli $conn): void
{
    $r = $conn->query("SHOW COLUMNS FROM book_calls LIKE 'odoo_lead_id'");
    if ($r && $r->num_rows === 0) {
        $conn->query('ALTER TABLE book_calls ADD COLUMN odoo_lead_id VARCHAR(64) DEFAULT NULL AFTER odoo_event_id');
    }
    if ($r) {
        $r->free();
    }
}
