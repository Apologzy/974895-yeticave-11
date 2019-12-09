<?php
session_start();
$dt_now = date_create('now');
$dt_future = date_create('2019-12-7');
$active_cat = 'nav__item--current';
$con = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
if ($con == false) {
    exit('Ошибка подключения ' . mysqli_connect_error());
};

require ('functions/main_functions.php');
require ('functions/sql_functions.php');

$content_id = $_GET['content_id'] ?? null;

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
            $sql = 'INSERT INTO users (dt_create, email, login, pass, contacts) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $password, $form['contacts']]);
            $res = mysqli_stmt_execute($stmt);
        };

        if ($res && empty($errors)) {
            header("Location: /login.php");
            exit();
        };

    }

};



$page_content = include_template ('sing_up_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
    'content_id' => $content_id, 'active_cat' => $active_cat, 'errors' => $errors]);

$layout_content = include_template ('sing_up_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Регистрация',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);


?>
