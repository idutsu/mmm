<?php get_header(); ?>
<style>

    .mmm-form{
        font-size:1.5rem;
    }

    .mmm-form legend{
        display:flex;
        font-weight:bold;
        margin-bottom:.25em;
    }

    .mmm-form__require{
        position:relative;
        display:inline-block;
        color:#FFF;
        background-color:red;
        height:1em;
    }

    .mmm-form__require:before{
        content:"必須";
        display:block;
        font-size:.5em;
        height:100%;
        line-height:1em;
    }

    .mmm-form fieldset{
        margin-bottom:1em;
    }

    .mmm-form input[type=text],
    .mmm-form input[type=email],
    .mmm-form input[type=tel],
    .mmm-form textarea,
    .mmm-form select{
        border:1px solid lightgray;
        width:100%;
        padding:.25em;
    }

    .mmm-form textarea{
        height:10em;
    }

    .mmm-form__radios{
        display:flex;
        flex-wrap:wrap;
    }

    .mmm-form__radios label{
        display:flex;
        align-items:center;
        margin-right:.5em;
    }

    .mmm-form__radios label::before{
        content: "";
        display:inline-block;
        width:1em;
        height:1em;
        border:1px solid lightgray;
        border-radius:50%;
        background-color:#FFF;
        margin-right:.2em;
    }

    .mmm-form__radios input:checked + label::before{
        background-color:lightgray;
    }

    .mmm-form__btns{
        display:flex;
        flex-wrap:wrap;
    }

    .mmm-form__btns input[type='submit'],
    .mmm-form__btns input[type='button'],
    .mmm-form__btns button{
        border:1px solid lightgray;
        padding:.25em 1em;
        margin-right:.5em;
    }

    @media screen and (max-width:768px){

        .mmm-form__btns input[type='submit'],
        .mmm-form__btns input[type='button'],
        .mmm-form__btns button{
            display:block;
            width:100%;
            margin-right:0;
            margin-bottom:.5em;
        }

    }

</style>
<div class="mmm-post mmm-post--single">
    <h2 class="mmm-post__title mmm-post--single__title">お問い合わせフォーム</h2>
    <div class="mmm-post__content">
        <div class="mmm-form">
            <fieldset>
                <legend><span class="mmm-form__require"></span>名前</legend>
                <input type="text" name="name" />
            </fieldset>
            <fieldset>
                <legend><span class="mmm-form__require"></span>メールアドレス</legend>
                <input type="email" name="email" />
            </fieldset>
            <fieldset>
                <legend>電話番号</legend>
                <input type="tel" name="tel" />
            </fieldset>
            <fieldset>
                <legend>性別</legend>
                <div class="mmm-form__radios">
                    <input type="radio" name="sex" value="男" id="male" /><label for="male">男</label>
                    <input type="radio" name="sex" value="女" id="female" /><label for="female">女</label>
                </div>
            </fieldset>
            <fieldset>
                <legend>血液型</legend>
                <select name="blood">
                    <option value="">選択する ▼</option>
                    <option value="A">A型</option>
                    <option value="B">B型</option>
                    <option value="O">O型</option>
                    <option value="AB">AB型</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>メッセージ</legend>
                <textarea name="message"></textarea>
            </fieldset>
            <div class="mmm-form__btns"><input type="submit" value="送信" /><button>確認</button></div>
        </div>
    </div>
</div>
<?php get_template_part('templates/after-content'); ?>
<?php get_footer(); ?>
