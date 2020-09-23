<?php get_header(); ?>
<link rel='stylesheet' href='<?php echo THEME_URL; ?>/css/contact.css' />
<div class="mmm-post mmm-post--single">
    <h2 class="mmm-post__title mmm-post--single__title">お問い合わせフォーム</h2>
    <div class="mmm-post__content mmm-post--single__content">
        <div class="mmm-form">
            <fieldset>
                <legend><span class="mmm-form__title">名前</span><span class="mmm-form__require">必須</span></legend>
                <input type="text" name="name" />
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__title">メールアドレス</span><span class="mmm-form__require">必須</span></legend>
                <input type="email" name="email" />
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__title">電話番号</span></legend>
                <input type="tel" name="tel" />
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__title">性別</span></legend>
                <div class="mmm-form__radios">
                    <input type="radio" name="sex" value="男" id="male" /><label for="male">男</label>
                    <input type="radio" name="sex" value="女" id="female" /><label for="female">女</label>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__title">血液型</span></legend>
                <select name="blood">
                    <option value="">選択する ▼</option>
                    <option value="A">A型</option>
                    <option value="B">B型</option>
                    <option value="O">O型</option>
                    <option value="AB">AB型</option>
                </select>
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__title">メッセージ</span></legend>
                <textarea name="message"></textarea>
            </fieldset>
            <div class="mmm-form__btns"><input type="submit" value="送信" /><button>確認</button></div>
        </div>
    </div>
</div>
<?php get_template_part('templates/after-content'); ?>
<?php get_footer(); ?>
