<?php
$user_name = 'Кирилл'; // укажите здесь ваше имя

$con = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
if ($con == false) {
    exit('Ошибка подключения ' . mysqli_connect_error());
};

require ('functions/main_functions.php');
require ('functions/sql_functions.php');

$lists_of_cat = sql_get_categories($con);


$page_content = include_template ('main.php', ['lists_of_cat' => $lists_of_cat]);
$layout_content = include_template ('layout.php',['main_content' => $page_content, 'title' => 'Yeticave: главная', 'lists_of_cat' => $lists_of_cat]);
print ($layout_content);

?>
