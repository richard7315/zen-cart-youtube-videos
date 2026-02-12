-- YouTube Video for Products - Uninstallation SQL
-- Version: 1.0.1
-- Date: 2026-02-10
--
-- WARNING: This will remove the YouTube video ID field and all video data from your products.
-- Backup your database before running this script.

-- Remove YouTube video ID field from products table
-- Note: This will error if the column doesn't exist (safe to ignore during reinstall)
ALTER TABLE products 
DROP COLUMN products_youtube_video_id;
