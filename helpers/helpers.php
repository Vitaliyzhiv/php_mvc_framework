<?php

use PHPFramework\Language;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Returns the application instance
 *
 * @return \PHPFramework\Application
 */
function app(): \PHPFramework\Application
{
    return \PHPFramework\Application::$app;
}


/**
 * Returns the current request instance
 *
 * @return \PHPFramework\Request
 */
function request(): \PHPFramework\Request
{
    return app()->request;
}

function response(): \PHPFramework\Response
{
    return app()->response;
}

// функция helper для более удобного обращения к классу session
function session(): \PHPFramework\Session
{
    return app()->session;
}

// функция helper для более простого обращения к инициализованому в классе Application обьекту класса Cache

function cache(): \PHPFramework\Cache
{
    return app()->cache;
}

// записываем в атрибут view пустую строку так как мы хотим одновременно иметь возможность подключать вид  
// и так же обращаться к экзмемпляру класса View, если вид не был передан
function view($view = '', $data = [], $layout = ''): string|\PHPFramework\View
{

    // если параметр $view передан то возвращаем вид
    if ($view) {
        return app()->view->render($view, $data, $layout);
    }
    // в противном случае возвращаем экземпляр класса View
    return app()->view;
}

// функция helper для вывода ошибок
function abort($error = '', $code = 404)
{
    // обращение к функции хелперу  response
    response()->setResponseCode($code);
    // возвращаем вид ошибки из папки errors
    // так как  мы не хотим видеть шаблон то в параметре layout передаем false
    echo view("errors/{$code}", ['error' => $error], false);
    die;
}

// добавляем функцию base_url
function base_url($path = ''): string
{
    return PATH . $path;
}

function base_href($path = ''): string
{
    // если язык не является базовым пристыковываем код языка к url
    if (app()->get('lang')['base'] != 1) {
        return PATH . '/' . app()->get('lang')['code'] . $path;
    }

    return PATH . $path;
}

// функция хелпер для получения всех параметров маршрута
function get_route_params(): array
{
    return app()->router->route_params;
}

// функция хелпер для получения какого параметра маршрута
function get_route_param($key, $default = ''): string
{
    return app()->router->route_params[$key] ?? $default;
}

// функция для того чтобы проваливаться в двухмерный массив и искать там значения
function array_value_search($arr, $index, $value): int|null|string
{
    foreach ($arr as $k => $v) {
        // проверяем явлеяется ли значение по индексу внутри вложенного массива равно переданому значению
        // например:
        // массив LANG = [
        // 'ru' => ['name' => 'Russian', 'email' => 'Email'],
        // 'en' => ['name' => 'English', 'email' => 'Email']
        // ]
        //  то в $k - у нас запишется  первый елемент массива 'ru' , 'en' и тд
        // а в $v будет массив который является значением первого
        // во вложеном массиве по ключу , который мы передали в индекс проверяем соотвествие
        // ожидаемому значению 
        if ($v[$index] == $value) {
            // возвращаем ключ
            return $k;
        }
    }
    // возврашаем null если ничего не сработало
    return null;
}


// функция хелпер для  возвращения url адресса без языка

function uri_without_lang(): string
{
    // получаем текущий uri (то что идет после базового url);
    $request_uri =  request()->uri;
    // разбиваем uri на массив по символу / 
    // используем лимит 2 так как нас интересует разбивка именно по языку:
    // с строки en/register/test/something, получим:
    // ['en', register/test/something].
    $request_uri = explode('/', $request_uri, 2);
    // проверяем существует ли текущий язык 
    if (array_key_exists($request_uri[0], LANGS)) {
        //  если существует то просто убираем его
        unset($request_uri[0]);
    }
    //  складываем uri обратно без языка
    $request_uri = implode('/', $request_uri);
    // если request_uri не пустой то добавляем / к нему, иначе возвращаем пустую строку  
    return $request_uri ? '/' . $request_uri : '';
}

// функция хелпер для вывода данных по ключу
function _e($key)
{
    echo Language::get($key);
}

// функция хелпер для возвращения данных по ключу
function __($key): string
{
    return Language::get($key);
}

// функция хелпер которая будет подключать алерт партс к странице
function get_alerts(): void
{

    if (!empty($_SESSION['flash'])) {
        // проходимся циклом по ключам внутри $_SESSION['flash']
        foreach ($_SESSION['flash'] as $key => $value) {
            // каждый ключ это имя алерта и значение это текст алерта
            echo view()->renderPartial("incs/alert_{$key}", ["flash_{$key}" =>
            session()->getFlash($key)]);
        }
        // удаляем данные из $_SESSION['flash'] после вывода
        unset($_SESSION['flash']);
    }
}

