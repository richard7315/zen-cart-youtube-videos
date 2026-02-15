<?php
/**
 * Storefront observer for YouTube Video module.
 * Sets the $products_youtube_video_id template variable using the notifier system,
 * avoiding the need to modify core storefront files.
 *
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: auto.YouTubeVideoObserver.php v1.0.4 2026-02-15 $
 */

class zcObserverYouTubeVideoObserver extends base
{
    public function __construct()
    {
        $this->attach($this, [
            'NOTIFY_MAIN_TEMPLATE_VARS_EXTRA_PRODUCT_INFO',
        ]);
    }

    protected function updateNotifyMainTemplateVarsExtraProductInfo(&$class, $eventID)
    {
        global $product_info;

        $GLOBALS['products_youtube_video_id'] = $product_info->fields['products_youtube_video_id'] ?? '';
    }
}
