<?php
session_start();
date_default_timezone_get("Europe/Moskow");
$dt_now = date_create('now');


require ('functions/main_functions.php');
require ('functions/sql_functions.php');
$con = sql_get_connect('127.0.0.1', 'root', '', 'yeticave');
//блок get_winner.ph закомментирован чтобы обеспечить быстродействие главной страницы, чтобы отправить почтовую рассылку победителям
// нужно раскомментировать блок подключения этого сценария.
//require ('templates/get_winner.php');



$content_id = $_GET['content_id'] ?? null;


$lists_of_cat = sql_get_categories($con);
$lots_view = sql_get_lots_view($con, $content_id);

foreach ($lots_view as &$lot) {
    $dt_future = (isset($lot['dt_end']) ? date_create($lot['dt_end']) : null );
    $lost_time_trade = get_lost_time($dt_now, $dt_future);
    $lot['lost_time'] = $lost_time_trade;
    $time_finisher = timer_finisher($dt_now, $dt_future);
    $lot['timer'] = $time_finisher;
};

$page_content = include_template ('main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view]);
$layout_content = include_template ('layout.php',['main_content' => $page_content, 'title' => 'Yeticave: главная', 'lists_of_cat' => $lists_of_cat]);
print ($layout_content);

