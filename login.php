<?php
session_start();
$dt_now = date_create('now');
$active_cat = 'nav__item--current';

require ('functions/main_functions.php');
require ('functions/sql_functions.php');

$config = include ('config.php');
$con = sql_get_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database_name']);

$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : null;
$content_id = sql_isset_content_id($con, $content_id);
if ($content_id == 'error') {
    http_response_code(404);
    die('Страница не найдена');
};


$lists_of_cat = sql_get_categories($con);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    };

    $email = mysqli_real_escape_string($con, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['pass'])) {


            $_SESSION['user'] = $user;
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template ('login_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
            'content_id' => $content_id, 'active_cat' => $active_cat, 'errors' => $errors]);
    }
    else {
        header("Location: /index.php");
        exit();
    }
}
else {
    $page_content = include_template ('login_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
        'content_id' => $content_id, 'active_cat' => $active_cat]);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
};



$layout_content = include_template ('login_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Авторизация',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);
