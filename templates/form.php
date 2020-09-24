<link rel="stylesheet" href="<?php echo THEME_URL; ?>/css/form.css" />
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
        <div class="mmm-form__checks mmm-form__checks--radio">
            <input type="radio" name="sex" value="男" id="male" /><label for="male">男</label>
            <input type="radio" name="sex" value="女" id="female" /><label for="female">女</label>
        </div>
    </fieldset>
    <fieldset>
        <legend><span class="mmm-form__title">連絡手段</span></legend>
        <div class="mmm-form__checks mmm-form__checks--checkbox">
            <input type="checkbox" name="contact[]" value="電話" id="tel" /><label for="tel">電話</label>
            <input type="checkbox" name="contact[]" value="メール" id="email" /><label for="email">メール</label>
            <input type="checkbox" name="contact[]" value="FAX" id="fax" /><label for="fax">FAX</label>
        </div>
    </fieldset>
    <fieldset>
        <legend><span class="mmm-form__title">血液型</span></legend>
        <div class="mmm-form__selects">
            <select name="blood">
                <option value="">選択する</option>
                <option value="A">A型</option>
                <option value="B">B型</option>
                <option value="O">O型</option>
                <option value="AB">AB型</option>
            </select>
        </div>
    </fieldset>
    <fieldset>
        <legend><span class="mmm-form__title">メッセージ</span></legend>
        <textarea name="message"></textarea>
    </fieldset>
    <div class="mmm-form__btns"><input class="mmm-form__btn" type="submit" value="送信" /><button class="mmm-form__btn">確認</button></div>
</div>
