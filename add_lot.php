<?php
date_default_timezone_get("Europe/Moskow");
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
$dt_diff = date_diff($dt_now, $dt_future);
$dt_lost = get_lost_time($dt_diff);

$lists_of_cat = sql_get_categories($con);


$req_fields = ['lot-name', 'lot-rate', 'category', 'lot-date', 'lot-step', 'description'];
$image_types = ['image/jpeg', '	image/svg+xml', 'image/x-icon', 'image/bmp'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'lot-name' => function($value) {
            return validateLength($value, 4, 100);
        },
        'lot-rate' => function($value) {
            return validateLength($value, 1, 7);
        },
        'lot-step' => function($value) {
            return validateLength($value, 1, 5);
        },
        'category' => function($value) {
            return validateLength($value, 1, 200);
        },
        'lot-date' => function($value) {
            return validateLength($value, 10, 10);
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

    if (count($errors)) {
        var_dump($errors);
        $page_content = include_template('add_lot_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con, 'content_id' => $content_id, 'active_cat' => $active_cat,
        'errors' => $errors, 'form_con_arr' => $form_con_arr]);
        $layout_content = include_template ('add_lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Добавить лот',
        'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);
        exit($layout_content);
        // header("Location: add.php?content_id=1");
    } else {
            $lot_name = $form_con_arr['lot-name'];
            $lot_step = $form_con_arr['lot-step'];
            $lot_img = 'uploads/' . $form_con_arr['path'];
            $lot_rate = $form_con_arr['lot-rate'];
            $lot_date = $form_con_arr['lot-date'];
            $lot_description = $form_con_arr['description'];
            $lot_cat_id = $form_con_arr['category'];
            $sql_add_lot = <<<SQL
            INSERT INTO lots
            set user_create_id = 1,
            cat_id = '$lot_cat_id',
            title = '$lot_name',
            img = '$lot_img',
            content = '$lot_description',
            start_price = '$lot_rate',
            dt_end = '$lot_date',
            step_rate = '$lot_step'
SQL;
            $add_post_result = mysqli_query($con, $sql_add_lot);
            if (!$add_post_result) {
                $error = mysqli_error($con);
                exit('Ошибка mySQL: ' . $error);


            } else {
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
