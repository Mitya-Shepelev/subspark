# SubSpark Video Processing Worker

Асинхронный worker для обработки видео с использованием FFmpeg и Redis очереди.

## Особенности

- **Асинхронная обработка**: Не блокирует основное приложение
- **Масштабируемость**: Можно запустить несколько worker'ов параллельно
- **Отказоустойчивость**: Задачи сохраняются в Redis при сбое worker'а
- **Ограничение ресурсов**: CPU и RAM лимиты через Docker

## Поддерживаемые операции

1. **convert** - Конвертация видео в MP4 (H.264 + AAC)
2. **thumbnail** - Создание миниатюры из видео (JPEG)
3. **reel_blur** - Создание размытого вертикального видео для reels

## Архитектура

```
┌─────────────┐      ┌──────────────┐      ┌─────────────┐      ┌──────────────┐
│  Web App    │─────▶│ Redis Queue  │◀─────│   Worker    │─────▶│   Storage    │
│  (subspark) │      │              │      │   (FFmpeg)  │      │  (Selectel)  │
└─────────────┘      └──────────────┘      └─────────────┘      └──────────────┘
```

## Запуск

### Docker Compose (рекомендуется)

```bash
docker-compose up -d video-worker
```

### Вручную

```bash
# Установить переменные окружения
export REDIS_HOST=localhost
export REDIS_PORT=6379
export FFMPEG_PATH=/usr/bin/ffmpeg
export FFMPEG_PROBE=/usr/bin/ffprobe

# Запустить worker
php worker.php
```

## Переменные окружения

| Переменная       | Описание                    | По умолчанию    |
|------------------|-----------------------------|-----------------|
| `REDIS_HOST`     | Хост Redis сервера          | `localhost`     |
| `REDIS_PORT`     | Порт Redis сервера          | `6379`          |
| `REDIS_PASSWORD` | Пароль Redis (опционально)  | -               |
| `REDIS_DB`       | Номер БД Redis              | `0`             |
| `FFMPEG_PATH`    | Путь к ffmpeg               | `/usr/bin/ffmpeg` |
| `FFMPEG_PROBE`   | Путь к ffprobe              | `/usr/bin/ffprobe` |

## Масштабирование

Запустить несколько worker'ов для параллельной обработки:

```bash
docker-compose up -d --scale video-worker=3
```

Или на разных серверах:

```bash
# Сервер 1
docker run -d --name worker1 \
  -e REDIS_HOST=redis.example.com \
  -e REDIS_PORT=6379 \
  subspark-video-worker

# Сервер 2
docker run -d --name worker2 \
  -e REDIS_HOST=redis.example.com \
  -e REDIS_PORT=6379 \
  subspark-video-worker
```

## Мониторинг

### Проверка статуса очереди

```php
require_once 'includes/video_queue_helper.php';

$stats = getVideoQueueStats();
echo "Pending: {$stats['pending']}\n";
echo "Processing: {$stats['processing']}\n";
echo "Completed: {$stats['completed']}\n";
echo "Failed: {$stats['failed']}\n";
```

### Логи worker'а

```bash
docker logs -f subspark-video-worker
```

### Логи Redis

```bash
redis-cli
> LLEN video_processing_queue        # Количество задач в очереди
> SMEMBERS video_processing_active   # Активные задачи
> SMEMBERS video_processing_failed   # Проваленные задачи
```

## Ограничения ресурсов

В `docker-compose.yml`:

```yaml
deploy:
  resources:
    limits:
      cpus: '2.0'      # Максимум 2 CPU
      memory: 2G       # Максимум 2GB RAM
    reservations:
      cpus: '0.5'      # Минимум 0.5 CPU
      memory: 512M     # Минимум 512MB RAM
```

## Использование в коде

```php
// Добавить задачу на конвертацию видео
require_once 'includes/video_queue_helper.php';

$jobId = queueVideoConversion(
    '/app/uploads/videos/raw_video.mov',
    '/app/uploads/videos',
    'video_12345'
);

if ($jobId) {
    echo "Job queued: {$jobId}\n";

    // Проверить статус позже
    $status = getVideoJobStatus($jobId);
    echo "Status: {$status['status']}\n";
}
```

## Отладка

### Запуск в режиме отладки

```bash
php worker.php
```

### Проверка доступности FFmpeg

```bash
docker exec subspark-video-worker ffmpeg -version
```

### Ручная обработка задачи

```bash
docker exec -it subspark-video-worker sh
# Внутри контейнера:
ffmpeg -i /app/uploads/test.mov -c:v libx264 /app/uploads/test.mp4
```
