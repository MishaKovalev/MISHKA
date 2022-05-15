<?php

header('Content-Type: text/html; charset=UTF-8');
session_start();

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();
    if (!empty($_GET['logout'])) {
        setcookie('name_value', '', 100000);
        setcookie('email_value', '', 100000);
        setcookie('date_value', '', 100000);
        setcookie('gender_value', '', 100000);
        setcookie('parts_value', '', 100000);
        setcookie('powers_value', '', 100000);
        setcookie('bio_value', '', 100000);
        setcookie('policy_value', '', 100000);
    }
    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        // Выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
        // Если в куках есть пароль, то выводим сообщение.
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf(
                'Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass'])
            );
        }
    }

    // Складываем признак ошибок в массив.
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['date'] = !empty($_COOKIE['date_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['parts'] = !empty($_COOKIE['parts_error']);
    $errors['powers'] = !empty($_COOKIE['powers_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['policy'] = !empty($_COOKIE['policy_error']);

    if ($errors['name']) {
        setcookie('name_error', '', 100000);
        $messages[] = '<div class="error">Введите имя.</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error">Введите верный email.</div>';
    }
    if ($errors['date']) {
        setcookie('date_error', '', 100000);
        $messages[] = '<div class="error">Введите корректную дату рождения.</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div class="error">Выберите пол.</div>';
    }
    if ($errors['parts']) {
        setcookie('parts_error', '', 100000);
        $messages[] = '<div class="error">Выберите количество конечностей.</div>';
    }
    if ($errors['powers']) {
        setcookie('powers_error', '', 100000);
        $messages[] = '<div class="error">Выберите суперспособнос(ть/ти).</div>';
    }
    if ($errors['bio']) {
        setcookie('bio_error', '', 100000);
        $messages[] = '<div class="error">Расскажите о себе.</div>';
    }
    if ($errors['policy']) {
        setcookie('policy_error', '', 100000);
        $messages[] = '<div class="error">Ознакомтесь с политикой обработки данных.</div>';
    }

    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
    $values['date'] = empty($_COOKIE['date_value']) ? '' : strip_tags($_COOKIE['date_value']);
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : strip_tags($_COOKIE['gender_value']);
    $values['parts'] = empty($_COOKIE['parts_value']) ? '' : strip_tags($_COOKIE['parts_value']);
    $values['powers'] = empty($_COOKIE['powers_value']) ? '' : strip_tags($_COOKIE['powers_value']);
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
    $values['policy'] = empty($_COOKIE['policy_value']) ? '' : strip_tags($_COOKIE['policy_value']);

    if (empty($errors) && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        try {
            $user = 'u47529';
            $pass = '5988897';
            $member = $_SESSION['login'];
            $db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
            $stmt = $db->prepare("SELECT * FROM users2 WHERE login = ?");
            $stmt->execute(array($member));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $values['name'] = $result['name'];
            $values['email'] = $result['email'];
            $values['date'] = $result['date'];
            $values['gender'] = $result['gender'];
            $values['parts'] = $result['parts'];
            $values['bio'] = $result['bio'];
            $values['policy'] = $result['policy'];

            $powers = $db->prepare("SELECT distinct name from superusers join superpowers3 pow on power_id = pow.id where user_id = ?");
            $powers->execute(array($member));
            $result = $powers->fetchAll(PDO::FETCH_ASSOC);
            $values['select'] = implode(',', $result);
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }
        printf('<div>Вход с логином %s, uid %d</div>', $_SESSION['login'], $_SESSION['uid']);
    }
    include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    $errors = FALSE;
    // проверка поля имени
    if (!preg_match('/^([a-zA-Z]|[а-яА-Я])/', $_POST['name'])) {
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('name_value', $_POST['name'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля email
    if (!preg_match('/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/', $_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля даты рождения
    $date = explode('-', $_POST['date']);
    $age = (int)date('Y') - (int)$date[0];
    if ($age > 100 || $age < 0) {
        setcookie('date_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('date_value', $_POST['date'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля пола
    if (empty($_POST['gender'])) {
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gender_value', $_POST['gender'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля количества конечностей
    if (empty($_POST['parts'])) {
        setcookie('parts_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('parts_value', $_POST['parts'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля суперспособностей
    if (empty($_POST['powers'])) {
        setcookie('powers_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('powers_value', $_POST['powers'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля биографии
    if (empty($_POST['bio'])) {
        setcookie('bio_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('bio_value', $_POST['bio'], time() + 12 * 30 * 24 * 60 * 60);
    }

    // проверка поля политики обработки данных 
    if (empty($_POST['policy'])) {
        setcookie('policy_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('policy_value', $_POST['policy'], time() + 12 * 30 * 24 * 60 * 60);
    }

    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        header('Location: index.php');
        exit();
    } else {
        setcookie('name_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('date_error', '', 100000);
        setcookie('gender_error', '', 100000);
        setcookie('parts_error', '', 100000);
        setcookie('powers_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('policy_error', '', 100000);
    }

    $user = 'u47529';
    $pass = '5988897';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $gender = $_POST['gender'];
    $parts = $_POST['parts'];
    $bio = $_POST['bio'];
    $policy = $_POST['policy'];
    $powers = $_POST['powers'];
    $member = $_SESSION['login'];

    $db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        try {
            $stmt = $db->prepare("UPDATE users2 SET name = ?, email = ?, date = ?, gender = ?, parts = ?, bio = ?, policy = ? WHERE login = ?");
            $stmt->execute(array($name, $email, $date, $gender, $parts, $bio, $policy, $member));

            $stmt = $db->prepare("SELECT id FROM users2 WHERE login = ?");
            $stmt->execute(array($member));
            $user_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $superpowers = $db->prepare("DELETE FROM superusers WHERE user_id = ?");
            $superpowers->execute(array($user_id['id']));

            foreach ($powers as $value) {
                $stmt = $db->prepare("SELECT id from superpowers3 WHERE name = ?");
                $stmt->execute(array($value));
                $power_id = $stmt->fetch(PDO::FETCH_ASSOC);

                $superpowers = $db->prepare("INSERT INTO superusers SET power_id = ?, user_id = ? ");
                $superpowers->execute(array($power_id['id'], $user_id['id']));
            }
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }
    } else {
        $login = uniqid();
        $password = uniqid();
        $hash = md5($password);
        // Сохраняем в Cookies.
        setcookie('login', $login);
        setcookie('pass', $password);

        try {
            $stmt = $db->prepare("INSERT INTO users2 SET login = ?, pass = ?, name = ?, email = ?, date = ?, gender = ?, parts = ?, bio = ?, policy = ?");
            $stmt->execute(array($login, $hash, $name, $email, $date, $gender, $parts, $bio, $policy));
            $user_id = $db->lastInsertId();
            foreach ($powers as $value) {
                $stmt = $db->prepare("SELECT id from superpowers3 WHERE name = ?");
                $stmt->execute(array($value));
                $power_id = $stmt->fetch(PDO::FETCH_ASSOC);

                $superpowers = $db->prepare("INSERT INTO superusers SET power_id = ?, user_id = ? ");
                $superpowers->execute(array($power_id['id'], $user_id));
            }
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }
    }
    setcookie('save', '1');
    header('Location: ./');
}
