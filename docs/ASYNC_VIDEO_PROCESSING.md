# Асинхронная обработка видео

## Проблема

При синхронной обработке видео:
- Пользователь ждет завершения FFmpeg (может занимать 2-10 минут)
- Блокируется PHP-FPM worker
- Возникают таймауты на уровне браузера/TCP
- При большой нагрузке могут закончиться PHP-FPM workers

## Решение

Асинхронная обработка через Redis очередь + background worker:
- ✅ Загрузка завершается мгновенно (возвращается 202 Accepted)
- ✅ FFmpeg обрабатывает видео в фоне
- ✅ Можно масштабировать (запустить несколько workers)
- ✅ Не блокирует основное приложение
- ✅ Пользователь получает уведомление когда видео готово

## Архитектура

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Browser    │────▶│   Web App    │────▶│ Redis Queue  │◀────│   Worker     │
│              │     │  (PHP-FPM)   │     │              │     │   (FFmpeg)   │
└──────────────┘     └──────────────┘     └──────────────┘     └──────────────┘
       ▲                                                               │
       │                                                               │
       └───────────────────────────────────────────────────────────────┘
                        Уведомление когда готово
```

## Как включить

### 1. Добавить колонку в БД (один раз)

```sql
-- Добавить колонку для отслеживания статуса обработки
ALTER TABLE i_user_uploads
ADD COLUMN processing_status ENUM('processing', 'completed', 'failed') DEFAULT 'completed' AFTER upload_time;

-- Для существующих записей установить 'completed'
UPDATE i_user_uploads SET processing_status = 'completed' WHERE processing_status IS NULL;
```

### 2. Включить в переменных окружения

В файле `.env` или в Portainer:

```bash
USE_ASYNC_VIDEO_PROCESSING=1
```

### 3. Убедиться что video-worker запущен

В `docker-compose.yml` уже есть сервис `video-worker`. Проверьте что он запущен:

```bash
docker-compose ps
```

Должны видеть:
```
subspark-app             Up
subspark-video-worker    Up
```

### 4. Перезапустить приложение

```bash
# Перезапустить с новыми переменными окружения
docker-compose down
docker-compose up -d
```

## Проверка работы

### Загрузить reel

1. Зайдите на сайт как обычный пользователь
2. Попробуйте загрузить reel video
3. Загрузка должна **завершиться мгновенно** (не ждать FFmpeg)
4. Увидите сообщение "Video is being processed. You will be notified when ready."

### Проверить логи worker'а

```bash
docker logs -f subspark-video-worker
```

Должны видеть:
```
[VideoWorker] Processing job job_xxx (type: reel_upload)
[VideoWorker] Processing reel upload: fileID=123, source=/var/www/html/uploads/...
[VideoWorker] Video duration: 15.5s, max: 90s
[VideoWorker] Converting to reels format
[VideoWorker] Reels conversion complete: /app/uploads/reels/...
[VideoWorker] Reel upload processing complete: fileID=123
```

### Проверить очередь Redis

```bash
docker exec -it redis redis-cli

# Проверить количество задач
> LLEN video_processing_queue

# Проверить активные задачи
> SMEMBERS video_processing_active

# Проверить проваленные задачи
> SMEMBERS video_processing_failed
```

## Масштабирование

Если нужно обрабатывать больше видео одновременно, запустите несколько workers:

```bash
docker-compose up -d --scale video-worker=3
```

Это запустит 3 worker'а параллельно.

## Откат к синхронной обработке

Если что-то не работает, можно вернуться к синхронной обработке:

1. Установите `USE_ASYNC_VIDEO_PROCESSING=0` в `.env`
2. Перезапустите: `docker-compose restart app`

## Мониторинг

### Проверить статус через PHP

Создайте файл `check_queue.php`:

```php
<?php
require_once 'includes/video_queue_helper.php';

$stats = getVideoQueueStats();
echo "Pending: {$stats['pending']}\n";
echo "Processing: {$stats['processing']}\n";
echo "Completed: {$stats['completed']}\n";
echo "Failed: {$stats['failed']}\n";
```

### UI для пользователей (TODO)

В будущем можно добавить:
- Индикатор прогресса обработки видео
- WebSocket уведомления когда видео готово
- Страницу со списком всех обрабатываемых видео

## Troubleshooting

### Worker не запускается

Проверьте логи:
```bash
docker logs subspark-video-worker
```

Частые проблемы:
- Redis недоступен (проверьте `REDIS_HOST`, `REDIS_PORT`)
- FFmpeg не найден (проверьте `FFMPEG_PATH=/usr/bin/ffmpeg`)
- Нет доступа к папкам uploads

### Видео застряло в статусе "processing"

1. Проверьте логи worker'а
2. Проверьте Redis очередь на failed jobs
3. Проверьте что есть свободное место на диске
4. Попробуйте обработать видео вручную:

```bash
docker exec -it subspark-video-worker sh
ffmpeg -i /app/uploads/files/2024-11-13/reel_xxx.mp4 -version
```

### Redis очередь растет

Если задач больше чем worker успевает обработать:

1. Запустите больше workers: `docker-compose up -d --scale video-worker=3`
2. Увеличьте ресурсы worker'а в `docker-compose.yml`:

```yaml
video-worker:
  deploy:
    resources:
      limits:
        cpus: '4.0'  # Было 2.0
        memory: 4G   # Было 2G
```

## Преимущества async обработки

✅ **Нет таймаутов** - пользователь не ждет FFmpeg
✅ **Масштабируемость** - можно запустить N workers
✅ **Отказоустойчивость** - задачи сохраняются в Redis
✅ **Мониторинг** - видно сколько задач в очереди
✅ **Приоритеты** - можно добавить приоритетную очередь
✅ **Повторы** - автоматический retry при ошибках
