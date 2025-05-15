markdown
# Навык для Алисы - Локальный LLM через LM Studio

**Алиса + LM Studio** — навык для голосового ассистента Яндекс.Алиса, который использует локальные LLM-модели (Llama 3, Mistral и др.) через LM Studio для приватных и кастомизируемых ответов.

## 🛠 Установка

### Требования

- **LM Studio** ([скачать](https://lmstudio.ai/)) с запущенным локальным сервером
- **PHP 8.0+** (рекомендуется 8.2)
- **Composer** для управления зависимостями
- **Расширения PHP**: cURL, JSON, MBString

### Шаги по установке

1. **Клонируйте репозиторий:**
   ```bash
   git clone https://github.com/zabarov/alice-deepseek.git
   cd alice-deepseek
Установите зависимости:

bash
composer install
Настройте LM Studio:

Запустите LM Studio

Загрузите модель (например, Llama 3 8B или Mistral 7B)

Включите сервер:
Settings → Server → Enable Local Server
(по умолчанию: http://localhost:1234)

Настройте вебхук в Алисе:

Создайте навык на Яндекс.Диалоги

Укажите URL вашего сервера (например, http://ваш-ip:порт/index.php)

⚙️ Конфигурация
Ключевые файлы
index.php — основной скрипт обработки запросов Алисы

.env — настройки подключения (не требуется для LM Studio по умолчанию)

Пример запроса к локальной модели
php
$client->post('chat/completions', [
    'json' => [
        'model' => 'llama-3-8b-instruct',
        'messages' => [
            ['role' => 'system', 'content' => 'Ты полезный ассистент'],
            ['role' => 'user', 'content' => 'Привет!']
        ],
        'temperature' => 0.7
    ]
]);
🌟 Особенности
Полная приватность — все запросы обрабатываются локально

Поддержка моделей:

Llama 3 (8B/70B)

Mistral (7B)

DeepSeek LLM (локальная версия)

Гибкие настройки через LM Studio UI

🔍 Тестирование
Проверьте работу API:

bash
curl http://localhost:1234/v1/chat/completions -H "Content-Type: application/json" -d '{
  "model": "llama-3-8b-instruct",
  "messages": [{"role": "user", "content": "Привет!"}]
}'
Для сброса истории диалога:

http://ваш-сервер/index.php?reset=1
📚 Полезные ссылки
LM Studio Documentation

Документация API Алисы

Список GGUF-моделей


### Ключевые изменения:
1. Заменил DeepSeek API на LM Studio
2. Добавил инструкции по настройке локального сервера
3. Упростил требования (не нужен API-ключ)
4. Добавил список поддерживаемых локальных моделей
5. Обновил примеры запросов под локальные LLM

Файл теперь полностью ориентирован на работу с **локальными моделями** через LM Studio.
