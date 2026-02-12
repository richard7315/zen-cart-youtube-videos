# Testing Guide for YouTube Video Module

This guide helps you verify that the YouTube video module is working correctly after installation.

## Prerequisites

Before testing, ensure:
1. The module is installed (database field added, files uploaded)
2. You have access to both the admin panel and storefront
3. You have a test product to work with
4. You have a YouTube video ID to test with

## Test Cases

### Test 1: Admin - Field Display

**Purpose**: Verify the YouTube Video ID field appears in the product edit form.

**Steps**:
1. Log into Zen Cart admin panel
2. Navigate to: Catalog > Products
3. Click "Edit" on any product
4. Scroll down to find the custom fields section

**Expected Result**:
- ✅ You should see a field labeled "YouTube Video ID"
- ✅ Field should have placeholder text: "e.g., abCd12345"

---

### Test 2: Admin - Save Video ID

**Purpose**: Verify the video ID saves to the database.

**Steps**:
1. Edit a product in the admin
2. Enter a test YouTube video ID in the field: `abCd12345`
3. Click "Update" button
4. Re-edit the same product
5. Check if the video ID is still there

**Expected Result**:
- ✅ Video ID should still be populated with `abCd12345`
- ✅ Value should persist after page refresh

**Troubleshooting**:
- Verify database column exists:
  ```sql
  SHOW COLUMNS FROM products LIKE 'products_youtube_video_id';
  ```
- Check database value directly:
  ```sql
  SELECT products_id, products_model, products_youtube_video_id 
  FROM products 
  WHERE products_id = YOUR_PRODUCT_ID;
  ```

---

### Test 3: Storefront - Video Display

**Purpose**: Verify the video displays on the product page.

**Steps**:
1. Ensure a product has a valid YouTube video ID set in admin
2. Navigate to that product's page in the storefront
3. Scroll down to see the video section

**Expected Result**:
- ✅ Video section should appear on the product page
- ✅ Section should have heading "Product Video"
- ✅ YouTube video should be embedded and playable
- ✅ Video should be responsive (resize with the page)

**What to Look For**:
- Video should appear below the product description
- Video should be in a responsive container (16:9 aspect ratio)
- Video should play when clicked
- Video should use youtube-nocookie.com domain (check page source)

---

### Test 4: Input Validation

**Purpose**: Verify invalid characters are filtered out.

**Steps**:
1. Edit a product in admin
2. Enter invalid input: `<script>alert('xss')</script>`
3. Save the product
4. Re-edit the product

**Expected Result**:
- ✅ Only valid characters should remain
- ✅ No HTML/JavaScript tags should be saved

---

### Test 5: Empty/No Video ID

**Purpose**: Verify the page works correctly when no video ID is set.

**Steps**:
1. Edit a product
2. Clear the YouTube Video ID field (leave it empty)
3. Save the product
4. View the product page on storefront

**Expected Result**:
- ✅ Product page should load normally
- ✅ No video section should appear
- ✅ No errors in browser console
- ✅ Page layout should not be affected

---

### Test 6: Multiple Products

**Purpose**: Verify the module works correctly with multiple products.

**Steps**:
1. Set different YouTube video IDs on 3 different products:
   - Product A: `abCd12345`
   - Product B: `efGh67890`
   - Product C: (leave empty)
2. View each product page on storefront

**Expected Result**:
- ✅ Product A shows the correct video
- ✅ Product B shows a different video
- ✅ Product C shows no video section
- ✅ Videos don't "bleed" between products

---

### Test 7: Custom Template Compatibility

**Purpose**: Verify the module works with custom templates.

**Steps**:
1. If using a custom template (not responsive_classic):
2. Manually add the YouTube video code to your template's `tpl_product_info_display.php` below the product description section (see the README for the code snippet)
3. View a product with a video ID on storefront

**Expected Result**:
- ✅ Video should display correctly in your custom template

**Note**: The template may need styling adjustments to match your theme.

---

## Quick Verification Checklist

Use this checklist for a quick verification after installation:

- [ ] Admin field appears in product edit form
- [ ] Video ID saves when product is updated
- [ ] Video ID persists after re-editing product
- [ ] Video displays on product page in storefront
- [ ] Video plays correctly
- [ ] Products without video IDs display normally
- [ ] Invalid input is sanitized
- [ ] Page source shows youtube-nocookie.com domain
- [ ] No errors in browser console

---

## Debugging Tips

### Check Database Directly

Verify the video ID is in the database:
```sql
SELECT products_id, products_model, products_youtube_video_id 
FROM products 
WHERE products_youtube_video_id IS NOT NULL 
AND products_youtube_video_id != '';
```

### View Page Source

Check the storefront page source to see if the video iframe is present:
1. View product page
2. Right-click > View Page Source
3. Search for "youtube-nocookie.com"

If found, the module is working.

### Browser Console

Check for JavaScript errors:
1. Open browser DevTools (F12)
2. Go to Console tab
3. Look for any red error messages

---

## Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| Field doesn't appear in admin | Check observer file is in the correct location in YOUR_ADMIN |
| Field doesn't save | Check database column exists |
| Video doesn't display | Verify product has video ID set, check template files exist |
| Video shows on wrong product | Clear cache, verify database values |

---

## Support

For issues or questions, please post on this mod's support thread on the Zen Cart Forums.
