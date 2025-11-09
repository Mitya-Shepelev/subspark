# SubSpark - Portainer Deployment Guide

Это руководство по развёртыванию SubSpark через Portainer Stacks.

## 📋 Требования

- Docker и Docker Compose установлены
- Portainer установлен и настроен
- Доступ к Git репозиторию или возможность загрузить `docker-compose.yml`

## 🚀 Способ 1: Deploy через Git Repository (рекомендуется)

### Шаг 1: Откройте Portainer

Перейдите в Portainer UI: `http://your-server:9000`

### Шаг 2: Создайте новый Stack

1. Выберите **Stacks** в меню слева
2. Нажмите **+ Add stack**
3. Введите имя стека: `subspark`

### Шаг 3: Выберите метод деплоя

Выберите **Repository** и заполните:

```
Repository URL: https://github.com/Mitya-Shepelev/subspark.git
Repository reference: refs/heads/main
Compose path: docker-compose.yml
```

### Шаг 4: Настройте Environment Variables

Нажмите **Advanced mode** и добавьте переменные окружения:

```env
# Обязательные
MYSQL_ROOT_PASSWORD=your_secure_root_password_123
DB_DATABASE=subspark
DB_USERNAME=subspark_user
DB_PASSWORD=your_secure_password_456
APP_URL=https://your-domain.com
APP_PORT=8000

# Опциональные
PMA_PORT=8080
APP_ENV=production
```

**Важно:** Используйте безопасные пароли!

### Шаг 5: Deploy Stack

1. Нажмите **Deploy the stack**
2. Дождитесь завершения (может занять 2-5 минут при первом запуске)
3. Проверьте статус контейнеров

## 🚀 Способ 2: Deploy через Web Editor

### Шаг 1: Создайте Stack

1. **Stacks** → **+ Add stack**
2. Имя: `subspark`
3. Выберите **Web editor**

### Шаг 2: Вставьте docker-compose.yml

Скопируйте содержимое из репозитория:
https://github.com/Mitya-Shepelev/subspark/blob/main/docker-compose.yml

### Шаг 3: Настройте Environment Variables

Добавьте в разделе **Environment variables**:

```
MYSQL_ROOT_PASSWORD=your_secure_root_password_123
DB_DATABASE=subspark
DB_USERNAME=subspark_user
DB_PASSWORD=your_secure_password_456
APP_URL=https://your-domain.com
APP_PORT=8000
PMA_PORT=8080
```

### Шаг 4: Deploy

Нажмите **Deploy the stack**

## 📊 После деплоя

### Проверка контейнеров

В Portainer перейдите в **Containers** и проверьте, что запущены:

- ✅ `subspark-mysql` - База данных
- ✅ `subspark-app` - Приложение (PHP-FPM + Nginx)
- ✅ `subspark-phpmyadmin` - Управление БД (опционально)

### Доступ к приложению

```
SubSpark: http://your-server:8000
phpMyAdmin: http://your-server:8080
```

### Импорт базы данных

**Вариант 1: Через phpMyAdmin**

1. Откройте `http://your-server:8080`
2. Войдите (username из `DB_USERNAME`, password из `DB_PASSWORD`)
3. Выберите БД `subspark`
4. Импорт → Выберите ваш `.sql` файл

**Вариант 2: Через Console**

1. В Portainer найдите контейнер `subspark-mysql`
2. Нажмите **Console** → **Connect**
3. Выполните:

```bash
mysql -u root -p subspark < /path/to/database.sql
```

Или через docker exec:

```bash
docker exec -i subspark-mysql mysql -u subspark_user -p'your_password' subspark < database.sql
```

## 🔧 Управление Stack

### Просмотр логов

В Portainer:
1. **Stacks** → `subspark`
2. Выберите контейнер
3. **Logs**

Или через CLI:
```bash
docker logs subspark-app
docker logs subspark-mysql
```

