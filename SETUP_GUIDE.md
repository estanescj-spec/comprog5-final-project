# Quick Setup Guide for Product Management Features

## Step 1: Run Database Migrations

Open your terminal in the project root and run:

```bash
php artisan migrate
```

This will create the following tables:
- `categories` - Product categories
- `products` - Product information
- `product_variants` - Product variants (size, color, stock)

## Step 2: Create Storage Link

Run this command to create a symbolic link for file uploads:

```bash
php artisan storage:link
```

This allows product images to be accessible from the web.

## Step 3: Seed Initial Data

### Create an Admin User (if not already done)
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default admin credentials:
- Email: admin@example.com
- Password: password

### Seed Sample Categories (Optional)
```bash
php artisan db:seed --class=CategorySeeder
```

This will create 7 sample skincare categories:
- Moisturizers
- Cleansers
- Serums
- Sunscreen
- Masks
- Toners
- Eye Care

## Step 4: Test the Features

1. **Login as Admin**
   - Navigate to `/login`
   - Use admin credentials
   - You'll see the dashboard with new management cards

2. **Add a Category**
   - Click "Manage Categories" in navigation
   - Click "+ Add Category"
   - Fill in name and description
   - Click "Create Category"

3. **Add a Product**
   - Click "Manage Products" in navigation
   - Click "+ Add Product"
   - Select a category
   - Fill in product details
   - Upload an image (optional)
   - Click "Create Product"

4. **Add Product Variants**
   - Edit the product you just created
   - Scroll to "Product Variants" section
   - Click "+ Add Variant"
   - Fill in size, color, price, and stock
   - Click "Add"

5. **Browse as User**
   - Logout and create a regular user account
   - Login as that user
   - Click "Products" in navigation
   - Use search and category filters
   - View product details

## Common Issues & Solutions

### Issue: Images not displaying
**Solution**: Make sure you ran `php artisan storage:link`

### Issue: "Class not found" errors
**Solution**: Run `composer dump-autoload`

### Issue: Upload directory permission errors
**Solution**: Make sure `storage/app/public` is writable:
```bash
chmod -R 775 storage/app/public
```

### Issue: No categories showing in product form
**Solution**: Create at least one category first before adding products

## File Upload Limits

By default, the maximum file size is 2MB. To change this:

1. Edit `php.ini`:
   ```
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

2. Update validation in `ProductController.php` (line 37):
   ```php
   'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
   ```

## Database Relationships

- A Product belongs to one Category
- A Product can have many ProductVariants
- A Category can have many Products
- Deleting a Product will delete all its Variants (cascade)
- Deleting a Category with Products is prevented

## Navigation Guide

### Admin Users See:
- Home
- Manage Users
- Manage Products
- Manage Categories

### Regular Users See:
- Home
- Products (with search & category filter)

## Need Help?

Check the detailed documentation in `PRODUCT_MANAGEMENT_README.md` for:
- Complete feature descriptions
- API routes documentation
- Database schema details
- Security features
- Future enhancements

Happy managing! 🌸
