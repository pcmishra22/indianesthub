# Deploy at localhost (root /var/www/html)

## Step 1 — Copy files
```bash
sudo cp -r stock_v3/. /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
sudo chmod 600 /var/www/html/.env
sudo mkdir -p /var/www/html/storage
sudo chmod 775 /var/www/html/storage
sudo chown www-data:www-data /var/www/html/storage
```

## Step 2 — Enable mod_rewrite
```bash
sudo a2enmod rewrite
```

## Step 3 — Allow .htaccess overrides (CRITICAL)
Edit your Apache site config:
```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```
Add/change the `<Directory>` block so it has `AllowOverride All`:
```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Step 4 — Restart Apache
```bash
sudo systemctl restart apache2
```

## Step 5 — Visit
http://localhost/
Login: admin / stockpass123 (or whatever you set in .env)

## Troubleshooting
If still 404, check Apache error log:
```bash
sudo tail -20 /var/log/apache2/error.log
```
