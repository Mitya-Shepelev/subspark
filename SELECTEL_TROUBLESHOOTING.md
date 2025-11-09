# Устранение проблем с Selectel в продакшене

## Проблема
Загрузка файлов через Selectel S3 не работает в Docker контейнере.

## Причина
AWS SDK для PHP не установлен в Docker контейнере, так как `composer install` не запускался при сборке образа.

## Решение

### 1. Убедитесь, что AWS SDK установлен
В Dockerfile добавлена строка для установки зависимостей:
```dockerfile
RUN cd /var/www/html/includes && composer install --no-dev --optimize-autoloader
```

### 2. Проверьте переменные окружения в Portainer
В стеке `subspark` должны быть установлены следующие переменные:

```yaml
environment:
  - SELECTEL_STATUS=1
  - SELECTEL_BUCKET=ваш_bucket
  - SELECTEL_REGION=ru-1
  - SELECTEL_KEY=ваш_ключ_доступа
  - SELECTEL_SECRET=ваш_секретный_ключ
  - SELECTEL_ENDPOINT=https://s3.ru-1.storage.selcloud.ru
  - SELECTEL_PUBLIC_BASE=https://ваш-uuid.selstorage.ru/
```

**Важно:**
- `SELECTEL_PUBLIC_BASE` должен заканчиваться на `/`
- Используйте именно переменную `SELECTEL_SECRET`, а не `SELECTEL_SECRET_KEY`

### 3. Диагностика проблемы

Зайдите в контейнер и запустите диагностический скрипт:

```bash
docker exec -it subspark-app sh
cd /var/www/html
php check_selectel.php
```

Скрипт проверит:
- Наличие переменных окружения
- Установку AWS SDK
- Инициализацию S3 клиента
- Возможность загрузки/удаления тестового файла

### 4. Проверьте логи

```bash
# Логи контейнера
docker logs subspark-app

# Логи PHP-FPM (внутри контейнера)
tail -f /var/log/supervisor/php-fpm-stderr.log
```

Ищите сообщения типа:
```
[Storage] Uploading to selectel: bucket=xxx, key=xxx
[Storage] Upload successful: xxx
```

Или ошибки:
```
[Storage] Upload failed: ...
```

## Частые проблемы

### Проблема: "S3 client not initialized"
**Решение:** Проверьте, что все переменные окружения установлены правильно.

### Проблема: "Access Denied"
**Решение:**
- Проверьте правильность `SELECTEL_KEY` и `SELECTEL_SECRET`
- Убедитесь, что у ключа есть права на запись в bucket

### Проблема: "Bucket does not exist"
**Решение:** Проверьте правильность `SELECTEL_BUCKET`

### Проблема: Файлы загружаются, но не отображаются
**Решение:**
- Проверьте `SELECTEL_PUBLIC_BASE` - он должен совпадать с публичным URL вашего контейнера
- Убедитесь, что контейнер настроен как публичный в панели Selectel

## После исправления

1. Пересоберите образ (GitHub Actions сделает это автоматически после push)
2. В Portainer обновите стек с опцией **Re-pull image and redeploy**
3. Запустите диагностический скрипт для проверки
4. Попробуйте загрузить файл через интерфейс приложения

## Полезные команды

```bash
# Проверить установленные пакеты composer
docker exec subspark-app sh -c "cd /var/www/html/includes && composer show"

# Проверить переменные окружения
docker exec subspark-app env | grep SELECTEL

# Запустить диагностику
docker exec subspark-app php /var/www/html/check_selectel.php
```
