
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
            <a href="/all_lots.php?content_id=<?= $category['id']; ?>"><?= $category['cat_name']; ?></a>
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
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?lot_id=<?= $lot['id']; ?>"><?=$lot['title']; ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?=$rates_amount ? $rates_result : 'Текущая цена'  ?></span>
                             <?php var_dump($curr_pr_arr); ?>
                            <?php foreach ($curr_pr_arr as $price) : ?>
                            <span class="lot__cost"><?= $price['rate_price'] ? $price['rate_price'] : $lot['start_price'] ?><b class="rub">р</b></span>
                            <?php endforeach; ?>
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
