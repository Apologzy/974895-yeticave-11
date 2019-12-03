
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="nav__item nav__item--current">
            <a href="pages/all-lots.html"><?= $category['cat_name']; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«Доски и лыжи»</span></h2>
        <ul class="lots__list">
            <?php foreach ($lots_view as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="../<?=$lot['img']; ?>" width="350" height="260" alt="<?=$lot['title']; ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$lot['cat_name']; ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.html"><?=$lot['title']; ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <?php
                            $lots_and_rates = sql_get_rates($con, $lot['id']);
                            $rates_amount = count($lots_and_rates);
                            $rates_result = get_rates_amount($rates_amount);
                            ?>
                            <span class="lot__amount"><?=$rates_amount ? $rates_result : 'Текущая цена'  ?></span>
                            <span class="lot__cost"><?=$lot['start_price']; ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer">
                            <?=$lot['dt_end']; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
</div>
