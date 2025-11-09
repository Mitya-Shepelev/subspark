# Развертывание SubSpark в Portainer

## Проблема
Сборка образа из GitHub через Portainer занимает слишком много времени (20+ минут).

## Решение: Собрать образ локально на сервере

### Шаг 1: SSH на сервер и соберите образ

```bash
# Перейдите в директорию или клонируйте репозиторий
cd /tmp
git clone https://github.com/Mitya-Shepelev/subspark.git
cd subspark

# Соберите Docker образ
docker build -t subspark:latest .

# Проверьте что образ создан
docker images | grep subspark
```

Сборка займет 3-5 минут.

### Шаг 2: Создайте стек в Portainer

1. Откройте **Portainer** → **Stacks** → **Add stack**
2. Name: `subspark`
3. Build method: **Web editor**

### Шаг 3: Вставьте конфигурацию

```yaml
version: '3.8'

services:
  app:
    image: subspark:latest
    container_name: subspark-app
    restart: unless-stopped
    network_mode: host
    volumes:
      - uploads_data:/var/www/html/uploads
    environment:
      - DB_HOST=${DB_HOST:-localhost}
      - DB_NAME=${DB_NAME:-subspark}
      - DB_USER=${DB_USER:-subspark}
      - DB_PASSWORD=${DB_PASSWORD}
      - DB_PORT=${DB_PORT:-3306}
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
```

### Шаг 4: Добавьте переменные окружения

В разделе **Environment variables**:

```
DB_PASSWORD=ваш_пароль_mysql
```

Для Selectel (опционально):
```
SELECTEL_STATUS=1
SELECTEL_BUCKET=ваш_контейнер
SELECTEL_KEY=ваш_access_key
SELECTEL_SECRET=ваш_secret_key
SELECTEL_PUBLIC_BASE=https://123456.selcdn.ru/container-name/
```

### Шаг 5: Deploy

Нажмите **Deploy the stack** - развертывание займет 5-10 секунд!

---

## Обновление кода (после git push)

Когда вы обновляете код в GitHub:

```bash
# SSH на сервер
cd /tmp/subspark
git pull origin main
docker build -t subspark:latest .
docker restart subspark-app
```

Или через Portainer:
1. Пересоберите образ на сервере (команды выше)
2. В Portainer найдите стек `subspark`
3. Нажмите **Update the stack** → **Re-pull image** → **Update**

---

## Быстрый старт (одна команда)

```bash
cd /tmp && \
git clone https://github.com/Mitya-Shepelev/subspark.git && \
cd subspark && \
docker build -t subspark:latest . && \
echo "✅ Образ subspark:latest готов! Теперь создайте стек в Portainer"
```
