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


function sql_get_lots_view($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE c.id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
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

function sql_get_lot ($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE l.id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
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

function sql_get_rates_history($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $rate = <<<SQL
    SELECT  u.id, u.login, r.user_id, r.lot_id, r.dt_create, r.rate_price FROM users u
    JOIN  rates r ON u.id = r.user_id
    $whereCondition
SQL;
    $sql_rate_result = mysqli_query($connect, $rate);
    if(!$sql_rate_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_rate_result, MYSQLI_ASSOC);
    }
};

function sql_get_current_price ($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $rate = <<<SQL
    SELECT  u.id, u.login, r.user_id, r.lot_id, r.dt_create, r.rate_price FROM users u
    JOIN  rates r ON u.id = r.user_id
    $whereCondition
    ORDER BY r.rate_price DESC LIMIT 1
SQL;
    $sql_rate_result = mysqli_query($connect, $rate);
    if(!$sql_rate_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_rate_result, MYSQLI_ASSOC);
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

function sql_get_rate_price_all_lots($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.rate_price  FROM lots l 
    JOIN rates r ON l.id = r.lot_id
    $whereCondition
    ORDER BY r.rate_price DESC LIMIT 1
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_lots_result);
    }
};


?>
