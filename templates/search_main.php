<nav class="nav">
    <ul class="nav__list container">
        <?php if (isset($lists_of_cat)): ?>
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
            <a href="/all_lots.php?content_id=<?= isset($category['id']) ? $category['id'] : null  ?>"><?= isset($category['cat_name']) ? $category['cat_name'] : '' ?></a>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <?php if (isset($found_lots)) : ?>
        <h2>Результаты поиска по запросу «<span><?=isset($search) ? $search : '' ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($found_lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="../<?=isset($lot['img']) ? $lot['img'] : null ?>" width="350" height="260" alt="<?= isset($lot['title']) ? $lot['title'] : '' ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=isset($lot['cat_name']) ? $lot['cat_name'] : '' ?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?lot_id=<?= isset($lot['id']) ? $lot['id'] : null ?>"><?= isset($lot['title']) ? $lot['title'] : '' ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount"><?=isset($lot['rate_count']) ? $lot['rate_count'] : 'Стартовая цена' ?></span>
                                <?php if (isset($lot['price']['rate_price'])): ?>
                                <span class="lot__cost"><?= isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : null ?><b class="rub">р</b></span>
                                <?php else : ?>
                                <span class="lot__cost"><?= isset($lot['start_price']) ? $lot['start_price'] : null ?><b class="rub">р</b></span>
                                <?php endif; ?>
                            </div>
                            <div class="lot__timer timer <?= isset($lot['timer']) ? $lot['timer'] : '' ?>">
                                <?= isset($lot['lost_time']) ? $lot['lost_time'] : null ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h2>Результаты поиска по запросу «<span><?= $search ?? '' ?></span>» <?= $errors['q'] ?? 'не дали результата' ?></h2>
        <?php endif; ?>
    </section>

    <?php if (isset($page_number)): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="/search.php?search=<?=isset($search) ? $search : null ?>&find=Найти&pages=<?= $back_slide > 1 ? $back_slide : 1; ?>">Назад</a></li>
        <?php if ($total_pages) : ?>
        <?php for($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="pagination-item"><a href="/search.php?search=<?=isset($search) ? $search : null ?>&find=Найти&pages=<?= $i; ?>"><?=$i; ?></a></li>
        <?php endfor; ?>
        <?php endif; ?>
        <li class="pagination-item pagination-item-next"><a href="/search.php?search=<?=isset($search) ? $search : null ?>&find=Найти&pages=<?= $forward_slide <= $total_pages ? $forward_slide : $page_number; ?>">Вперед</a></li>
    </ul>
    <?php endif; ?>
</div>
