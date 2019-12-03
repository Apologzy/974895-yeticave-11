<?php

function sql_get_categories($connect) {
    mysqli_set_charset($connect, 'utf8');
    $categories = <<<SQL
    SELECT * FROM categories 
SQL;
    $sql_cut_result = mysqli_query($connect, $categories);
    if(!$sql_cut_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
       return mysqli_fetch_all($sql_cut_result, MYSQLI_ASSOC);
    }
};

function sql_get_lots($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT * FROM lots
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_cut_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


function sql_get_lots_view($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


function sql_get_lots_rate($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT r.user_id, r.lot_id, r.rate_price, l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    JOIN rates r ON l.id = r.lot_id
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};

function sql_get_rates($connect, $id) {
    mysqli_set_charset($connect, 'utf8');
    $were_con = $id;
    $lots = <<<SQL
    SELECT l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.rate_price  FROM lots l 
    JOIN rates r ON l.id = r.lot_id
    WHERE r.lot_id = '$were_con'
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};



?>
