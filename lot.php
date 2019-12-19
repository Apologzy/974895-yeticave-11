<?php
session_start();
date_default_timezone_get("Europe/Moskow");
$dt_now = date_create('now');

$active_cat = 'nav__item--current';

require ('functions/main_functions.php');
require ('functions/sql_functions.php');
$config = include ('config.php');

$con = sql_get_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database_name']);

$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : null;

$lot_id = isset($_GET['lot_id']) ? intval($_GET['lot_id']) : null;
$lot_id = sql_isset_lot_id($con, $lot_id);
if ($lot_id == 'error') {
    http_response_code(404);
    die('Страница не найдена');
};
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
    $lot['rate_count'] = $rates_result;
    $current_price = sql_get_current_price($con, $lot['id']);
    $lot['price'] = $current_price;
    $cur_price = (isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : $lot['start_price']);
    $min_price = calc_min_rate($cur_price, $lot['step_rate'], $rates_amount);
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
        $rate = strip_tags($form['rate']);
        $format_date_now = date_format($dt_now, 'Y-m-d H:i:s');
        if ($lot_rate) {
            $safe_rate = mysqli_real_escape_string($con, $rate);
            $update = <<<SQL
            UPDATE rates
            SET dt_create = "$format_date_now",
            user_id = "$user_id",
            lot_id = "$lot_id",
            rate_price = "$safe_rate"
            WHERE ID = "$rate_id"
SQL;
            $res = mysqli_query($con, $update);
            if(!$res) {
                $error = mysqli_error($con);
                http_response_code(404);
                die('Попытка SQL инъекции');
            }
        } else {
            $sql = 'INSERT INTO rates (dt_create, user_id, lot_id, rate_price) VALUES (?,?,?,?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$format_date_now, $user_id, $lot_id, $rate]);
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
