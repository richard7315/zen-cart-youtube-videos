<?php
/**
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: config.youtube_video_admin.php v1.0.0 2026-02-10 $
 */

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// Load the observer class for admin
// Priority 190 ensures language files (loaded at 40-60) are available before observer instantiation
$autoLoadConfig[190][] = [
    'autoType' => 'class',
    'loadFile' => 'observers/YouTubeVideoAdminObserver.php',
    'classPath' => DIR_WS_CLASSES,
];

$autoLoadConfig[190][] = [
    'autoType' => 'classInstantiate',
    'className' => 'YouTubeVideoAdminObserver',
    'objectName' => 'YouTubeVideoAdminObserver'
];
