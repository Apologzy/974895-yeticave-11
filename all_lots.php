<?php
session_start();
$user_name = 'Кирилл'; // укажите здесь ваше имя
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
$page_number = $_GET['pages'] ?? 1;
$forward_slide = $page_number + 1;
$back_slide = $page_number - 1;

$lists_of_cat = sql_get_categories($con);
$total_lots = sql_get_total_count_lots($con, $content_id);
$total_pages = get_total_pages($total_lots);
$offset_and_limits = get_offset_and_limits(9,$total_pages);
$lots_view = sql_get_lots_for_curr_pages($con, $content_id, $page_number, $offset_and_limits);


foreach ($lots_view as &$lot) {
    $dt_future = (isset($lot['dt_end']) ? date_create($lot['dt_end']) : null );
    $lost_time_trade = get_lost_time($dt_now, $dt_future);
    $lot['lost_time'] = $lost_time_trade;
    $time_finisher = timer_finisher($dt_now, $dt_future);
    $lot['timer'] = $time_finisher;
    $lots_and_rates = sql_get_rates($con, $lot['id']);
    $rates_amount = count($lots_and_rates);
    $rates_result = get_rates_amount($rates_amount);
    $current_price = sql_get_rate_price_all_lots($con, $lot['id']);
    $lot['price'] = $current_price;

};


$page_content = include_template ('all_lots_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con,
'content_id' => $content_id, 'active_cat' => $active_cat, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result,
'total_pages' => $total_pages, 'forward_slide' => $forward_slide, 'back_slide' => $back_slide, 'page_number' => $page_number]);

$layout_content = include_template ('all_lots_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: all lots',
'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);

?>
