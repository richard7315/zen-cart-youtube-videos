# YouTube Video for Products - Zen Cart Module

## Overview

This module adds YouTube video functionality to Zen Cart product pages. Store owners can easily add a YouTube video ID to any product in the admin panel, and the video will be displayed on the product's detail page in the storefront.

## Features

- **Easy Admin Interface**: Add YouTube video IDs directly in the product edit form
- **Responsive Video Display**: Videos are displayed in a responsive iframe that adapts to different screen sizes
- **Privacy-Focused**: Uses `youtube-nocookie.com` domain to minimize tracking

## Requirements

- Zen Cart v2.0.0 or higher (tested with v2.1.0)
- PHP 7.4 or higher

## Installation

1. **Backup Your Store**
   - Always backup your database and files before installing any module

2. **Upload Files**
   - Extract the module files
   - Upload the `YOUR_ADMIN` folder contents to your Zen Cart's `admin` directory
   - Upload the `includes` folder contents to your Zen Cart's `includes` directory
   - **Important**: Rename `YOUR_TEMPLATE` in the path to match your template folder name (e.g., `responsive_classic`, `bootstrap`, etc.)
   - **Note**: The files `main_template_vars.php` and `tpl_product_info_display.php` are modified Zen Cart core files. If you have custom changes in these files, merge the YouTube video code into your existing files rather than overwriting them.


3. **Run Database Update**
   - In Zen Cart Admin, go to **Tools > Install SQL Patches**
   - Copy and paste the following SQL statement:
     ```sql
     ALTER TABLE products ADD products_youtube_video_id VARCHAR(255) NULL AFTER products_image;
     ```
   - Click "Send" to execute
   - You should see a success message confirming the field was added

4. **Verify Installation**
   - Log in to your Zen Cart Admin Panel
   - Go to **Catalog > Products**
   - Edit any product
   - You should see a new field: **YouTube Video ID**

## Usage

### Adding a YouTube Video to a Product

1. **Get the Video ID**
   - Go to the YouTube video you want to use
   - From the URL `https://www.youtube.com/watch?v=abCd12345`
   - Copy only the video ID: `abCd12345`

2. **Add to Product**
   - In the Admin Panel, go to **Catalog > Products**
   - Edit the product where you want to add the video
   - Scroll to the **YouTube Video ID** field
   - Paste the video ID (e.g., `abCd12345`)
   - Save the product

3. **View on Storefront**
   - Navigate to the product page on your storefront
   - The video will be displayed below the product description

## File Structure

```
YouTube-Videos-for-Zen-Cart/
├── YOUR_ADMIN/                       ← Upload to admin/
│   └── includes/
│       ├── auto_loaders/
│       │   └── config.youtube_video_admin.php
│       ├── classes/
│       │   └── observers/
│       │       └── YouTubeVideoAdminObserver.php
│       └── languages/
│           └── english/
│               └── extra_definitions/
│                   └── lang.youtube_video.php
├── includes/                         ← Upload to includes/ (standard Zen Cart structure)
│   ├── languages/
│   │   └── english/
│   │       └── extra_definitions/
│   │           └── lang.youtube_video.php
│   ├── modules/
│   │   └── pages/
│   │       └── product_info/
│   │           └── main_template_vars.php
│   └── templates/
│       └── YOUR_TEMPLATE/            ← Rename to your template folder
│           └── templates/
│               └── tpl_product_info_display.php
├── install.sql
├── uninstall.sql
├── TESTING_GUIDE.md
└── README.md
```

## Database Changes

The module adds one field to the `products` table:

```sql
ALTER TABLE products 
ADD products_youtube_video_id VARCHAR(255) NULL 
AFTER products_image;
```

## Troubleshooting

### Blank Admin Pages After Installation

If admin pages show a blank/white screen after installing this module, the most likely cause is a missing `classPath` parameter in the autoloader configuration.

Verify your autoloader file at `admin/includes/auto_loaders/config.youtube_video_admin.php` includes the `classPath` line:

```php
$autoLoadConfig[190][] = [
    'autoType' => 'class',
    'loadFile' => 'observers/YouTubeVideoAdminObserver.php',
    'classPath' => DIR_WS_CLASSES,  // This line is CRITICAL for Zen Cart 2.0+
];
```

If this line is missing, download the latest version of this module and replace the autoloader file.

### Video ID Field Not Appearing in Admin

