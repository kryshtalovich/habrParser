<?php

include('vendor/autoload.php');
include('TelegramBot.php');
include('Pars.php');

$telegramApi = new TelegramBot();
$updates = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
$parsShow = new Pars();

$chat_id = $updates['message']['chat']['id'];
$first_name = $updates['message']['from']['first_name'];
$message = $updates['message']['text'];

function between($val, $min, $max)
{
    if ($val >= $min && $val <= $max) return true;
    return false;
}

function sendTop($count)
{
    $telegramApi = new TelegramBot();
    $updates = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
    $parsShow = new Pars();
    $chat_id = $updates['message']['chat']['id'];

    $telegramApi->sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Лови свою подборку с хабра за сутки 😉']);
    $parsShow = $parsShow->getPars();
    $i = 0;
    foreach ($parsShow->find('.posts_list .post_preview') as $article) {
        $article = pq($article);
        $title = $article->find('.post__title')->html();
        $author = $article->find('.user-info__nickname')->text();
        $img = $article->find('.post__body_crop .post__text-html')->attr('src');
        $href = $article->find('.post__body_crop .post__habracut-btn')->attr('href');
        $text = $article->find('.post__body_crop .post__text-html')->html();

        $i++;
        if ($i > $count) {
            break;
        }
        $telegramApi->sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "🔥 $i место в топе\n
📝 Автор: $author\n
🌐 Ссылка на статью: $href"]);
    }
}


switch ($message) {
    case '/start':
        $telegramApi->sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Привет, $first_name!
Введи /help и узнаешь, что я умею делать."]);
        break;
    case '/help':
        $telegramApi->sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Смотри, $first_name, я умею выводить самые популярные статьи с хабра.
Введи через нижнее подчеркивание /top и количество статей от 1 до 20.
Например, сообщение /top_5 выведет 5 статей.
В быстром доступе уже есть популярные команды.
Возможно, появится и новый функционал, но пока что на этом все.
Если у тебя есть предложения по улучшению - пиши напрямую @Westrow"]);
        break;
    default:
        $command = explode('_', $message);
        if ($command[0] == '/top' && between($command[1], 1, 20)) {
            sendTop($command[1]);
        } else {
            $telegramApi->sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Друг, я тебя не понимаю(
Введи /help и я расскажу, что умею.']);
        }
        break;
}