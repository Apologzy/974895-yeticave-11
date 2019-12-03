<?php
$user_name = 'Кирилл'; // укажите здесь ваше имя
date_default_timezone_get("Europe/Moskow");
$dt_now = date_create('now');
$dt_future = date_create('2019-12-7');

$con = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
if ($con == false) {
    exit('Ошибка подключения ' . mysqli_connect_error());
};

require ('functions/main_functions.php');
require ('functions/sql_functions.php');







$dt_diff = date_diff($dt_now, $dt_future);
$dt_lost = get_lost_time($dt_diff);

$lists_of_cat = sql_get_categories($con);
$lots_view = sql_get_lots_view($con);



$page_content = include_template ('all_lots_main.php', ['lists_of_cat' => $lists_of_cat, 'lots_view' => $lots_view, 'con' => $con]);
$layout_content = include_template ('all_lots_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: all lots', 'lists_of_cat' => $lists_of_cat]);
print ($layout_content);

?>
