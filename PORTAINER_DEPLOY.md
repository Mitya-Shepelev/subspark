# Развертывание SubSpark в Portainer

## ✅ Автоматическая сборка через GitHub Actions

При каждом `git push` в ветку `main` автоматически:
- Собирается Docker образ
- Публикуется в GitHub Container Registry (ghcr.io)
- Готов к использованию в Portainer

**Никакой работы через SSH!** Просто делайте `git push` и обновляйте стек в Portainer.

---

## 🚀 Первичная настройка

### Шаг 1: Сделайте репозиторий публичным (опционально)

Если репозиторий приватный, Docker образ тоже будет приватным.

**Вариант А: Публичный образ** (проще)
1. GitHub → Settings → Change repository visibility → Public

**Вариант Б: Приватный образ** (требует токен)
1. Создайте Personal Access Token с правами `read:packages`
2. В Portainer создайте Registry с ghcr.io и вашим токеном

### Шаг 2: Дождитесь первой сборки

После этого коммита GitHub Actions автоматически соберет образ (~5 минут).

Проверить статус:
- GitHub → Actions → "Build and Push Docker Image"
- Дождитесь зеленой галочки ✅

### Шаг 3: Создайте стек в Portainer

1. **Portainer** → **Stacks** → **Add stack**
2. Name: `subspark`
3. Build method: **Web editor**
4. Вставьте конфигурацию:

```yaml
version: '3.8'

services:
  app:
    image: ghcr.io/mitya-shepelev/subspark:latest
    container_name: subspark-app
    restart: unless-stopped
    ports:
      - "8082:80"  # Опционально, для прямого доступа
    pull_policy: always
    networks:
      - nginx-proxy-manager_default  # Для доступа из Nginx Proxy Manager
      - mysql-8_default              # Для доступа к MySQL
    volumes:
      - uploads_data:/var/www/html/uploads
    environment:
      - DB_SERVER=${DB_SERVER:-mysql-8-mysql_db-1}  # Имя контейнера MySQL в сети
      - DB_USERNAME=${DB_USERNAME:-subspark}
      - DB_PASSWORD=${DB_PASSWORD}
      - DB_DATABASE=${DB_DATABASE:-subspark}
      - SELECTEL_STATUS=${SELECTEL_STATUS:-0}
      - SELECTEL_BUCKET=${SELECTEL_BUCKET:-}
      - SELECTEL_REGION=${SELECTEL_REGION:-ru-1}
      - SELECTEL_KEY=${SELECTEL_KEY:-}
      - SELECTEL_SECRET=${SELECTEL_SECRET:-}
      - SELECTEL_ENDPOINT=${SELECTEL_ENDPOINT:-https://s3.ru-1.storage.selcloud.ru}
      - SELECTEL_PUBLIC_BASE=${SELECTEL_PUBLIC_BASE:-}

volumes:
  uploads_data:
    driver: local

networks:
  nginx-proxy-manager_default:
    external: true
  mysql-8_default:
    external: true
```

**Важно:**
- Контейнер подключен к сетям `nginx-proxy-manager_default` и `mysql-8_default`
- В Nginx Proxy Manager используйте: `http://subspark-app:80`
- DB_SERVER указывает на контейнер MySQL (по умолчанию `mysql-8-mysql_db-1`)
- Используйте правильные имена переменных: `DB_SERVER`, `DB_USERNAME`, `DB_DATABASE` (не DB_HOST/DB_USER/DB_NAME)

### Шаг 4: Добавьте переменные окружения

```
DB_PASSWORD=ваш_пароль
```

Для Selectel:
```
SELECTEL_STATUS=1
SELECTEL_BUCKET=ваш_bucket
SELECTEL_KEY=ваш_key
SELECTEL_SECRET=ваш_secret
SELECTEL_PUBLIC_BASE=https://123456.selcdn.ru/container-name/
```

### Шаг 5: Deploy

Нажмите **Deploy the stack** - образ скачается за 10-30 секунд!

---

## 🔄 Обновление кода (автоматически)

### После каждого git push:

1. **Закоммитьте изменения:**
```bash
git add .
git commit -m "Update code"
git push origin main
```

2. **Дождитесь сборки образа** (проверьте GitHub Actions, ~5 минут)

3. **Обновите стек в Portainer:**
   - Откройте стек `subspark`
   - Нажмите **Pull and redeploy** или **Update the stack**
   - Включите **Re-pull image and redeploy**
   - Нажмите **Update**

**Готово!** Новый код автоматически развернут.

---

## 📝 Преимущества этого подхода

✅ Никакого SSH доступа к серверу
✅ Автоматическая сборка при каждом push
✅ Быстрое скачивание образа (10-30 сек)
✅ Версионирование образов (latest + SHA)
✅ Просто обновлять через Portainer UI

---

## 🔧 Настройка Nginx Proxy Manager

Если вы используете Nginx Proxy Manager (рекомендуется):

### 1. Создайте Proxy Host

1. Откройте **Nginx Proxy Manager** → **Proxy Hosts** → **Add Proxy Host**
2. **Details:**
   - Domain Names: `subspark.ru` (ваш домен)
   - Scheme: `http`
   - Forward Hostname/IP: `subspark-app`
   - Forward Port: `80`
   - ✅ Cache Assets
   - ✅ Block Common Exploits
   - ✅ Websockets Support

3. **SSL:**
   - ✅ SSL Certificate (Let's Encrypt)
   - ✅ Force SSL
   - ✅ HTTP/2 Support

4. **Advanced:**
   ```nginx
   client_max_body_size 128M;
   ```

5. **Save**

Теперь приложение доступно на `https://subspark.ru`!

---

## 🔧 Альтернатива: Nginx на хосте (если НЕ используете Nginx Proxy Manager)

### 1. Создайте конфигурацию nginx на хосте

```bash
# Создайте файл /etc/nginx/sites-available/subspark
server {
    listen 80;
    server_name ваш_домен.ru;

    client_max_body_size 128M;

    location / {
        proxy_pass http://localhost:8082;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### 2. Активируйте конфигурацию

```bash
ln -s /etc/nginx/sites-available/subspark /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```
