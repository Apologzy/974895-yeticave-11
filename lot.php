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
$lot_id = $_GET['lot_id'] ?? null;

$dt_diff = date_diff($dt_now, $dt_future);
$dt_lost = get_lost_time($dt_diff);

$lists_of_cat = sql_get_categories($con);
$lots_view = sql_get_lot($con, $lot_id);


foreach ($lots_view as $lot) {
    $rate_history = sql_get_rates_history($con, $lot['id']);
    $lots_and_rates = sql_get_rates($con, $lot['id']);
    $rates_amount = count($lots_and_rates);
    $rates_result = get_rates_amount($rates_amount);
};


$page_content = include_template ('lot_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con,
    'content_id' => $content_id, 'active_cat' => $active_cat, 'lot_id' => $lot_id, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result,
    'rate_history' => $rate_history]);

$layout_content = include_template ('lot_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: all lots',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);

?>
