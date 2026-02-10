<?php
/**
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: YouTubeVideoAdminObserver.php v1.0.0 2026-02-10 $
 * 
 * TEMPORARY DEBUGGING VERSION
 * This file contains comprehensive debugging to identify why the admin field might not be saving.
 * The debugging should be removed once the issue is resolved.
 */

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

class YouTubeVideoAdminObserver extends base
{
    /**
     * Log file path for debugging
     */
    private $logFile;

    public function __construct()
    {
        // Initialize log file path
        $this->logFile = DIR_FS_LOGS . '/myDEBUG-youtube-video-observer-' . date('Ymd') . '.log';
        
        // Debug: Observer instantiation
        $this->debugLog('=== YouTubeVideoAdminObserver Constructor Called ===');
        $this->debugLog('Attaching to notifications:');
        $this->debugLog('  - NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS (display field)');
        $this->debugLog('  - NOTIFY_MODULES_UPDATE_PRODUCT_END (save field) - CORRECTED NOTIFICATION');
        
        $this->attach($this, [
            'NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS',
            'NOTIFY_MODULES_UPDATE_PRODUCT_END',  // This is the correct notification for saving
        ]);
        
        $this->debugLog('Observer successfully attached to notifications');
        $this->debugLog('');
    }

    public function update(&$class, $eventID, $p1, &$p2, &$p3)
    {
        $this->debugLog('--- update() method called ---');
        $this->debugLog('Event ID: ' . $eventID);
        
        switch ($eventID) {
            case 'NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS':
                $this->debugLog('Handling NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS (display notification)');
                $this->addYouTubeVideoField($p1, $p2);
                break;
            case 'NOTIFY_MODULES_UPDATE_PRODUCT_END':
                $this->debugLog('Handling NOTIFY_MODULES_UPDATE_PRODUCT_END (save notification)');
                $this->saveYouTubeVideoField($p1);
                break;
            default:
                $this->debugLog('WARNING: Unexpected event ID received: ' . $eventID);
        }
        
        $this->debugLog('');
    }

    /**
     * Add the YouTube Video ID field to the product edit form
     *
     * @param object $pInfo Product information object containing current product data
     * @param array &$extra_product_inputs Reference to array of extra input fields
     */
    private function addYouTubeVideoField($pInfo, &$extra_product_inputs)
    {
        $this->debugLog('>>> addYouTubeVideoField() called <<<');
        
        // Check if product info is available
        if (is_object($pInfo)) {
            $this->debugLog('Product Info Object: YES');
            $this->debugLog('Product ID: ' . ($pInfo->products_id ?? 'N/A'));
        } else {
            $this->debugLog('Product Info Object: NO - This is unexpected!');
        }
        
        // Check if language constants are defined
        $this->debugLog('Checking language constants:');
        if (defined('TEXT_PRODUCTS_YOUTUBE_VIDEO_ID')) {
            $this->debugLog('  TEXT_PRODUCTS_YOUTUBE_VIDEO_ID: DEFINED = "' . TEXT_PRODUCTS_YOUTUBE_VIDEO_ID . '"');
        } else {
            $this->debugLog('  TEXT_PRODUCTS_YOUTUBE_VIDEO_ID: NOT DEFINED - This will cause an error!');
        }
        
        if (defined('TEXT_PRODUCTS_YOUTUBE_VIDEO_ID_DESCRIPTION')) {
            $this->debugLog('  TEXT_PRODUCTS_YOUTUBE_VIDEO_ID_DESCRIPTION: DEFINED = "' . TEXT_PRODUCTS_YOUTUBE_VIDEO_ID_DESCRIPTION . '"');
        } else {
            $this->debugLog('  TEXT_PRODUCTS_YOUTUBE_VIDEO_ID_DESCRIPTION: NOT DEFINED - This will cause an error!');
        }
        
        // Get current value from database
        $currentValue = $pInfo->products_youtube_video_id ?? '';
        $this->debugLog('Current Value from Database: "' . $currentValue . '"');
        $this->debugLog('Current Value is ' . (empty($currentValue) ? 'EMPTY' : 'NOT EMPTY'));
        
        // Sanitize for display
        // CHARSET is a Zen Cart constant, should be defined by this point
        $charset = defined('CHARSET') ? CHARSET : 'utf-8';
        $safeValue = htmlspecialchars($currentValue, ENT_COMPAT, $charset, true);
        $this->debugLog('Sanitized Value for Display: "' . $safeValue . '"');
        
        // Build field definition
        $fieldDefinition = [
            'label' => [
                'text' => TEXT_PRODUCTS_YOUTUBE_VIDEO_ID,
                'field_name' => 'products_youtube_video_id',
            ],
            'input' => zen_draw_input_field(
                'products_youtube_video_id', 
                $safeValue, 
                'class="form-control" placeholder="e.g., abCd12345"'
            ) . '<span class="help-block">' . TEXT_PRODUCTS_YOUTUBE_VIDEO_ID_DESCRIPTION . '</span>'
        ];
        
        $this->debugLog('Field Definition Created:');
        $this->debugLog('  Field Name: products_youtube_video_id');
        $this->debugLog('  Label Text: ' . (TEXT_PRODUCTS_YOUTUBE_VIDEO_ID ?? 'UNDEFINED'));
        
        // Add to extra inputs array
        $countBefore = count($extra_product_inputs);
        $extra_product_inputs[] = $fieldDefinition;
        $countAfter = count($extra_product_inputs);
        
        $this->debugLog('Field added to $extra_product_inputs array');
        $this->debugLog('  Array count before: ' . $countBefore);
        $this->debugLog('  Array count after: ' . $countAfter);
        $this->debugLog('  Field successfully added: ' . ($countAfter > $countBefore ? 'YES' : 'NO'));
        $this->debugLog('<<< addYouTubeVideoField() completed >>>');
        $this->debugLog('');
    }

