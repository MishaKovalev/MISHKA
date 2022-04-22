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
            <input type="text" class="form-control" name="name" placeholder="Ваше имя..." />
            <input type="text" class="form-control" name="email" placeholder="Ваш email..." />
            <div class="block" id="date-block">
                <span class="block-title">Дата рождения</span>
                <input type="date" class="form-control" name="date" />
            </div>
            <div class="block" id="gender-block">
                <span>Пол:</span>
                <div class="radio-btns">
                    <div class="male-radio">
                        <input class="form-check-input" type="radio" name="gender" value="m" />
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
                        <input class="form-check-input" type="radio" name="parts" value="1" />
                        <label class="form-check-label" for="male">1</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="2" />
                        <label class="form-check-label" for="female">2</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="3" />
                        <label class="form-check-label" for="female">3</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="4" />
                        <label class="form-check-label" for="female">4</label>
                    </div>
                    <div class="parts-radio">
                        <input class="form-check-input" type="radio" name="parts" value="m" />
                        <label class="form-check-label" for="female">Больше</label>
                    </div>
                </div>
            </div>
            <div class="block">
                <span class="block-title">Выберите суперспособности</span>
                <select class="form-select" name="powers[]" multiple>
                    <option value="walls">Прохождение сквозь стены</option>
                    <option value="immortality">Бессмертие</option>
                    <option value="levitation">Левитация</option>
                </select>
            </div>
            <div class="block">
                <span class="block-title">Биография</span>
                <textarea class="form-control" placeholder="Расскажите о себе..." name="bio"></textarea>
            </div>
            <div class="form-check policy">
                <input class="form-check-input" type="checkbox" value="y" id="policy" name="policy" />
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
