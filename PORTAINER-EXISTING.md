# SubSpark - Portainer Deployment (Existing MySQL)

Инструкция по развёртыванию SubSpark через Portainer Stacks с использованием **существующих** MySQL и phpMyAdmin.

## 📋 Требования

- ✅ Docker и Portainer установлены
- ✅ MySQL контейнер уже запущен
- ✅ phpMyAdmin контейнер уже запущен (опционально)
- ✅ Сети: `mysql-8_default` и `nginx-proxy-manager_default`

## 🔍 Подготовка

### Шаг 1: Узнайте имя MySQL контейнера

В Portainer:
1. Перейдите в **Containers**
2. Найдите ваш MySQL контейнер
3. Запомните его имя (обычно: `mysql-8`, `mysql`, `mariadb`)

### Шаг 2: Создайте базу данных

Через phpMyAdmin или консоль MySQL:

```sql
CREATE DATABASE subspark CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'subspark_user'@'%' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON subspark.* TO 'subspark_user'@'%';
FLUSH PRIVILEGES;
```

**Через Portainer Console:**
1. Containers → Ваш MySQL контейнер → Console → Connect
2. Выполните:
```bash
mysql -u root -p
# Введите root пароль, затем выполните SQL выше
```

## 🚀 Deploy через Portainer

### Вариант 1: Через Git Repository (Рекомендуется)

#### Шаг 1: Создайте Stack

1. **Stacks** → **+ Add stack**
2. Имя: `subspark`
3. Build method: **Repository**

#### Шаг 2: Настройте Repository

```
Repository URL: https://github.com/Mitya-Shepelev/subspark.git
Repository reference: refs/heads/main
Compose path: docker-compose.portainer.yml
```

**⚠️ ВАЖНО:** Используйте `docker-compose.portainer.yml` (НЕ `docker-compose.yml`)!

#### Шаг 3: Environment Variables

Добавьте переменные:

```env
# ОБЯЗАТЕЛЬНЫЕ
DB_SERVER=mysql-8
DB_USERNAME=subspark_user
DB_PASSWORD=your_secure_password_here
DB_DATABASE=subspark
APP_URL=https://your-domain.com
APP_PORT=8000

# ОПЦИОНАЛЬНЫЕ
APP_ENV=production
S3_STATUS=0
WASABI_STATUS=0
MINIO_STATUS=0
```

**⚠️ Замените:**
- `DB_SERVER` на имя вашего MySQL контейнера
- `DB_PASSWORD` на ваш пароль
- `APP_URL` на ваш домен

#### Шаг 4: Deploy

Нажмите **Deploy the stack**

### Вариант 2: Через Web Editor

#### Шаг 1: Создайте Stack

1. **Stacks** → **+ Add stack**
2. Имя: `subspark`
3. Build method: **Web editor**

#### Шаг 2: Вставьте compose файл

Скопируйте содержимое из:
https://github.com/Mitya-Shepelev/subspark/blob/main/docker-compose.portainer.yml

#### Шаг 3: Environment Variables

Добавьте те же переменные, что и в Варианте 1

#### Шаг 4: Deploy

Нажмите **Deploy the stack**

## 📊 После деплоя

### Проверка контейнера

В Portainer → **Containers**:
- ✅ `subspark-app` должен быть **running**

### Проверка подключения к БД

1. Containers → `subspark-app` → **Logs**
2. Проверьте, нет ли ошибок подключения к БД
3. Если есть ошибка - проверьте `DB_SERVER` и credentials

### Импорт данных

Через ваш существующий phpMyAdmin:
1. Откройте phpMyAdmin
2. Выберите БД `subspark`
3. Импорт → Выберите `.sql` файл
4. Нажмите **Go**

### Доступ к приложению

```
http://your-server:8000
```

## 🌐 Настройка через Nginx Proxy Manager

Если хотите использовать домен через NPM:

### Шаг 1: Добавьте Proxy Host в NPM

1. Откройте Nginx Proxy Manager
2. **Proxy Hosts** → **Add Proxy Host**

### Шаг 2: Настройте

**Details:**
- Domain Names: `your-domain.com`
- Scheme: `http`
- Forward Hostname / IP: `subspark-app` (имя контейнера)
- Forward Port: `80`
- ✅ Cache Assets
- ✅ Block Common Exploits
- ✅ Websockets Support

