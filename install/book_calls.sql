-- Run once on the PrivacyDuck database (or rely on auto-create from book_call_ensure_table).
CREATE TABLE IF NOT EXISTS book_calls (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL,
    scheduled_start_utc DATETIME NOT NULL,
    scheduled_end_utc DATETIME NOT NULL,
    odoo_event_id VARCHAR(64) DEFAULT NULL,
    verification_token CHAR(64) NOT NULL,
    reminder_sent TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    KEY idx_user (user_id),
    KEY idx_start (scheduled_start_utc),
    KEY idx_reminder (reminder_sent, scheduled_start_utc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
