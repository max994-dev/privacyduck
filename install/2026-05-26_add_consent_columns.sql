-- =============================================================================
-- Migration: UK GDPR Phase 1.4 - add consent audit columns to users table
-- Date:      2026-05-26
-- Run on:    Production MySQL (privacyduck DB)
-- Rationale: UK GDPR Art. 7(1) requires the controller to be able to
--            DEMONSTRATE that the data subject has consented. To do that
--            we need to log (a) the version of the privacy policy they
--            accepted and (b) the timestamp of acceptance, per category.
-- =============================================================================

ALTER TABLE users
  ADD COLUMN consent_policy_version VARCHAR(20) NULL DEFAULT NULL
    COMMENT 'Effective date of the privacy policy version the user accepted at signup',
  ADD COLUMN policy_consent_at DATETIME NULL DEFAULT NULL
    COMMENT 'When the user accepted the privacy policy',
  ADD COLUMN marketing_consent_at DATETIME NULL DEFAULT NULL
    COMMENT 'When the user opted in to marketing emails (NULL = no marketing consent)';

-- Backfill: treat pre-migration signups as having accepted the older policy at
-- account creation time. Their original signup did capture acceptance via the
-- existing checkbox; we are just retroactively recording it in the new columns.
UPDATE users
  SET consent_policy_version = 'pre-2026-05-26',
      policy_consent_at = created_at
  WHERE policy_consent_at IS NULL
    AND created_at IS NOT NULL;
