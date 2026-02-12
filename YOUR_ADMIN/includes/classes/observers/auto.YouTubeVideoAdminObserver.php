<?php
/**
 * Admin observer for YouTube Video module.
 * Adds YouTube Video ID field to product edit form and handles saving.
 *
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: auto.YouTubeVideoAdminObserver.php v1.0.3 2026-02-12 $
 */

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

class zcObserverYouTubeVideoAdminObserver extends base
{
    public function __construct()
    {
        $this->attach($this, [
            'NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS',
            'NOTIFY_MODULES_UPDATE_PRODUCT_END',
        ]);
    }

    public function update(&$class, $eventID, $p1, &$p2, &$p3)
    {
        switch ($eventID) {
            case 'NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS':
                $this->addYouTubeVideoField($p1, $p2);
                break;
            case 'NOTIFY_MODULES_UPDATE_PRODUCT_END':
                $this->saveYouTubeVideoField($p1);
                break;
        }
    }

    /**
     * Add the YouTube Video ID field to the product edit form
     *
     * @param object $pInfo Product information object containing current product data
     * @param array &$extra_product_inputs Reference to array of extra input fields
     */
    private function addYouTubeVideoField($pInfo, &$extra_product_inputs)
    {
        $currentValue = $pInfo->products_youtube_video_id ?? '';

        $charset = defined('CHARSET') ? CHARSET : 'utf-8';
        $safeValue = htmlspecialchars($currentValue, ENT_COMPAT, $charset, true);

        $extra_product_inputs[] = [
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
    }

    /**
     * Save the YouTube Video ID when the product is updated
     *
     * @param array $p1 Array containing products_id and other product data
     */
    private function saveYouTubeVideoField($p1)
    {
        global $db;

        $products_id = is_array($p1) ? ($p1['products_id'] ?? 0) : (int)$p1;

        $youtube_video_id = $_POST['products_youtube_video_id'] ?? '';
        $youtube_video_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $youtube_video_id);
        $prepared_value = zen_db_prepare_input($youtube_video_id);

        $sql = 'UPDATE ' . TABLE_PRODUCTS . ' SET products_youtube_video_id = :videoId WHERE products_id = :productsId';
        $sql = $db->bindVars($sql, ':videoId', $prepared_value, 'string');
        $sql = $db->bindVars($sql, ':productsId', $products_id, 'integer');
        $db->Execute($sql);
    }
}
