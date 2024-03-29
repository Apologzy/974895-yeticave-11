<?php
session_start();
$dt_now = date_create('now');
$dt_future = date_create('2019-12-7');
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
    $errors = [];
    $req_fields = ['email', 'password', 'name', 'contacts'];
    $rules = [
        'email' => function($value) {
            return validateEmail($value, 11, 40);
        },
        'password' => function($value) {
            return validateLength($value, 5, 15);
        },
        'name' => function($value) {
            return validateLength($value, 3, 15);
        },
        'contacts' => function($value) {
            return validateLength($value, 11, 200);
        }
    ];

    $form_con_arr = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT,
        'contacts' => FILTER_DEFAULT], true);

    foreach ($form_con_arr as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $req_fields) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    };
    $user_name = strip_tags($form['name']);
    if ($user_name=='') {
        $errors['name'] = 'Введите корректное имя пользователя';
    };
    $user_password = strip_tags($form['password']);
    if ($user_password=='') {
        $errors['password'] = 'Введите корректный пароль';
    };
    $errors = array_filter($errors);

    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res_email = mysqli_query($con, $sql);
        $login = mysqli_real_escape_string($con, $form['name']);
        $sql = "SELECT id FROM users WHERE login = '$login'";
        $res_login = mysqli_query($con, $sql);
        if (mysqli_num_rows($res_email) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } elseif (mysqli_num_rows($res_login) > 0) {
            $errors['name'] = 'Пользователь с этим именем уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
            $user_contacts = htmlspecialchars($form['contacts']);
            $sql = 'INSERT INTO users (dt_create, email, login, pass, contacts) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $user_contacts]);
            $res = mysqli_stmt_execute($stmt);
        };

        if ($res && empty($errors)) {
            header("Location: /login.php");
            exit();
        };

    }

};

if (isset($errors)) {
    $page_content = include_template ('sing_up_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
    'content_id' => $content_id, 'active_cat' => $active_cat, 'errors' => $errors]);
} else {
    $page_content = include_template ('sing_up_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
        'content_id' => $content_id, 'active_cat' => $active_cat]);
};



$layout_content = include_template ('sing_up_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Регистрация',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);


?>
