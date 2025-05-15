<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;

// Загрузка переменных окружения
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Инициализация клиента для LM Studio API
$client = new Client([
    'base_uri' => 'http://195.122.229.112:1234/v1/', // Адрес вашего LM Studio API
    'timeout'  => 60.0,  // Увеличьте таймаут для локальных запросов
]);

// Хранение состояния пользователей
session_start();
if (!isset($_SESSION['users_state'])) {
    $_SESSION['users_state'] = [];
}

// Функция для очистки приветствия Алисы
function cleanRequest($request) {
    $cutWords = ['Алиса', 'алиса'];
    foreach ($cutWords as $word) {
        if (mb_stripos($request, $word) === 0) {
            $request = mb_substr($request, mb_strlen($word));
        }
    }
    return trim($request);
}

// Функция для взаимодействия с LM Studio API
function askLLM($message, $messages, $client) {
    // Подготовка массива сообщений в правильном формате
    $formattedMessages = [
        ['role' => 'system', 'content' => 'Ты дружелюбный ассистент. Отвечай кратко и по делу.']
    ];

    // Конвертируем предыдущие сообщения из строк в объекты
    foreach ($messages as $msg) {
        $formattedMessages[] = ['role' => 'user', 'content' => $msg];
    }

    // Добавляем текущее сообщение
    $formattedMessages[] = ['role' => 'user', 'content' => $message];

    try {
        $response = $client->post('chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'meta-llama-3.1-8b-instruct-128k',
                'messages' => $formattedMessages, // Используем правильно форматированный массив
                'top_p' => 0.95,
                'top_k' => 40,
                'min_p' => 0.05,
                'frequency_penalty' => 1.1,
                'temperature' => 0.8,
                'max_tokens' => 200,
                'stream' => false
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return trim($body['choices'][0]['message']['content']);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return 'Не удалось получить ответ от сервиса. Ошибка: ' . $e->getMessage();
    }
}
// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $response = [
        'session' => $input['session'],
        'version' => $input['version'],
        'response' => [
            'end_session' => false
        ]
    ];

    $sessionId = $input['session']['session_id'];
    if (!isset($_SESSION['users_state'][$sessionId])) {
        $_SESSION['users_state'][$sessionId] = [
            'messages' => []
        ];
    }

    $userState = &$_SESSION['users_state'][$sessionId];

    if (!empty($input['request']['original_utterance'])) {
        $userMessage = cleanRequest($input['request']['original_utterance']);
        $userState['messages'][] = $userMessage;

        $botReply = askLLM($userMessage, $userState['messages'], $client);
        $response['response']['text'] = $botReply;
        $response['response']['tts'] = $botReply . '<speaker audio="alice-sounds-things-door-2.opus">';
    } else {
        $response['response']['text'] = 'Я умный чат-бот. Спроси что-нибудь.';
        $response['response']['tts'] = 'Я умный чат-бот. Спроси что-нибудь.';
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Метод не поддерживается.";
}
