<?php
session_start();
date_default_timezone_get("Europe/Moskow");
$dt_now = date_create('now');

$active_cat = 'nav__item--current';

$con = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
if ($con == false) {
    exit('Ошибка подключения ' . mysqli_connect_error());
};

require ('functions/main_functions.php');
require ('functions/sql_functions.php');



$content_id = $_GET['content_id'] ?? null;
$lot_id = $_GET['lot_id'] ?? null;

$lists_of_cat = sql_get_categories($con);
$lots_view = sql_get_lot($con, $lot_id);


foreach ($lots_view as &$lot) {
    $rate_history = sql_get_rates_history($con, $lot['id']);
    $lot['history'] = $rate_history;
    $dt_future = (isset($lot['dt_end']) ? date_create($lot['dt_end']) : null );
    $lost_time_trade = get_lost_time($dt_now, $dt_future);
    $lot['lost_time'] = $lost_time_trade;
    $time_finisher = timer_finisher($dt_now, $dt_future);
    $lot['timer'] = $time_finisher;
    foreach ($lot['history'] as &$history) {
        $dt_cr_rate = ($history['dt_create'] ? date_create($history['dt_create']) : null);
        $time_ago = get_history_time_ago($dt_now, $dt_cr_rate);
        $history['time'] = $time_ago;
    };
    $lots_and_rates = sql_get_rates($con, $lot['id']);
    $rates_amount = count($lots_and_rates);
    $rates_result = get_rates_amount($rates_amount);
    $current_price = sql_get_current_price($con, $lot['id']);
    $lot['price'] = $current_price;
    $cur_price = (isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : $lot['start_price']);
    $min_price = calc_min_rate($cur_price, $lot['step_rate']);
    $lot['min_price'] = $min_price;
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $req_fields = ['rate'];
    $rules = [
        'rate' => function($value, $con, $lot_id) {
            return validate_rate($value, $con, $lot_id);
        }
    ];

    $form_con_arr = filter_input_array(INPUT_POST, ['rate' => FILTER_DEFAULT], true);

    foreach ($form_con_arr as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value, $con, $lot_id);
        }

        if (in_array($key, $req_fields) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    };
    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template ('lot_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con,
        'content_id' => $content_id, 'active_cat' => $active_cat, 'lot_id' => $lot_id, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result,
        'errors' => $errors]);
        $layout_content = include_template ('lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Лот',
        'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);
        exit($layout_content);

    } else {
        $lot_rate = sql_existence_rates($con, $lot_id, $_SESSION['user']['id']);
        $rate_id = $lot_rate[0]['rate_id'] ?? null;
        $user_id = $_SESSION['user']['id'];
        $rate = $form['rate'];
        $format_date_now = date_format($dt_now, 'Y-m-d H:i:s');
        if ($lot_rate) {
            $update = <<<SQL
            UPDATE rates
            SET dt_create = "$format_date_now",
            user_id = "$user_id",
            lot_id = "$lot_id",
            rate_price = "$rate"
            WHERE ID = "$rate_id"
SQL;
            $res = mysqli_query($con, $update);
            if(!$res) {
                $error = mysqli_error($con);
                exit('Ошибка mySQL: ' . $error);
            }
        } else {
            $sql = 'INSERT INTO rates (dt_create, user_id, lot_id, rate_price) VALUES (?,?,?,?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$format_date_now, $user_id, $lot_id, $form['rate']]);
            $res = mysqli_stmt_execute($stmt);
        }

    };
    if ($res && empty($errors)) {
        header("Location: lot.php?lot_id=" . $lot_id);
        exit();
    };
};


$page_content = include_template ('lot_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con,
    'content_id' => $content_id, 'active_cat' => $active_cat, 'lot_id' => $lot_id, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result]);

$layout_content = include_template ('lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Лот',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);

?>
