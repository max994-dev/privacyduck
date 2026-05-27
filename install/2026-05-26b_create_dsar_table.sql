-- =============================================================================
-- Migration: UK GDPR Phase 1.5 - DSAR (Data Subject Access Request) intake table
-- Date:      2026-05-26
-- Run on:    Production MySQL (privacyduck DB)
-- Rationale: UK GDPR Art. 15-22 grants data subjects 8 rights. We need a place
--            to capture incoming requests, track the 1-month SLA (Art. 12), and
--            give staff a queue to work from.
-- Order:     Run BEFORE deploying the Phase 1.5 PHP (PrivacyRequest pages +
--            privacy_request_submit controller). PHP references this table.
-- =============================================================================

CREATE TABLE dsar_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference VARCHAR(20) NOT NULL UNIQUE
    COMMENT 'Public ref shown to the user (e.g. PD-DSAR-7A4F3B)',
  request_type ENUM(
    'access','rectification','erasure','restrict',
    'portability','object','no_automated','withdraw_consent'
  ) NOT NULL
    COMMENT 'Which of the 8 UK GDPR rights they are exercising',
  email VARCHAR(255) NOT NULL,
  name VARCHAR(200) NULL,
  country VARCHAR(8) NULL,
  capacity ENUM('self','representative') NOT NULL DEFAULT 'self'
    COMMENT 'self = data subject themselves; representative = third party acting for them',
  matched_user_id INT NULL
    COMMENT 'Set on submit if email matches a row in users.id. Informational, not enforced as FK',
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(500) NULL,
  received_at DATETIME NOT NULL,
  deadline_at DATETIME NOT NULL
    COMMENT 'UK GDPR Art. 12: one calendar month from received_at',
  status ENUM('open','in_progress','completed','rejected','extended')
    NOT NULL DEFAULT 'open',
  staff_notes TEXT NULL,
  completed_at DATETIME NULL,
  KEY ix_status_deadline (status, deadline_at),
  KEY ix_email (email),
  KEY ix_received (received_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
