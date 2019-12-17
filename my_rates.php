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

$winner = sql_lot_winner($con, $dt_now);

$all_rates_of_curr_user = sql_get_all_rates_of_curr_user($con,$_SESSION['user']['id']);

$content_id = $_GET['content_id'] ?? null;
$page_number = $_GET['pages'] ?? 1;
$forward_slide = $page_number + 1;
$back_slide = $page_number - 1;

$lists_of_cat = sql_get_categories($con);
$all_rates_of_curr_user = sql_get_all_rates_of_curr_user($con,$_SESSION['user']['id']);
$total_pages = get_total_pages($all_rates_of_curr_user);
$offset_and_limits = get_offset_and_limits(9,$total_pages);

$lots_view = sql_get_lots_and_rates_for_curr_user($con, $_SESSION['user']['id'], $page_number, $offset_and_limits);

foreach ($lots_view as &$lot) {
    $dt_cr_rate = ($lot['rate_dt_create'] ? date_create($lot['rate_dt_create']) : null);
    $time_ago = get_history_time_ago($dt_now, $dt_cr_rate);
    $lot['time'] = $time_ago;
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
    $user_contact = get_user_contacts($con, $lot['user_create_id']);
    $lot['user_contact'] = $user_contact;
};


$page_content = include_template ('my_rates_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con,
    'content_id' => $content_id, 'active_cat' => $active_cat, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result,
    'total_pages' => $total_pages, 'forward_slide' => $forward_slide, 'back_slide' => $back_slide, 'page_number' => $page_number]);

$layout_content = include_template ('my_rates_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Мои ставки',
    'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

print ($layout_content);