1. Verify file locations:
   - `admin/includes/auto_loaders/config.youtube_video_admin.php`
   - `admin/includes/classes/observers/YouTubeVideoAdminObserver.php`

2. Check file permissions (files should be readable, typically 644)

3. Check PHP error logs in `admin/logs/` for fatal errors or warnings

4. Verify the database field exists:
   ```sql
   SHOW COLUMNS FROM products LIKE 'products_youtube_video_id';
   ```
   If the field doesn't exist, run the `install.sql` again.

### Video Not Displaying on Storefront

1. **Check Video ID**: Ensure you're entering only the video ID, not the full URL
   - ✅ Correct: `abCd12345`
   - ❌ Incorrect: `https://www.youtube.com/watch?v=abCd12345`

2. **Clear Cache**: Clear Zen Cart cache and browser cache

3. **Check Template Folder Name**: The most common issue is forgetting to rename `YOUR_TEMPLATE`
   - Find your current template name in Admin > Tools > Template Selection
   - Ensure the template file is at: `includes/templates/[your_template_name]/templates/tpl_product_info_display.php`
   - The folder name in the module distribution should match your actual template folder

4. **Check All File Locations**:
   - `includes/modules/pages/product_info/main_template_vars.php`
   - `includes/templates/[your_template_name]/templates/tpl_product_info_display.php`
   - `includes/languages/english/extra_definitions/lang.youtube_video.php`

### Video ID Not Saving

If the YouTube Video ID field appears but the value isn't saved when you update the product, the observer may be missing the save notification. Verify that `admin/includes/classes/observers/YouTubeVideoAdminObserver.php` listens to **both** of these notifications:

- `NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS` (to display the field)
- `NOTIFY_ADMIN_PRODUCT_UPDATE_PRODUCT_END` (to save the value)

If either is missing, download the latest version of this module and replace the observer file.

Also verify the database column exists:
   ```sql
   SHOW COLUMNS FROM products LIKE 'products_youtube_video_id';
   ```

If missing, run the `install.sql` script again.

### Database Field Not Created

If the `ALTER TABLE` command from `install.sql` fails:

1. **Check if the field already exists:**
   ```sql
   SHOW COLUMNS FROM products LIKE 'products_youtube_video_id';
   ```

2. **Check database user permissions** — your database user needs `ALTER` table permissions. Contact your hosting provider if needed.

3. **Try adding the field without specifying position:**
   ```sql
   ALTER TABLE products ADD products_youtube_video_id VARCHAR(255) NULL;
   ```

## Uninstallation

If you need to remove this module:

1. **Remove Module Files**
   Delete the following files from your Zen Cart installation:

   **Admin Files:**
   - `admin/includes/auto_loaders/config.youtube_video_admin.php`
   - `admin/includes/classes/observers/YouTubeVideoAdminObserver.php`
   - `admin/includes/languages/english/extra_definitions/lang.youtube_video.php`

   **Storefront Files:**
   - `includes/languages/english/extra_definitions/lang.youtube_video.php`
   - `includes/modules/pages/product_info/main_template_vars.php`
   - `includes/templates/[your_template_name]/templates/tpl_product_info_display.php`



2. **Remove Database Field** (Optional)
   - Run the SQL script in `uninstall.sql`, or manually execute:
   ```sql
   ALTER TABLE products DROP products_youtube_video_id;
   ```
   - **WARNING**: This will permanently remove all YouTube video IDs from your products
   - You can leave the database field in place if you might reinstall later — an empty field won't affect your store's operation

## Custom Template Usage

The `YOUR_TEMPLATE` folder should be renamed to match your actual Zen Cart template folder name. Common template names include:

- `responsive_classic` (Zen Cart default)
- `bootstrap` (Bootstrap template)
- Your custom template name

Simply rename the folder before uploading, or copy the files into your existing template directory.

## Support

For issues, questions, or feature requests, please post on this mod's support thread on the Zen Cart Forums.

## License

GNU Public License V2.0

## Version History

### Version 1.0.0 - 2026-02-10

Initial Release:
- YouTube video ID field in product admin
- Responsive video display on product pages
- Privacy-focused embed using `youtube-nocookie.com`
- Proper observer pattern implementation for Zen Cart 2.0+
- Clean autoloader configuration with `classPath` support

---

**Note**: This module adds a new field to your database. Always backup your database before installation.
