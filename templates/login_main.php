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

</nav>
<form class="form container <?= isset($errors) ? 'form--invalid' : '' ?> "  method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?> "> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= getPostVal('email'); ?>" placeholder="Введите e-mail">
        <?php if(isset($errors)) : ?>
        <span class="form__error"><?= $errors['email']=='Такой пользователь не найден' ? 'Данный пользователь не найден' : 'Введите email' ?></span>
        <?php endif; ?>
    </div>
    <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : '' ?> ">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= getPostVal('password'); ?>" placeholder="Введите пароль">
        <?php if(isset($errors)) : ?>
        <span class="form__error"> <?= $errors['password']=='Неверный пароль' ? $errors['password'] : 'Введите пароль' ?></span>
        <?php endif; ?>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
