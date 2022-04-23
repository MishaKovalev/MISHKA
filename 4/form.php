<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <title>Задание 3</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 450px;
            margin: 50px auto;
            padding: 15px;
            border-radius: 7px;
            border: 1px solid rgba(0, 0, 0, 0.4);
            background-color: rgba(255, 255, 255, 0.4);
        }

        .form-title {
            margin-bottom: 5px;
            font-size: 20px;
            font-weight: 500;
        }

        input.form-control, .block {
            margin-bottom: 7px;
        }

        .radio-btns {
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-radius: 5px;
        }

        .btn-container {
            width: 100%;
            text-align: center;
        }

        .error {
            color: rgba(245, 46, 46, 1);
            border: 1px solid rgba(245, 46, 46, 1);
        }

    </style>
</head>

<body>
    <?php
    if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
            print($message);
        }
        print('</div>');
    }
    ?>
    <div class="form-container">
        <div class="form-title">Анкета</div>
        <form method="POST" action="">
            <input type="text" class="form-control" name="name" placeholder="Ваше имя..." 
            <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>"/>
            <input type="text" class="form-control" name="email" placeholder="Ваш email..." 
            <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>"/>
            <div class="block" id="date-block">
                <span class="block-title">Дата рождения</span>
                <input type="date" class="form-control" name="date" <?php if ($errors['date']) { print 'class="error"';} ?> value="<?php print $values['date']; ?>"/>
            </div>
            <div class="block" id="gender-block">
                <span>Пол:</span>
                <div class="radio-btns">
                    <div class="male-radio">
                        <input class="form-check-input" type="radio" name="gender" value="m" <?php if ($values['gender'] == 'm') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="male">Муж</label>
                    </div>
                    <div class="female-radio">
                        <input class="form-check-input" type="radio" name="gender" value="f" />
                        <label class="form-check-label" for="female">Жен</label>
                    </div>
                </div>
            </div>
            <div class="block">
                <span class="block-title">Конечности:</span>
                <div class="radio-btns">
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="1" <?php if ($values['parts'] == '1') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="male">1</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="2" <?php if ($values['parts'] == '2') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="female">2</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="3" <?php if ($values['parts'] == '3') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="female">3</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="4" <?php if ($values['parts'] == '4') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="female">4</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="m" <?php if ($values['parts'] == 'm') {print 'checked';}; ?>/>
                        <label class="form-check-label" for="female">Больше</label>
                    </div>
                </div>
            </div>
            <div class="block">
                <span class="block-title">Выберите суперспособности</span>
                <select class="form-select" name="powers[]" multiple <?php if ($errors['powers']) {print 'class="error"';} ?>>
                    <option value="walls" <?php $arr = explode(',', $values['powers']);
                                        if ($arr != '') {
                                            foreach ($arr as $value) {
                                                if ($value == 'walls') {
                                                    print 'selected';
                                                }
                                            }
                                        }
                                        ?>>Прохождение сквозь стены</option>
                    <option value="immortality" <?php $arr = explode(',', $values['powers']);
                                        if ($arr != '') {
                                            foreach ($arr as $value) {
                                                if ($value == 'immortality') {
                                                    print 'selected';
                                                }
                                            }
                                        }
                                        ?>>Бессмертие</option>
                    <option value="levitation" <?php $arr = explode(',', $values['powers']);
                                        if ($arr != '') {
                                            foreach ($arr as $value) {
                                                if ($value == 'levitation') {
                                                    print 'selected';
                                                }
                                            }
                                        }
                                        ?>>Левитация</option>
                </select>
            </div>
            <div class="block">
                <span class="block-title">Биография</span>
                 <textarea class="form-control" placeholder="Расскажите о себе..." name="bio" <?php if ($errors['bio']) {print 'class="error"';} ?> value="<?php print $values['bio']; ?>"></textarea>
            </div>
            <div class="form-check policy">
                <input class="form-check-input" type="checkbox" value="y" id="policy" name="policy" checked/>
                <label class="form-check-label" for="policy">Согласен с политикой обработки данных</label>
            </div>
            <div class="btn-container">
                <button class="btn btn-primary" type="submit" id="send-btn">
                    Отправить
                </button>
            </div>
        </form>
    </div>
</body>

</html>