**SSL:**
- ✅ Force SSL
- ✅ HTTP/2 Support
- SSL Certificate: Request a new SSL Certificate (Let's Encrypt)
- ✅ Email: your@email.com
- ✅ I Agree to Let's Encrypt Terms

### Шаг 3: Сохраните

Теперь доступно через: `https://your-domain.com`

## 🔧 Проверка сетей

### Убедитесь, что контейнер подключён к правильным сетям:

В Portainer:
1. **Containers** → `subspark-app`
2. **Network** (вкладка)
3. Должны быть подключены:
   - ✅ `mysql-8_default`
   - ✅ `nginx-proxy-manager_default`

Если нет - добавьте вручную или пересоздайте stack.

## 🐛 Troubleshooting

### "Database connection error"

**Проверьте имя MySQL контейнера:**
```bash
docker ps | grep mysql
```

Если имя не `mysql-8`, обновите переменную `DB_SERVER` в Portainer:
1. Stacks → `subspark` → **Editor**
2. Измените Environment Variables
3. Update the stack

**Проверьте, что контейнеры в одной сети:**
```bash
docker network inspect mysql-8_default
```

Должны быть оба контейнера: ваш MySQL и `subspark-app`

### "Cannot connect to MySQL server"

1. Проверьте, что MySQL контейнер запущен
2. Проверьте credentials в phpMyAdmin
3. Убедитесь, что пользователь создан с хостом `'%'` (не `'localhost'`)

```sql
-- Проверьте пользователя
SELECT user, host FROM mysql.user WHERE user = 'subspark_user';

-- Если host = 'localhost', пересоздайте:
DROP USER 'subspark_user'@'localhost';
CREATE USER 'subspark_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON subspark.* TO 'subspark_user'@'%';
FLUSH PRIVILEGES;
```

### "Network not found"

Если сети `mysql-8_default` или `nginx-proxy-manager_default` не найдены:

1. Проверьте названия сетей:
```bash
docker network ls
```

2. Обновите `docker-compose.portainer.yml`:
```yaml
networks:
  your-actual-mysql-network-name:
    external: true
  your-actual-npm-network-name:
    external: true
```

### Порт 8000 занят

Измените в Environment Variables:
```
APP_PORT=8001
```

## 💾 Backup & Restore

### Backup базы данных

Через ваш существующий phpMyAdmin или:

```bash
docker exec your-mysql-container mysqldump -u subspark_user -p subspark > backup.sql
```

### Backup uploads

```bash
docker run --rm \
  -v subspark_uploads_data:/data \
  -v $(pwd):/backup \
  alpine tar czf /backup/uploads-backup.tar.gz -C /data .
```

### Restore

Через phpMyAdmin или:

```bash
docker exec -i your-mysql-container mysql -u subspark_user -p subspark < backup.sql
```

## 🔄 Обновление

### Через Portainer:

1. **Stacks** → `subspark` → **Editor**
2. Нажмите **Pull and redeploy**

### Через Git:

Если деплоили через Repository, просто:
1. Обновите код в GitHub
2. В Portainer нажмите **Pull and redeploy**

## 📖 Структура файлов

```
docker-compose.yml              - Полный стек (со своей MySQL)
docker-compose.portainer.yml    - ДЛЯ СУЩЕСТВУЮЩЕЙ MySQL (используйте этот!)
.env.portainer                  - Пример переменных (полный стек)
.env.portainer.existing         - Пример переменных (существующая MySQL)
```

## 🎯 Чеклист развёртывания

- [ ] Узнал имя MySQL контейнера
- [ ] Создал БД `subspark` через phpMyAdmin
- [ ] Создал пользователя `subspark_user` с хостом `%`
- [ ] Создал Stack в Portainer
- [ ] Указал правильный `DB_SERVER` в переменных
- [ ] Задеплоил stack
- [ ] Проверил логи контейнера
- [ ] Импортировал данные через phpMyAdmin
- [ ] Настроил домен в NPM (опционально)
- [ ] Открыл сайт и проверил работу

## 💡 Рекомендации

1. **Используйте `docker-compose.portainer.yml`** вместо `docker-compose.yml`
2. **Правильно укажите `DB_SERVER`** - это имя вашего MySQL контейнера
3. **Создавайте пользователя с `@'%'`** а не `@'localhost'`
4. **Используйте NPM** для SSL и доменов
5. **Делайте регулярные бэкапы** БД и uploads

## 📞 Поддержка

- **GitHub**: https://github.com/Mitya-Shepelev/subspark
- **Основная документация**: PORTAINER.md (для полного стека)
- **Общий деплой**: DEPLOYMENT.md

---

**Quick Start (TL;DR):**

```bash
# 1. Создайте БД в phpMyAdmin:
#    subspark / subspark_user / password

# 2. Portainer → Stacks → Add Stack
#    Repository: https://github.com/Mitya-Shepelev/subspark.git
#    Compose path: docker-compose.portainer.yml

# 3. Environment Variables:
#    DB_SERVER=mysql-8  (ИМЯ ВАШЕГО MySQL КОНТЕЙНЕРА!)
#    DB_USERNAME=subspark_user
#    DB_PASSWORD=your_password
#    DB_DATABASE=subspark
#    APP_URL=https://your-domain.com

# 4. Deploy → Импорт данных → Готово!
```
