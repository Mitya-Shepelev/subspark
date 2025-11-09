# Устранение проблем с загрузкой файлов

## Проблема
Загружаются только маленькие файлы (например, до 1-2 MB), большие файлы не загружаются.

## Причины

Существует несколько мест, где может быть ограничение на размер загружаемых файлов:

1. **Nginx Proxy Manager** (если используется) - по умолчанию 1MB
2. **Nginx внутри контейнера** - настроено на 128MB
3. **PHP** - настроено на 128MB
4. **PHP-FPM буферы** - могут быть недостаточными

## Решение

### 1. Проверьте лимиты в Nginx Proxy Manager

Это **САМАЯ ЧАСТАЯ ПРИЧИНА** проблем с загрузкой!

**В Nginx Proxy Manager:**
1. Откройте **Proxy Hosts**
2. Найдите ваш домен (например, `subspark.ru`)
3. Нажмите **Edit** (три точки → Edit)
4. Перейдите на вкладку **Advanced**
5. Добавьте в **Custom Nginx Configuration**:

```nginx
# Increase upload size limit to 128MB
client_max_body_size 128M;
client_body_buffer_size 128k;
client_body_timeout 300s;

# Proxy timeouts for large uploads
proxy_connect_timeout 300s;
proxy_send_timeout 300s;
proxy_read_timeout 300s;
```

6. Нажмите **Save**

### 2. Проверьте настройки внутри контейнера

После обновления Dockerfile все настройки уже должны быть правильными:

**Nginx (внутри контейнера):**
- `client_max_body_size 128M;`
- `client_body_buffer_size 128k;`
- `client_body_timeout 300s;`

**PHP:**
- `upload_max_filesize = 128M`
- `post_max_size = 128M`
- `memory_limit = 256M`
- `max_execution_time = 300`

**PHP-FPM:**
- `fastcgi_read_timeout 300;`
- `fastcgi_buffer_size 128k;`
- `fastcgi_buffers 256 16k;`

### 3. Диагностика

#### Способ 1: Через браузер
Откройте в браузере:
```
https://ваш-домен.ru/check_upload_limits.php
```

Вы увидите:
- Текущие лимиты PHP
- Эффективный лимит загрузки
- Предупреждения о конфигурации
- Форму для тестовой загрузки

#### Способ 2: Через командную строку
```bash
# Подключитесь к контейнеру
docker exec -it subspark-app sh

# Проверьте настройки PHP
php -i | grep -E "upload_max_filesize|post_max_size|memory_limit"

# Или запустите диагностический скрипт
php /var/www/html/check_upload_limits.php
```

### 4. Проверьте логи

#### Логи Nginx Proxy Manager
В интерфейсе Nginx Proxy Manager → Proxy Hosts → три точки → View Logs

Ищите ошибки типа:
```
client intended to send too large body
```

#### Логи контейнера
```bash
docker logs subspark-app --tail 100
```

Ищите ошибки:
```
PHP Warning: POST Content-Length
PHP Warning: File upload error
```

#### Логи Nginx внутри контейнера
```bash
docker exec subspark-app tail -f /var/log/nginx/subspark_error.log
```

## Типичные сценарии

### Сценарий 1: Файлы до 1MB загружаются, больше - нет
**Причина:** Nginx Proxy Manager имеет дефолтный лимит 1MB
**Решение:** Добавьте `client_max_body_size 128M;` в Advanced настройки Proxy Host

### Сценарий 2: Файлы до 2MB загружаются, больше - нет
**Причина:** Где-то установлен лимит 2MB (возможно в PHP или Nginx)
**Решение:** Проверьте все настройки через `check_upload_limits.php`

### Сценарий 3: Загрузка начинается, но обрывается
**Причина:** Недостаточные таймауты
**Решение:** Увеличьте таймауты в Nginx Proxy Manager:
```nginx
proxy_connect_timeout 300s;
proxy_send_timeout 300s;
proxy_read_timeout 300s;
```

### Сценарий 4: Ошибка 413 Request Entity Too Large
**Причина:** Nginx блокирует большие запросы
**Решение:** Увеличьте `client_max_body_size` в Nginx Proxy Manager И в контейнере

### Сценарий 5: Ошибка 504 Gateway Timeout
**Причина:** Недостаточные таймауты для обработки больших файлов
**Решение:** Увеличьте все таймауты (proxy_read_timeout, fastcgi_read_timeout)

## Пошаговая проверка

1. ✅ Откройте `https://ваш-домен.ru/check_upload_limits.php`
2. ✅ Проверьте, что PHP показывает 128MB лимиты
3. ✅ Попробуйте загрузить тестовый файл через форму на странице
4. ✅ Если загрузка не работает, проверьте логи
5. ✅ Проверьте настройки Nginx Proxy Manager (Advanced)
6. ✅ Убедитесь, что образ Docker обновлён до последней версии

## После исправления

1. Если изменили Nginx Proxy Manager - изменения применятся сразу
2. Если изменили код - пересоберите образ и обновите стек в Portainer
3. Протестируйте загрузку файла разного размера:
   - 500 KB - должно работать
   - 5 MB - должно работать
   - 50 MB - должно работать
   - 100 MB - должно работать

## Полезные команды

```bash
# Проверить настройки PHP
docker exec subspark-app php -i | grep upload

# Проверить настройки Nginx
docker exec subspark-app cat /etc/nginx/http.d/default.conf | grep client_max_body_size

# Посмотреть логи в реальном времени
docker logs -f subspark-app

# Перезапустить контейнер
docker restart subspark-app
```

## Рекомендуемые настройки для разных сценариев

### Для обычных фото (до 10MB)
```nginx
client_max_body_size 16M;
```

### Для HD фото и коротких видео (до 100MB)
```nginx
client_max_body_size 128M;
```

### Для длинных видео (до 500MB)
```nginx
client_max_body_size 512M;
```

В нашем случае установлено **128MB**, что подходит для большинства сценариев.
