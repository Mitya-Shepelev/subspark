#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Скрипт для перевода языкового файла ru.php с английского на русский
"""

import re

# Словарь переводов (основные фразы для социальной сети)
translations = {
    # Welcome
    'Welcome to': 'Добро пожаловать в',
    'Sign up to': 'Зарегистрируйтесь, чтобы',
    'make money': 'зарабатывать деньги',
    'and discover': 'и открывать для себя',
    'exlusively': 'эксклюзивно',
    'the contents of your favouret stars': 'контент ваших любимых звезд',
    'Menu': 'Меню',
    'Login': 'Войти',
    'Sign Up': 'Регистрация',
    'Remember that you can monetize your content.': 'Помните, что вы можете монетизировать свой контент.',
    'Try': 'Попробуйте',
    'for free': 'бесплатно',

    # Footer
    'About Us': 'О нас',
    'Privacy Policies': 'Политика конфиденциальности',
    'Contact': 'Контакты',
    'Terms Of Use': 'Условия использования',
    'Cookies': 'Файлы cookie',
    'Terms of sales': 'Условия продажи',

    # 404
    'Page Not Found': 'Страница не найдена',
    "Sorry, this page isn't available.": 'К сожалению, эта страница недоступна.',
    'The link you followed may be broken, or the page may have been removed. Go back to': 'Ссылка, по которой вы перешли, может быть неработающей, или страница могла быть удалена. Вернуться на',
    'Home Page': 'Главную страницу',

    # Header
    'Look at your profile': 'Посмотреть свой профиль',
    'Dashboard': 'Панель управления',
    'Payments': 'Платежи',
    'Subscribers': 'Подписчики',
    'Subscriptions': 'Подписки',
    'Settings': 'Настройки',
    'Day / Night Mode': 'Дневной / Ночной режим',
    'Logout': 'Выход',
    'Saved': 'Сохраненное',
    'Languages': 'Языки',

    # Messages
    'See all in messenger': 'Смотреть все в мессенджере',
    'Messages': 'Сообщения',
    'Notifications': 'Уведомления',
    'See All Messages': 'Посмотреть все сообщения',
    'Delete Message': 'Удалить сообщение',
    'Mark as read': 'Отметить как прочитанное',
    'See all notifications': 'Посмотреть все уведомления',
    'Remove This Notification': 'Удалить это уведомление',

    # Login
    'You Are Back': 'С возвращением',
    'Login to access your account': 'Войдите, чтобы получить доступ к своему аккаунту',
    'Login With': 'Войти через',
    'Twitter': 'Twitter',
    'Google': 'Google',
    'or directly': 'или напрямую',
    'Username or Email address': 'Имя пользователя или Email',
    'e.g: john or john@hotmail.com': 'например: ivan или ivan@mail.ru',
    'Password': 'Пароль',
    "Not a member yet?": 'Еще не зарегистрированы?',
    'Sign up now': 'Зарегистрируйтесь сейчас',
    'Forgot your password?': 'Забыли пароль?',
    'Change Password': 'Изменить пароль',
    'Never mind! It happens to everyone...': 'Не беспокойтесь! Это случается со всеми...',
    'Send': 'Отправить',
    'Already a Member?': 'Уже зарегистрированы?',
    'Email Address': 'Email адрес',
    'Your Email Address': 'Ваш email адрес',

    # Register
    'You are': 'Вы',
    'Your full name': 'Ваше полное имя',
    'Your username': 'Ваше имя пользователя',
    'By clicking Register, you agree to our': 'Нажимая Регистрация, вы соглашаетесь с нашими',
    'and': 'и',
    'Cookie Policy': 'Политикой использования файлов cookie',
    'Please fill in all the information completely.': 'Пожалуйста, заполните всю информацию полностью.',
    'Your password must be at least 6 characters. It should not contain spaces.': 'Ваш пароль должен содержать не менее 6 символов. Он не должен содержать пробелы.',
    'The username should not be empty.': 'Имя пользователя не должно быть пустым.',
    'This username is used by someone else. Please try to create a different username.': 'Это имя пользователя уже занято. Пожалуйста, попробуйте создать другое имя пользователя.',
    'Username must be at least 6 characters.': 'Имя пользователя должно содержать не менее 6 символов.',
    'Your username contains invalid characters.': 'Ваше имя пользователя содержит недопустимые символы.',
    'We could not send the confirmation email. You can continue, but please verify your email later or contact support.': 'Мы не смогли отправить письмо с подтверждением. Вы можете продолжить, но пожалуйста, подтвердите свой email позже или свяжитесь с поддержкой.',

    # Left Menu
    'Profile': 'Профиль',
    'Home Page': 'Главная',
    'Newsfeed': 'Лента новостей',
    'Explore': 'Обзор',
    'Premium': 'Премиум',
    'Our Creators': 'Наши авторы',

    # No More
    'No more notification will be shown.': 'Больше нет уведомлений.',
    'There is nothing to show for the moment. Start by posting something or follow members to make sure you don\'t miss any posts!': 'Пока нечего показать. Начните с публикации чего-нибудь или подпишитесь на участников, чтобы не пропустить посты!',
    'No more post will be shown on this profile!': 'Больше нет постов в этом профиле!',

    # Privacy
    'Who can see this?': 'Кто может это видеть?',
    'Everyone': 'Все',
    'Followers': 'Подписчики',
    'Select Privacy': 'Выберите приватность',

    # Post
    'Edit Post': 'Редактировать пост',
    'Edit Comment': 'Редактировать комментарий',
}

def translate_line(line):
    """Переводит строку, сохраняя структуру PHP"""
    # Пропускаем комментарии и пустые строки
    if line.strip().startswith('//') or line.strip().startswith('/*') or line.strip().startswith('*') or not line.strip():
        return line

    # Пропускаем строки без переводимого текста
    if '=>' not in line:
        return line

    # Ищем строки вида 'key' => 'value',
    match = re.match(r"^(\s*['\"][\w\-_]+['\"]\s*=>\s*['\"])(.+?)(['\"],?\s*)$", line)
    if match:
        prefix = match.group(1)
        text = match.group(2)
        suffix = match.group(3)

        # Проверяем, есть ли в тексте PHP переменные или HTML
        has_php_vars = '$' in text or 'href=' in text or '<a ' in text or '</a>' in text or '<span>' in text

        # Если текст уже на русском (содержит кириллицу), пропускаем
        if any('\u0400' <= char <= '\u04FF' for char in text):
            return line

        # Переводим текст
        translated = text
        for eng, rus in translations.items():
            if eng in translated:
                translated = translated.replace(eng, rus)

        # Возвращаем переведенную строку
        if translated != text:
            return prefix + translated + suffix + '\n'

    return line

# Читаем исходный файл
input_file = '/Users/dmitrijsepelev/Sites/subspark/langs/ru.php'
output_file = '/Users/dmitrijsepelev/Sites/subspark/langs/ru_translated.php'

print("Начинаем перевод файла...")
print(f"Входной файл: {input_file}")
print(f"Выходной файл: {output_file}")

try:
    with open(input_file, 'r', encoding='utf-8') as f:
        lines = f.readlines()

    translated_lines = []
    for i, line in enumerate(lines, 1):
        translated = translate_line(line)
        translated_lines.append(translated)
        if i % 100 == 0:
            print(f"Обработано строк: {i}/{len(lines)}")

    with open(output_file, 'w', encoding='utf-8') as f:
        f.writelines(translated_lines)

    print(f"\n✓ Перевод завершен!")
    print(f"✓ Переведено строк: {len(lines)}")
    print(f"✓ Результат сохранен в: {output_file}")
    print("\nПроверьте результат и замените исходный файл, если всё в порядке:")
    print(f"mv {output_file} {input_file}")

except Exception as e:
    print(f"✗ Ошибка: {e}")
