-- YouTube Video for Products - Installation SQL
-- Version: 1.0.2
-- Date: 2026-02-10
--
-- This SQL script adds the necessary database field to support YouTube videos on product pages.
-- Run this script in your Zen Cart database before or after uploading the module files.

-- Add YouTube video ID field to products table
ALTER TABLE products 
ADD products_youtube_video_id VARCHAR(255) NULL 
AFTER products_image;
