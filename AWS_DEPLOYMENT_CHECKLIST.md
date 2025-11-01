# AWS EC2 Deployment Checklist - Image Issues Fixed

## ✅ Code Fixes Applied (Already Done)

All hardcoded relative image paths have been fixed:
- ❌ **Before:** `'/images/default-avatar.png'` (relative path)
- ✅ **After:** `asset('images/default-avatar.png')` (absolute URL using APP_URL)

**Files Fixed:**
- `resources/views/student/logs/create.blade.php`
- `resources/views/teacher/logs/unreviewed.blade.php`
- `resources/views/teacher/logs/student-logs.blade.php`
- `resources/views/teacher/logs/show.blade.php`
- `resources/views/teacher/logs/index.blade.php`
- `resources/views/dashboard/teacher.blade.php`
- `resources/views/dashboard/student.blade.php`

---

## 🚀 AWS EC2 Deployment Steps

### 1. Push Changes to GitHub

```bash
git add .
git commit -m "Fix: Replace hardcoded image paths with asset() helper for AWS deployment"
git push origin master
```

### 2. Pull Changes on EC2

SSH into your EC2 instance:
```bash
ssh -i your-key.pem ec2-user@your-ec2-ip
cd /var/www/html/your-project
git pull origin master
```

### 3. Create Storage Symlink (CRITICAL!)

```bash
php artisan storage:link
```

**Expected Output:**
```
The [public/storage] link has been connected to [storage/app/public].
```

### 4. Set Correct Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 5. Verify .env Configuration

Make sure your AWS `.env` file has:

```env
APP_URL=https://your-ec2-domain.com
# or
APP_URL=http://your-ec2-ip-address

FILESYSTEM_DISK=public
```

**Important:** The `APP_URL` must match your actual domain/IP!

### 6. Clear Laravel Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 7. Verify Public Assets Exist

```bash
# Check if images folder exists
ls -la public/images/

# Check if storage symlink exists
ls -la public/storage

# Check if profile pictures directory exists
ls -la storage/app/public/profile_pictures/
```

---

## 🔍 Testing & Verification

### Test 1: Storage Symlink
Visit: `https://your-domain.com/storage/`
- Should show directory listing or 403 (not 404)

### Test 2: Default Avatar
Visit: `https://your-domain.com/images/default-avatar.png`
- Should display the default avatar image

### Test 3: Background Image
Visit: `https://your-domain.com/images/background.png`
- Should display the SchoolPro logo

### Test 4: Profile Pictures
1. Log in to your application
2. Open browser DevTools (F12)
3. Go to Network tab
4. Refresh the page
5. Look for image requests:
   - ✅ All images should return 200 OK
   - ❌ If 404, check the URL being requested

### Test 5: Check Generated URLs

Run this on EC2:
```bash
php artisan tinker
```

Then test:
```php
echo url('images/default-avatar.png');
echo asset('images/default-avatar.png');
echo storage_path('app/public/profile_pictures/');
```

Expected output should show your full domain URL.

---

## 🐛 Troubleshooting

### Issue: Images still showing broken

**Check 1: Symlink exists?**
```bash
ls -la public/storage
```
Should show: `storage -> ../storage/app/public`

**Fix:**
```bash
rm public/storage  # Remove if it exists as a directory
php artisan storage:link
```

---

### Issue: 403 Forbidden on images

**Check permissions:**
```bash
ls -la public/images/
ls -la storage/app/public/
```

**Fix:**
```bash
sudo chmod -R 755 public/images/
sudo chmod -R 775 storage/app/public/
```

---

### Issue: Wrong URL generated

**Check APP_URL in .env:**
```bash
grep APP_URL .env
```

**Should be:**
```
APP_URL=https://your-actual-domain.com
```

**Fix:**
```bash
nano .env  # Edit APP_URL
php artisan config:clear
```

---

### Issue: SELinux blocking on Amazon Linux

If on Amazon Linux 2:
```bash
sudo chcon -R -t httpd_sys_rw_content_t storage/
sudo chcon -R -t httpd_sys_rw_content_t public/storage/
```

---

## 📋 Quick Diagnostic Script

Create this file on EC2: `check_images.php`

```php
<?php
echo "<h2>Image Path Diagnostics</h2>";
echo "<strong>APP_URL:</strong> " . env('APP_URL') . "<br>";
echo "<strong>Asset URL:</strong> " . asset('images/default-avatar.png') . "<br>";
echo "<strong>Storage URL:</strong> " . url('storage/') . "<br>";
echo "<br>";
echo "<strong>Storage Link Exists:</strong> " . (is_link(public_path('storage')) ? 'YES ✓' : 'NO ✗') . "<br>";
echo "<strong>Images Folder Exists:</strong> " . (is_dir(public_path('images')) ? 'YES ✓' : 'NO ✗') . "<br>";
echo "<strong>Storage/app/public Exists:</strong> " . (is_dir(storage_path('app/public')) ? 'YES ✓' : 'NO ✗') . "<br>";
```

Visit: `https://your-domain.com/check_images.php`

**Delete this file after checking!**

---

## ✅ Final Checklist

Before closing this issue, verify:

- [ ] Code pushed to GitHub
- [ ] Code pulled on EC2
- [ ] `php artisan storage:link` executed
- [ ] Permissions set correctly
- [ ] `.env` has correct `APP_URL`
- [ ] Caches cleared
- [ ] Default avatar image accessible
- [ ] Background logo image accessible
- [ ] Profile pictures loading (if any exist)
- [ ] No 404 errors in browser console
- [ ] All image URLs use full domain (check page source)

---

## 📞 Need More Help?

If images still don't work after all steps:

1. Check Apache/Nginx error logs:
   ```bash
   sudo tail -f /var/log/httpd/error_log
   # or
   sudo tail -f /var/log/nginx/error.log
   ```

2. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Verify web server user:
   ```bash
   ps aux | grep httpd
   # or
   ps aux | grep nginx
   ```

4. Test file permissions as web server user:
   ```bash
   sudo -u www-data ls -la public/images/
   sudo -u www-data ls -la storage/app/public/
   ```

---

**All image paths are now using `asset()` helper and will work correctly with your AWS deployment!** 🎉
