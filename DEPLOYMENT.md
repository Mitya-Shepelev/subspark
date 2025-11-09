# SubSpark - Production Deployment Guide

## 📋 Pre-Deployment Checklist

### 1. Server Requirements
- ✅ PHP 8.1+ with extensions:
  - PDO, pdo_mysql
  - mbstring, json, curl
  - gd or imagick (для обработки изображений)
  - fileinfo
- ✅ MySQL 8.0+ или MariaDB 10.5+
- ✅ Nginx или Apache
- ✅ FFmpeg (для обработки видео)
- ✅ Composer (если используются vendor dependencies)

### 2. Database Setup
```bash
# Создайте базу данных
mysql -u root -p
CREATE DATABASE subspark_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'subspark_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON subspark_production.* TO 'subspark_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Импортируйте SQL дамп
mysql -u subspark_user -p subspark_production < database.sql
```

### 3. Environment Configuration

**ВАЖНО:** НЕ используйте дефолтные credentials из `includes/connect.php` в production!

#### Вариант 1: Через переменные окружения (рекомендуется)

Создайте `.env` файл или установите переменные окружения:

```bash
export DB_SERVER=localhost
export DB_USERNAME=subspark_user
export DB_PASSWORD=secure_password_here
export DB_DATABASE=subspark_production
export APP_ENV=production
export APP_URL=https://your-domain.com
```

Для Nginx добавьте в конфиг:
```nginx
fastcgi_param DB_SERVER "localhost";
fastcgi_param DB_USERNAME "subspark_user";
fastcgi_param DB_PASSWORD "secure_password_here";
fastcgi_param DB_DATABASE "subspark_production";
fastcgi_param APP_ENV "production";
```

#### Вариант 2: Изменить `includes/connect.php` (не рекомендуется)

```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'subspark_user');
define('DB_PASSWORD', 'secure_password_here');
define('DB_DATABASE', 'subspark_production');
```

⚠️ **ВНИМАНИЕ:** Если изменяете `connect.php`, добавьте его в `.gitignore` на production сервере!

### 4. File Permissions

```bash
# Установите правильные права доступа
cd /path/to/subspark

# Директории для загрузок
chmod 755 uploads/
chmod 755 uploads/avatars uploads/covers uploads/files uploads/videos uploads/reels

# Логи (если используются)
chmod 755 logs/ 2>/dev/null || true

# PHP файлы
find . -type f -name "*.php" -exec chmod 644 {} \;

# Защита конфигурации
chmod 600 includes/connect.php
```

### 5. Web Server Configuration

#### Nginx (рекомендуется)

Используйте `dizzy.conf` как основу:

```bash
# Скопируйте и адаптируйте конфиг
cp dizzy.conf /etc/nginx/sites-available/subspark.conf

# Отредактируйте:
# - server_name (ваш домен)
# - root (путь к проекту)
# - fastcgi_pass (ваш PHP-FPM сокет)

# Активируйте
ln -s /etc/nginx/sites-available/subspark.conf /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

#### Apache

`.htaccess` уже настроен. Убедитесь что:
```apache
# Включен mod_rewrite
a2enmod rewrite

# В VirtualHost установлено
<Directory /path/to/subspark>
    AllowOverride All
</Directory>
```

### 6. SSL Certificate (HTTPS)

```bash
# Используйте Let's Encrypt
certbot --nginx -d your-domain.com -d www.your-domain.com

# Или для Apache
certbot --apache -d your-domain.com -d www.your-domain.com
```

### 7. Object Storage (опционально)

Если используете S3/Wasabi/DigitalOcean Spaces/MinIO:

1. Загрузите папку `uploads/` в ваш bucket
2. Настройте переменные окружения (см. `.env.example`)
3. Включите соответствующий провайдер в админ-панели

### 8. FFmpeg Configuration

```bash
# Установите FFmpeg
apt-get install ffmpeg  # Ubuntu/Debian
yum install ffmpeg      # CentOS/RHEL

# Проверьте установку
ffmpeg -version
which ffmpeg            # Путь для настройки в админке

# Настройте в админ-панели:
# Admin Panel → General → FFmpeg Path
```

## 🚀 Deployment Steps

### Способ 1: Git Clone (рекомендуется)

```bash
# На production сервере
cd /var/www/
git clone https://github.com/Mitya-Shepelev/subspark.git
cd subspark

# Настройте .env или переменные окружения
cp .env.example .env
nano .env

# Установите зависимости (если есть)
composer install --no-dev --optimize-autoloader

# Установите права
chmod 755 uploads/ -R
chmod 600 includes/connect.php
```

### Способ 2: FTP/SFTP Upload

```bash
# Загрузите все файлы кроме:
# - .git/
# - CLAUDE.md
# - translate_ru.py
# - node_modules/
# - .DS_Store

# После загрузки установите права (см. раздел 4)
```

### Обновление (Update)

```bash
cd /var/www/subspark
git pull origin main
composer install --no-dev --optimize-autoloader
# При необходимости запустите миграции
php admin/migrate.php
```

## 🔒 Security Checklist

- [ ] Использованы безопасные пароли БД
- [ ] `includes/connect.php` защищен (chmod 600)
- [ ] APP_ENV=production (отключает отображение ошибок)
- [ ] HTTPS включен (SSL сертификат)
- [ ] Firewall настроен (порты 22, 80, 443)
- [ ] PHP настроен безопасно:
  - `display_errors = Off`
  - `expose_php = Off`
  - `upload_max_filesize` установлен
  - `post_max_size` установлен
- [ ] Регулярные бэкапы БД настроены
- [ ] Error logs мониторятся

## 📊 Post-Deployment

1. **Проверьте доступность сайта:**
   - https://your-domain.com
   - https://your-domain.com/admin/index

2. **Войдите в админ-панель:**
   - Создайте первого администратора через БД или регистрацию
   - Настройте основные параметры

3. **Протестируйте основные функции:**
   - Регистрация пользователей
   - Загрузка файлов
   - Создание постов
   - Платежные системы

4. **Настройте мониторинг:**
   - Логи ошибок: `/var/log/nginx/error.log` или Apache logs
   - PHP errors: `php-fpm` logs
   - Application logs: проверьте `error_log` файлы

## 🆘 Troubleshooting

### "Database connection error"
- Проверьте переменные окружения или `includes/connect.php`
- Убедитесь что MySQL запущен: `systemctl status mysql`
- Проверьте права пользователя БД

### "404 on all pages"
- **Apache:** Убедитесь что `mod_rewrite` включен и `AllowOverride All` установлен
- **Nginx:** Проверьте конфигурацию `try_files` в server block

### "Upload failed"
- Проверьте права на `uploads/`: `chmod 755 -R uploads/`
- Проверьте PHP настройки: `upload_max_filesize`, `post_max_size`
- Проверьте disk space: `df -h`

### "FFmpeg not working"
- Установите: `apt-get install ffmpeg`
- Настройте путь в админ-панели: обычно `/usr/bin/ffmpeg`

## 📞 Support

Для вопросов и поддержки:
- GitHub Issues: https://github.com/Mitya-Shepelev/subspark/issues
- Documentation: См. CLAUDE.md
