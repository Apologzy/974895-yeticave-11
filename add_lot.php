<?php
session_start();
date_default_timezone_get("Europe/Moskow");
$dt_now = date_create('now');
$active_cat = 'nav__item--current';

require ('functions/main_functions.php');
require ('functions/sql_functions.php');

$config = include ('config.php');
$con = sql_get_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database_name']);

$content_id = $_GET['content_id'] ?? null;

$lists_of_cat = sql_get_categories($con);


$req_fields = ['lot-name', 'lot-rate', 'category', 'lot-date', 'lot-step', 'description'];
$image_types = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/x-icon', 'image/bmp'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'lot-name' => function($value) {
            return validateLength($value, 4, 100);
        },
        'lot-rate' => function($value) {
            return validate_lot_rate($value, 1, 7);
        },
        'lot-step' => function($value) {
            return validate_step_rate($value, 1, 4);
        },
        'category' => function($value) {
            return validateLength($value, 1, 200);
        },
        'lot-date' => function($value) {
            return validate_date($value, 10, 10);
        },
        'description' => function($value) {
            return validateLength($value, 10, 3000);
        }
    ];

    $form_con_arr = filter_input_array(INPUT_POST, ['lot-name' => FILTER_DEFAULT, 'lot-rate' => FILTER_DEFAULT, 'lot-step' => FILTER_DEFAULT,
        'lot-date' => FILTER_DEFAULT, 'description' => FILTER_DEFAULT, 'category' => FILTER_DEFAULT], true);

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


    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];
        $formats = explode('.', $path);
        if (count($formats) >= 2) {
            $format = end($formats);
            $format = mb_strtolower($format);
        };
        $filename = uniqid() . '.' . $format;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if (in_array($file_type, $image_types)) {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $form_con_arr['path'] = $filename;
        }  else {
            $errors['file'] = 'Загрузите картинку в нужном формате';
        };



    } else {
        $errors['file'] = 'Фаил не загружен';
    };
    $non_tags_fields = xss_filter ($form_con_arr['lot-name'], $form_con_arr['lot-step'], $form_con_arr['lot-rate'], $form_con_arr['lot-date'], $form_con_arr['description'], $form_con_arr['category']);
    foreach ($non_tags_fields as $key => $field) {
        if ($field == '' || null) {
            $errors[$key] = "Поле $key надо заполнить";
        };
    };
    if (count($errors)) {
        $page_content = include_template('add_lot_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con, 'content_id' => $content_id, 'active_cat' => $active_cat,
        'errors' => $errors, 'form_con_arr' => $form_con_arr]);
        $layout_content = include_template ('add_lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Добавить лот',
        'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);
        exit($layout_content);
    } else {
            $lot_name = $non_tags_fields['lot-name'];
            $lot_step = $non_tags_fields['lot-step'];
            $lot_img = 'uploads/' . $form_con_arr['path'];
            $lot_rate = $non_tags_fields['lot-rate'];
            $lot_date = $non_tags_fields['lot-date'];
            $lot_description = $non_tags_fields['description'];
            $lot_cat_id = $non_tags_fields['category'];
            $user_id = $_SESSION['user']['id'];

            $sql = 'INSERT INTO lots (user_create_id, cat_id, title, img, content, start_price, dt_end, step_rate) VALUES (?,?,?,?,?,?,?,?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $lot_cat_id, $lot_name, $lot_img, $lot_description, $lot_rate, $lot_date, $lot_step]);
            $res = mysqli_stmt_execute($stmt);
            if ($res && empty($errors)) {
                 $add_lot_id = mysqli_insert_id($con);
                header("Location: lot.php?lot_id=" . $add_lot_id);
            };

    };
};



$page_content = include_template ('add_lot_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
'content_id' => $content_id, 'active_cat' => $active_cat]);

$layout_content = include_template ('add_lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Добавить Лот',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);

?>
