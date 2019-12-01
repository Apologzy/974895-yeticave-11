<?php
$user_name = 'Кирилл'; // укажите здесь ваше имя

require ('functions/main_functions.php');

$page_content = include_template ('main.php', []);
$layout_content = include_template ('layout.php',['main_content' => $page_content, 'title' => 'Yeticave: главная']);
print ($layout_content);

?>
