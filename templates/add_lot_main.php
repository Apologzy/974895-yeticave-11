<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
            <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
                <a href="/all_lots.php?content_id=<?= $category['id']; ?>"><?= $category['cat_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

</nav>
<form class="form form--add-lot container <?= isset($errors) ? 'form--invalid' : '' ?>"  method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors['lot-name']) ? 'form__item--invalid' : '' ?> "> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" value="<?= getPostVal('lot-name'); ?>" placeholder="Введите наименование лота">
                <?php if(isset($errors)) : ?>
                <span class="form__error">Введите наименование лота</span>
                <?php endif; ?>
            </div>
            <div class="form__item <?= isset($errors['category']) ? 'form__item--invalid' : '' ?> ">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option></option>
                    <?php foreach ($lists_of_cat as $category): ?>
                    <option value="<?= $category['id']; ?>" <?= $category['id']==($_POST['category'] ?? null) ? 'selected' : ''; ?> ><?= $category['cat_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(isset($errors)) : ?>
                <span class="form__error">Выберети категорию</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form__item <?= isset($errors['description']) ? 'form__item--invalid' : '' ?> form__item--wide">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description"  placeholder="Напишите описание лота"><?= getPostVal('description'); ?></textarea>
            <?php if(isset($errors)) : ?>
            <span class="form__error">Введите описание лота</span>
            <?php endif; ?>
        </div>
        <div class="form__item <?= isset($errors['file']) ? 'form__item--invalid' : '' ?> form__item--file">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <?php if(isset($errors)) : ?>
            <span class="form__error"><?= $errors['file']=='Загрузите картинку в нужном формате' ? $errors['file'] : 'Добавьте изображение' ?></span>
            <?php endif; ?>
        </div>
        <div class="form__container-three">
            <div class="form__item <?= isset($errors['lot-rate']) ? 'form__item--invalid' : '' ?> form__item--small">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" value="<?= getPostVal('lot-rate'); ?>" placeholder="0">
                <?php if(isset($errors)) : ?>
                <span class="form__error"><?= $errors['lot-rate']=='Указана неверая цена лота' ? 'Введите корректную цену лота' : 'Введите начальную цену' ?></span>
                <?php endif; ?>
            </div>
            <div class="form__item <?= isset($errors['lot-step']) ? 'form__item--invalid' : '' ?> form__item--small">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" value="<?= getPostVal('lot-step'); ?>" placeholder="0">
                <?php if(isset($errors)) : ?>
                <span class="form__error"><?= $errors['lot-step']=='Указан неверный шаг ставки' ? 'Введите корректный шаг ставки' : 'Введите шаг ставки' ?></span>
                <?php endif; ?>
            </div>
            <div class="form__item <?= isset($errors['lot-date']) ? 'form__item--invalid' : '' ?> ">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?= getPostVal('lot-date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <?php if(isset($errors)) : ?>
                <span class="form__error"><?= $errors['lot-date']=='Указана неварная дата окончания торгов' ? $errors['lot-date'] : 'Введите дату окончания торгов' ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php endif; ?>
        <button type="submit" class="button">Добавить лот</button>
    </form>
