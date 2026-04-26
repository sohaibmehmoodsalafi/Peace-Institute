# 🚀 host.com.pk Hosting Guide — Peace Institute

## ✅ ASAAN TAREEQA (Seedha public_html mein)

### Step 1 — Apne PC par tayyar karo

```bash
cd "D:\Peace Institute\Website"
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 2 — .env file banao

.env.example ko copy karke .env banao aur yeh fields fill karo:

```
APP_NAME="Peace Institute"
APP_ENV=production
APP_KEY=             ← php artisan key:generate se milega
APP_DEBUG=false
APP_URL=https://yourdomain.com.pk

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanelusername_peacedb
DB_USERNAME=cpanelusername_dbuser
DB_PASSWORD=YourStrongPassword123

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com.pk
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com.pk
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourdomain.com.pk
```

### Step 3 — cPanel mein MySQL Database banao

1. cPanel login karo → **MySQL Databases**
2. "Create New Database" → naam: `peacedb`
3. "Create New User" → naam: `dbuser`, strong password
4. "Add User to Database" → All Privileges ✓
5. Note karo: Database = `cpanelusername_peacedb`, User = `cpanelusername_dbuser`

### Step 4 — Files Upload karo (2 options)

---

#### OPTION A — FileZilla (FTP) se Upload ✅ BEST

1. FileZilla download karo: https://filezilla-project.org
2. host.com.pk → cPanel → FTP Accounts se credentials lo
3. Connect karo:
   - Host: yourdomain.com.pk
   - Username: FTP username
   - Password: FTP password
   - Port: 21

4. **IMPORTANT Structure:**
   ```
   /home/username/                    ← FTP root
   ├── public_html/                   ← Website root (yahan public folder ka content)
   │   ├── index.php                  ← modified index.php
   │   ├── .htaccess
   │   ├── favicon.ico
   │   └── (baaki public/ files)
   │
   └── peace_institute/               ← Laravel app (public_html se BAHAR)
       ├── app/
       ├── bootstrap/
       ├── config/
       ├── database/
       ├── resources/
       ├── routes/
       ├── storage/
       ├── vendor/
       ├── .env
       └── artisan
   ```

5. public/index.php mein yeh lines update karo:
   ```php
   // Line 20 — autoloader path
   require __DIR__.'/../../peace_institute/vendor/autoload.php';
   
   // Line 26 — app path  
   $app = require_once __DIR__.'/../../peace_institute/bootstrap/app.php';
   ```

---

#### OPTION B — cPanel File Manager se Upload

1. cPanel → **File Manager** open karo
2. `public_html` ke BAHAR ek folder banao: `peace_institute`
3. **Compress** karo apni files ko ZIP mein
4. Upload karo aur Extract karo
5. `public_html` mein sirf `public/` folder ka content move karo

---

### Step 5 — Database Migrate karo

cPanel → **Terminal** (agar available ho) ya **phpMyAdmin**:

#### Terminal se:
```bash
cd ~/peace_institute
php artisan migrate --force
php artisan db:seed --force
```

#### phpMyAdmin se (agar Terminal nahi):
1. `php artisan migrate --force` se SQL generate karo pehle
2. Ya manually SQL import karo

---

### Step 6 — Storage Link

```bash
cd ~/peace_institute
php artisan storage:link
```

Ya manually symlink banao cPanel mein.

---

### Step 7 — Permissions set karo

```bash
chmod -R 755 ~/peace_institute/storage
chmod -R 755 ~/peace_institute/bootstrap/cache
```

---

## 🔧 host.com.pk Specific Settings

| Setting | Value |
|---------|-------|
| PHP Version | 8.2+ (cPanel → Select PHP Version) |
| MySQL | Available in cPanel |
| SSL | Free SSL available |
| SSH/Terminal | Check with support |

### PHP Version Change karo:
cPanel → **Select PHP Version** → PHP 8.2 select karo

### PHP Extensions Enable karo:
- mbstring ✓
- openssl ✓
- pdo_mysql ✓
- tokenizer ✓
- xml ✓
- ctype ✓
- json ✓
- bcmath ✓

---

## 📞 Agar Mushkil Ho

host.com.pk support se poochho:
- **SSH access** milti hai? (Easiest way)
- **PHP 8.2** available hai?
- **Composer** server par hai?

WhatsApp: Apne host.com.pk account ke support chat se poochho

---

## ✅ Test karo

Upload ke baad check karo:
- https://yourdomain.com.pk → Homepage
- https://yourdomain.com.pk/login → Login page
- https://yourdomain.com.pk/admin/dashboard → Admin panel