### Перезапуск

В Portainer:
- **Stacks** → `subspark` → **Stop** / **Start**

Или через CLI:
```bash
docker-compose restart
```

### Обновление приложения

**Через Portainer:**
1. **Stacks** → `subspark` → **Editor**
2. Нажмите **Pull and redeploy**
3. Подтвердите

**Через CLI:**
```bash
cd /path/to/stack
git pull origin main
docker-compose pull
docker-compose up -d
```

### Остановка

В Portainer:
- **Stacks** → `subspark` → **Stop**

Или:
```bash
docker-compose down
```

## 💾 Volumes (Persistent Data)

Stack создаёт два volume:

1. **mysql_data** - База данных MySQL
2. **uploads_data** - Пользовательские загрузки

### Backup Volumes

**MySQL:**
```bash
docker exec subspark-mysql mysqldump -u root -p'password' subspark > backup.sql
```

**Uploads:**
```bash
docker run --rm -v subspark_uploads_data:/data -v $(pwd):/backup alpine tar czf /backup/uploads-backup.tar.gz -C /data .
```

### Restore Volumes

**MySQL:**
```bash
docker exec -i subspark-mysql mysql -u root -p'password' subspark < backup.sql
```

**Uploads:**
```bash
docker run --rm -v subspark_uploads_data:/data -v $(pwd):/backup alpine tar xzf /backup/uploads-backup.tar.gz -C /data
```

## 🔒 Security

### Рекомендации:

1. **Измените дефолтные пароли** в Environment Variables
2. **Используйте HTTPS** - настройте reverse proxy (Traefik/Nginx Proxy Manager)
3. **Ограничьте доступ к phpMyAdmin** - используйте profile или удалите из compose
4. **Регулярные бэкапы** БД и uploads
5. **Обновления** - периодически пулите новые образы

### Настройка Reverse Proxy (Traefik example)

Добавьте labels в `docker-compose.yml`:

```yaml
app:
  labels:
    - "traefik.enable=true"
    - "traefik.http.routers.subspark.rule=Host(`your-domain.com`)"
    - "traefik.http.routers.subspark.entrypoints=websecure"
    - "traefik.http.routers.subspark.tls.certresolver=letsencrypt"
    - "traefik.http.services.subspark.loadbalancer.server.port=80"
```

## 🐛 Troubleshooting

### Контейнер не запускается

```bash
# Проверьте логи
docker logs subspark-app

# Проверьте статус
docker ps -a | grep subspark
```

### База данных не подключается

1. Проверьте, что контейнер `mysql` запущен
2. Проверьте переменные окружения (DB_USERNAME, DB_PASSWORD)
3. Проверьте логи MySQL: `docker logs subspark-mysql`

### Порт уже занят

Если порт 8000 или 8080 занят, измените в Environment Variables:
```
APP_PORT=8001
PMA_PORT=8081
```

### Ошибки загрузки файлов

1. Проверьте права на volume `uploads_data`
2. Увеличьте лимиты в `docker/nginx/subspark.conf` если нужно

## 📞 Дополнительно

- **GitHub**: https://github.com/Mitya-Shepelev/subspark
- **Документация**: См. DEPLOYMENT.md в репозитории
- **CLAUDE.md**: Справка для разработки (не в репозитории)

## ⚡ Quick Start (TL;DR)

```bash
# 1. Создайте stack в Portainer
# 2. Repository: https://github.com/Mitya-Shepelev/subspark.git
# 3. Compose path: docker-compose.yml
# 4. Environment variables:
#    MYSQL_ROOT_PASSWORD=your_password
#    DB_USERNAME=subspark_user
#    DB_PASSWORD=your_password
#    APP_URL=https://your-domain.com
# 5. Deploy!
# 6. Импортируйте БД через phpMyAdmin (port 8080)
# 7. Откройте http://your-server:8000
```

Готово! 🎉