    /**
     * Save the YouTube Video ID when the product is updated
     *
     * @param array $p1 Array containing products_id and other product data
     */
    private function saveYouTubeVideoField($p1)
    {
        global $db;
        
        $this->debugLog('>>> saveYouTubeVideoField() called <<<');
        $this->debugLog('Parameter $p1 type: ' . gettype($p1));
        
        // Extract product ID from parameter
        $products_id = is_array($p1) ? ($p1['products_id'] ?? 0) : (int)$p1;
        $this->debugLog('Product ID: ' . $products_id);
        
        // Check POST data
        $this->debugLog('Checking $_POST data:');
        $this->debugLog('  $_POST array exists: ' . (isset($_POST) ? 'YES' : 'NO'));
        $this->debugLog('  $_POST array count: ' . (isset($_POST) ? count($_POST) : 0));
        
        if (isset($_POST['products_youtube_video_id'])) {
            $this->debugLog('  products_youtube_video_id in $_POST: YES');
            $rawValue = $_POST['products_youtube_video_id'];
            $this->debugLog('  Raw POST value: "' . $rawValue . '"');
            $this->debugLog('  Raw value length: ' . strlen($rawValue));
            $this->debugLog('  Raw value is empty: ' . (empty($rawValue) ? 'YES' : 'NO'));
        } else {
            $this->debugLog('  products_youtube_video_id in $_POST: NO - Field was not submitted!');
            $rawValue = '';
        }
        
        // Log other POST fields for context (helpful for debugging two-step product update process)
        // Note: This is temporary debugging - shows context for action=new_product and action=new_product_preview
        $this->debugLog('  Other POST fields present: ' . implode(', ', array_keys($_POST)));
        
        // Get the posted value
        $youtube_video_id = $_POST['products_youtube_video_id'] ?? '';
        $this->debugLog('POST value retrieved (with ?? fallback): "' . $youtube_video_id . '"');
        
        // Sanitize the input
        $this->debugLog('Sanitizing input:');
        $this->debugLog('  Before sanitization: "' . $youtube_video_id . '"');
        $youtube_video_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $youtube_video_id);
        $this->debugLog('  After sanitization: "' . $youtube_video_id . '"');
        $this->debugLog('  Sanitization changed value: ' . ($rawValue !== $youtube_video_id ? 'YES' : 'NO'));
        
