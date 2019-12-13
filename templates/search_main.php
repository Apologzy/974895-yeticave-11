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
        <?php if (isset($found_lots)) : ?>
        <h2>Результаты поиска по запросу «<span><?=$search ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($found_lots as $lot): ?>
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
                                <span class="lot__cost"><?= $lot['price']['rate_price'] ? $lot['price']['rate_price'] : $lot['start_price'] ?><b class="rub">р</b></span>

                            </div>
                            <div class="lot__timer timer <?= $lot['timer']; ?>">
                                <?= $lot['lost_time']; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h2>Результаты поиска по запросу «<span><?=$search ?? '' ?></span>» <?= $errors['q'] ?? 'не дали результата' ?></h2>
        <?php endif; ?>
    </section>

    <?php if (isset($page_number)): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="/search.php?search=<?=$search ?>&find=Найти&pages=<?= $back_slide > 1 ? $back_slide : 1; ?>">Назад</a></li>
        <?php for($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="pagination-item"><a href="/search.php?search=<?=$search ?>&find=Найти&pages=<?= $i; ?>"><?=$i; ?></a></li>
        <?php endfor; ?>
        <li class="pagination-item pagination-item-next"><a href="/search.php?search=<?=$search ?>&find=Найти&pages=<?= $forward_slide <= $total_pages ? $forward_slide : $page_number; ?>">Вперед</a></li>
    </ul>
    <?php endif; ?>
</div>
