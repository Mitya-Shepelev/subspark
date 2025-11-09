# Исправление отображения аватаров и обложек для Selectel

## Проблема
Загрузка аватаров и обложек профиля работала, файлы загружались в Selectel, но не отображались в профиле.

## Причина
Функции `iN_UserAvatar()` и `iN_UserCover()` в `includes/functions.php` были написаны для поддержки только:
- Amazon S3
- Wasabi
- DigitalOcean Spaces

Они **не поддерживали Selectel** и другие S3-совместимые провайдеры.

## Решение

Обновлены обе функции для использования унифицированной системы хранилища:

### До исправления:
```php
if ($s3Status == 1) {
    $data = 'https://' . $s3['s3_bucket'] . '.s3.' . $s3['s3_region'] . '.amazonaws.com/' . $avatarPath;
} else if($wasStatus == 1) {
    $data = 'https://' . $s3['was_bucket'] . '.s3.' . $s3['was_region'] . '.wasabisys.com/' . $avatarPath;
} else if($oceanStatus == 1) {
    $data = 'https://'.$s3['ocean_space_name'].'.'.$s3['ocean_region'].'.digitaloceanspaces.com/'. $avatarPath;
} else {
    $data = $base_url . $avatarPath;
}
```

### После исправления:
```php
// Use unified storage_public_url if available
if (function_exists('storage_public_url') && $avatarPath) {
    $data = storage_public_url($avatarPath);
} else if ($s3Status == 1) {
    // ... fallback to old logic
}
```

## Как это работает

Функция `storage_public_url()` из `includes/object_storage.php`:
1. Автоматически определяет активный провайдер (Selectel, S3, MinIO, etc.)
2. Возвращает правильный публичный URL в зависимости от настроек
3. Для Selectel использует `SELECTEL_PUBLIC_BASE` из переменных окружения

## Преимущества нового подхода

1. ✅ **Единая точка управления** - вся логика URL в одном месте
2. ✅ **Поддержка всех провайдеров** - автоматически работает с Selectel, MinIO, S3, и др.
3. ✅ **Обратная совместимость** - старые провайдеры продолжают работать
4. ✅ **Легко расширять** - добавление нового провайдера не требует изменений в функциях

## Файлы изменены

- `includes/functions.php`:
  - `iN_UserAvatar()` - строки 118-162
  - `iN_UserCover()` - строки 166-197

## Тестирование

После обновления:

1. Загрузите новый аватар через профиль
2. Обновите страницу
3. Аватар должен отображаться корректно
4. URL аватара должен быть: `https://UUID.selstorage.ru/uploads/avatars/YYYY-MM-DD/filename.png`

## Проверка в базе данных

```sql
-- Посмотреть последние загруженные аватары
SELECT * FROM i_user_avatars ORDER BY avatar_id DESC LIMIT 10;

-- Проверить текущий аватар пользователя
SELECT iuid, i_username, user_avatar FROM i_users WHERE iuid = YOUR_USER_ID;
```

В таблице `i_user_avatars` поле `avatar_path` содержит относительный путь:
```
uploads/avatars/2025-11-10/avatar_1731234567_123.png
```

Функция `storage_public_url()` преобразует его в полный URL:
```
https://6a9c6762-1a83-4aab-81a5-efefedbd168a.selstorage.ru/uploads/avatars/2025-11-10/avatar_1731234567_123.png
```

## Связанные изменения

Это исправление работает вместе с:
- Установкой AWS SDK в Docker (`Dockerfile`)
- Унифицированной системой хранилища (`includes/object_storage.php`)
- Переменными окружения Selectel в Portainer

## Если аватар всё ещё не отображается

1. Проверьте переменные окружения:
   ```bash
   docker exec subspark-app env | grep SELECTEL
   ```

2. Проверьте, что `SELECTEL_PUBLIC_BASE` правильный и заканчивается на `/`

3. Проверьте, что файл действительно загружен в Selectel:
   ```bash
   docker exec subspark-app php /var/www/html/check_selectel.php
   ```

4. Проверьте логи при загрузке аватара:
   ```bash
   docker logs subspark-app --tail 50 | grep -i "storage\|upload\|avatar"
   ```

5. Откройте URL аватара напрямую в браузере - он должен открываться

## Аналогичные функции

Те же принципы применяются к:
- `iN_UserCover()` - обложка профиля
- Другим функциям, работающим с файлами пользователей

Все они теперь используют `storage_public_url()` для генерации правильных URL.