        // Prepare for database
        $prepared_value = zen_db_prepare_input($youtube_video_id);
        $this->debugLog('After zen_db_prepare_input(): "' . $prepared_value . '"');
        
        // Check if database column exists
        $this->debugLog('Checking database column existence:');
        try {
            $columnCheck = $db->Execute("SHOW COLUMNS FROM " . TABLE_PRODUCTS . " LIKE 'products_youtube_video_id'");
            if ($columnCheck->RecordCount() > 0) {
                $this->debugLog('  Database column exists: YES');
                $columnInfo = $columnCheck->fields;
                $this->debugLog('  Column Type: ' . ($columnInfo['Type'] ?? 'unknown'));
                $this->debugLog('  Column Null: ' . ($columnInfo['Null'] ?? 'unknown'));
                $this->debugLog('  Column Default: ' . ($columnInfo['Default'] ?? 'NULL'));
            } else {
                $this->debugLog('  Database column exists: NO - THIS IS A PROBLEM! Column needs to be created.');
                $this->debugLog('  Run this SQL: ALTER TABLE ' . TABLE_PRODUCTS . ' ADD products_youtube_video_id VARCHAR(255) NULL;');
                $this->debugLog('<<< saveYouTubeVideoField() aborted - column missing >>>');
                return;
            }
        } catch (Exception $e) {
            $this->debugLog('  Error checking column: ' . $e->getMessage());
        }
        
        // Perform direct SQL UPDATE (like the reference implementation)
        $this->debugLog('Preparing SQL UPDATE:');
        $sql = 'UPDATE ' . TABLE_PRODUCTS . ' SET products_youtube_video_id = :videoId WHERE products_id = :productsId';
        $this->debugLog('  SQL Template: ' . $sql);
        
        // Bind variables
        $sql = $db->bindVars($sql, ':videoId', $prepared_value, 'string');
        $sql = $db->bindVars($sql, ':productsId', $products_id, 'integer');
        $this->debugLog('  SQL with values: ' . $sql);
        
        // Execute the update
        $this->debugLog('Executing SQL UPDATE...');
        try {
            $result = $db->Execute($sql);
            $this->debugLog('  SQL executed successfully');
            $this->debugLog('  Affected rows: ' . ($db->affectedRows() ?? 'unknown'));
        } catch (Exception $e) {
            $this->debugLog('  ERROR executing SQL: ' . $e->getMessage());
        }
        
        // Log the action being performed
        $this->debugLog('Action Summary:');
        $this->debugLog('  Product ID: ' . $products_id);
        $this->debugLog('  Field: products_youtube_video_id');
        $this->debugLog('  Value saved: "' . $prepared_value . '"');
        $this->debugLog('  Value is empty: ' . (empty($prepared_value) ? 'YES (saved as empty/NULL)' : 'NO'));
        
        $this->debugLog('<<< saveYouTubeVideoField() completed >>>');
        $this->debugLog('');
    }
    
    /**
     * Debug logging helper method
     * Writes debug messages to admin/logs/myDEBUG-youtube-video-observer-YYYYMMDD.log
     * 
     * NOTE: This is TEMPORARY debugging code. Error suppression is used to prevent
     * breaking the admin panel if logging fails. This should be removed once
     * the issue is resolved.
     *
     * @param string $message The message to log
     */
    private function debugLog($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = '[' . $timestamp . '] ' . $message . PHP_EOL;
        
        // Ensure logs directory exists
        // DIR_FS_LOGS is a Zen Cart constant, not user input
        if (!defined('DIR_FS_LOGS')) {
            // Fallback if DIR_FS_LOGS is not defined (should not happen in normal Zen Cart operation)
            $logDir = dirname(dirname(dirname(dirname(__FILE__)))) . '/logs';
        } else {
            $logDir = DIR_FS_LOGS;
        }
        
        // Suppress errors to avoid breaking admin panel if logging fails (temporary debugging only)
        if (!file_exists($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        // Write to log file (suppress errors to avoid breaking admin panel if logging fails)
        @file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