// функция helper для вывода ошибок из сессии form_errors (source: app/Controllers/UserController.
// php)

function get_errors($fieldname): string
{
    // инициализация пустой переменной для записи ошибок
    $output = '';
    // получаем ошибки из form_errors
    $errors = session()->get('form_errors');
    // проверяем есть ли ошибки
    if (isset($errors[$fieldname])) {
        // если есть то конкатенируем их в $output
        $output .= '<div class="invalid-feedback d-block"><ul class="list-unstyled">';
        // проходимся циклом foreach и записываем ошибки в елементы списка li
        foreach ($errors[$fieldname] as $error) {
            $output .= "<li> $error  </li>";
        }
        $output .= '</ul></div>';
    }

    return $output;
}

// функция helper для получения класса для валидации полей form_errors
// (source: app/Controllers/UserController.php)
function get_validation_class($fieldname): string
{

    $errors = session()->get('form_errors');
    // чтобы не подсвечивалась пустая стартовая зеленая форма если ошибки отсутсвуют то возвращаем ''
    if (empty($errors)) {
        return '';
    }
    return isset($errors[$fieldname]) ? 'is-invalid' : 'is-valid';
}


// функция helper для сохранения данных полей  из сессии form_data (source: app/Controllers/UserController.
// php)
function old($fieldname): string
{

    // возвращаем данные поля если он существует
    return isset(session()->get('form_data')[$fieldname])
        ? h(session()->get('form_data')[$fieldname])
        : '';
}

// функция обертка для htmlspecialchars чтобы использовать более короткое название

function h($str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// функция helper для сокращенного обращения к обьекту db
function db(): \PHPFramework\Database
{
    return app()->db;
}

/**
 * Returns a string containing a hidden HTML form field with the name
 * "csrf_token" and a value of the current CSRF token from the session.
 *
 * This is useful for including in forms that need to be protected from
 * cross-site request forgery (CSRF) attacks.
 *
 * @return string A string containing a hidden HTML form field with the
 *     CSRF token.
 */
function get_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . session()->get('csrf_token') . '" />';
}

/**
 * Returns a string containing a meta HTML tag with the name "csrf-token"
 * and a content attribute set to the current CSRF token from the session.
 *
 * This is useful for including in HTML pages where you need to protect
 * against cross-site request forgery (CSRF) by making the token accessible
 * in JavaScript.
 *
 * @return string A string containing a meta HTML tag with the CSRF token.
 */
function get_csrf_meta(): string
{
    // Retrieve the CSRF token from the session and insert it into a meta tag
    return '<meta name="csrf-token" content="' . session()->get('csrf_token') . '" />';
}

function check_auth(): bool
{
    return false;
}

function send_mail(array $to, string $subject, string $tpl, array $data = [], array $attachments = []): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = MAIL_SETTINGS['debug'];
        $mail->isSMTP();
        $mail->Host       =  MAIL_SETTINGS['host'];
        $mail->SMTPAuth   =  MAIL_SETTINGS['auth'];
        $mail->Username   =  MAIL_SETTINGS['username'];
        $mail->Password   =  MAIL_SETTINGS['password'];
        $mail->SMTPSecure =  MAIL_SETTINGS['secure'];
        $mail->Port       =  MAIL_SETTINGS['port'];

        $mail->setFrom(MAIL_SETTINGS['from_email'], MAIL_SETTINGS['from_name']);
        // проходимся массивом по адрессам $to
        foreach ($to as $email) {
            $mail->addAddress($email);
        }

        // вложения 
        if ($attachments) {
            // перебираем вложения циклом 
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment); //Add attachments
            }
        }

        // Контент
        $mail->isHTML(MAIL_SETTINGS['is_html']);
        $mail->CharSet = MAIL_SETTINGS['charset'];
        $mail->Subject = $subject;
        // подключаем вид с помощью функции хелпера view()
        $mail->Body    = view($tpl, $data, false);


        return $mail->send();
    } catch (Exception $e) {
        error_log("File: {$e->getFile()}, Line: {$e->getLine()}, Message: {$e->getMessage()}\n", 3, ERROR_LOGS);
        return false;
    }
}
