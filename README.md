# Навык для Алисы - DeepSeek Chat

**Алиса DeepSeek** — навык для голосового ассистента Яндекс.Алиса, который интегрируется с DeepSeek Chat, обеспечивая интеллектуальные ответы на ваши вопросы.

## Установка

### Требования

- **PHP 7.4** или выше
- **Composer** для управления зависимостями
- **Расширение cURL** для PHP

### Шаги по установке

1. **Клонируйте репозиторий:**

    ```bash
    git clone https://github.com/zabarov/alice-deepseek.git .
    ```

2. **Установите зависимости с помощью Composer:**

    ```bash
    composer install
    ```

3. **Настройте файл окружения:**

    - Создайте файл `.env` на основе `.env.example`:

        ```bash
        cp .env.example .env
        ```

    - Откройте `.env` и добавьте ваш DeepSeek API ключ:

        ```
        DEEPSEEK_API_KEY=your_deepseek_api_key
        ```

4. **Настройте вебхук в навыке Алисы:**

    - Укажите публичный URL вашего сервера (например, `https://your-domain.com/index.php`) в настройках вебхука вашего навыка в Яндекс.Алисе.

## Ссылки

- DeepSeek API: https://api.deepseek.com
- Документация по API Алисы: https://yandex.ru/dev/dialogs/alice/doc/
- Руководство по разработке навыков для Алисы: https://yandex.ru/dev/dialogs/alice/doc/quickstart-about.html

При создании использовались данные с проекта https://github.com/peleccom/chat_gpt_yandex_alice