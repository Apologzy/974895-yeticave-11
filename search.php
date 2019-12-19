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
$content_id = sql_isset_content_id($con, $content_id);
if ($content_id == 'error') {
    http_response_code(404);
    die('Страница не найдена');
};

$lists_of_cat = sql_get_categories($con);


$search = htmlspecialchars($_GET['search']) ?? '';
$req_fields = ['search'];
$errors = [];
$rules = [
    'search' => function($value) {
        return validateLength($value, 3, 100);
    }
];

if ($search) {
    $form_con_arr = filter_input_array(INPUT_GET, ['search' => FILTER_DEFAULT], true);

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
};

if (count($errors)) {
    $page_content = include_template('search_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con, 'content_id' => $content_id, 'active_cat' => $active_cat,
        'errors' => $errors, 'form_con_arr' => $form_con_arr, 'search' => $search]);
    $layout_content = include_template ('search_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Поиск',
        'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);
    exit($layout_content);
} else {
   if ($search) {

       $sql = "SELECT * FROM lots "
           . "WHERE MATCH(title, content) AGAINST(?)";

       $stmt = db_get_prepare_stmt($con, $sql, [$search]);
       $gg = mysqli_stmt_execute($stmt);
       $result = mysqli_stmt_get_result($stmt);
       $page_number =isset($_GET['pages']) ? intval($_GET['pages']) : 1;
       $forward_slide = $page_number + 1;
       $back_slide = $page_number - 1;

       $lots_search_res = mysqli_fetch_all($result, MYSQLI_ASSOC);

       $total_pages = get_total_pages($lots_search_res);
       $offset_and_limits = get_offset_and_limits(9,$total_pages);

       $found_lots = sql_get_found_lots_from_search($con, $search, $page_number, $offset_and_limits);
       if ($found_lots == 'error') {
           http_response_code(404);
           die('Страница не найдена');
       };
       if (!$lots_search_res) {
           $errors['q'] = 'не дали результатов';
           $page_content = include_template('search_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con, 'content_id' => $content_id, 'active_cat' => $active_cat,
               'errors' => $errors, 'form_con_arr' => $form_con_arr, 'search' => $search]);
           $layout_content = include_template ('search_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Поиск',
               'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);
           exit($layout_content);
       };

       foreach ($found_lots as &$lot) {
           $dt_future = (isset($lot['dt_end']) ? date_create($lot['dt_end']) : null );
           $lost_time_trade = get_lost_time($dt_now, $dt_future);
           $lot['lost_time'] = $lost_time_trade;
           $time_finisher = timer_finisher($dt_now, $dt_future);
           $lot['timer'] = $time_finisher;
           $lots_and_rates = sql_get_rates($con, $lot['id']);
           $rates_amount = count($lots_and_rates);
           $rates_result = get_rates_amount($rates_amount);
           $lot['rate_count'] = $rates_result;
           $current_price = sql_get_rate_price_all_lots($con, $lot['id']);
           $lot['price'] = $current_price;
       };
       $page_content = include_template ('search_main.php', ['lists_of_cat' => $lists_of_cat, 'found_lots' => $found_lots, 'con' => $con,
       'content_id' => $content_id, 'active_cat' => $active_cat, 'rates_amount' => $rates_amount, 'rates_result' => $rates_result,
       'total_pages' => $total_pages, 'forward_slide' => $forward_slide, 'back_slide' => $back_slide, 'page_number' => $page_number,
       'search' => $search]);

       $layout_content = include_template ('search_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Поиск',
       'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

      print ($layout_content);
       exit();

   } else {
        $page_content = include_template ('search_main.php', ['lists_of_cat' => $lists_of_cat, 'con' => $con,
            'content_id' => $content_id, 'active_cat' => $active_cat]);

        $layout_content = include_template ('search_layout.php',['main_content' => $page_content, 'title' => 'Yeticave: Поиск',
            'lists_of_cat' => $lists_of_cat, 'content_id' => $content_id, 'active_cat' => $active_cat ]);

        print ($layout_content);

    };

};






