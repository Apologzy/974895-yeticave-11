
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
            <a href="/all_lots.php?content_id=<?= $category['id']; ?>"><?= $category['cat_name']; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= $categories_name['cat_name'] ?>»</span></h2>
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
                            <span class="lot__amount"><?=isset($lot['rate_count']) ? $lot['rate_count'] : 'Стартовая цена' ?></span>
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
    </section>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="/all_lots.php?content_id=<?= $content_id; ?>&pages=<?= $back_slide > 1 ? $back_slide : 1; ?>">Назад</a></li>
        <?php for($i = 1; $i <= $total_pages; $i++) : ?>
        <li class="pagination-item"><a href="/all_lots.php?content_id=<?= $content_id; ?>&pages=<?= $i; ?>"><?=$i; ?></a></li>
        <?php endfor; ?>
        <li class="pagination-item pagination-item-next"><a href="/all_lots.php?content_id=<?= $content_id; ?>&pages=<?= $forward_slide <= $total_pages ? $forward_slide : $page_number; ?>">Вперед</a></li>
    </ul>
</div>
