<?php

$user = 'u47529';
$pass = '5988897';
$db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['delete'])) {
        $stmt = $db->prepare("SELECT * FROM users2 WHERE login = ?");
        $stmt->execute(array($_POST['delete']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            print('<p>Ошибка при удалении данных</p>');
        } else {
            $stmt = $db->prepare("DELETE FROM users2 WHERE login = ?");
            $stmt->execute(array($_POST['delete']));

            $powers = $db->prepare("DELETE FROM superusers where user_id = ?");
            $powers->execute(array($_COOKIE['user_id']));
            header('Location: ?delete_error=0');
        }
    } else if (!empty($_POST['edit'])) {
        $user = 'u47529';
        $pass = '5988897';
        $member_id = $_POST['edit'];

        $db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("SELECT * FROM users2 WHERE login = ?");
        $stmt->execute(array($member_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $values['name'] = $result['name'];
        $values['email'] = $result['email'];
        $values['birth'] = $result['date'];
        $values['gender'] = $result['gender'];
        $values['parts'] = $result['parts'];
        $values['bio'] = $result['bio'];
        $values['policy'] = $result['policy'];

        setcookie('user_id', $member_id, time() + 12 * 30 * 24 * 60 * 60);

        $powers = $db->prepare("SELECT distinct name from superusers join superpowers3 pow on power_id = pow.id where user_id = ?");
        $powers->execute(array($member_id));
        $result = $powers->fetchAll(PDO::FETCH_ASSOC);
        $str = "";
        foreach ($powers as $power) {
            $str .= $power['name'] . ',';
        }
        $values['select'] = $str;
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $date = $_POST['date'];
        $gender = $_POST['gender'];
        $parts = $_POST['parts'];
        $bio = $_POST['bio'];
        $policy = $_POST['policy'];
        $select = $_POST['select'];
        $user = 'u47529';
        $pass = '5988897';
        $db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

        $member_id = $_COOKIE['user_id'];

        try {
            $stmt = $db->prepare("SELECT login FROM users2 WHERE id = ?");
            $stmt->execute(array($member_id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("UPDATE users2 SET name = ?, email = ?, date = ?, gender = ?, parts = ?, bio = ?, policy = ? WHERE login = ?");
            $stmt->execute(array($name, $email, $date, $gender, $parts, $bio, $policy, $result['login']));

            $superpowers = $db->prepare("DELETE FROM superusers WHERE user_id = ?");
            $superpowers->execute(array($member_id));

            foreach ($select as $value) {
                $stmt = $db->prepare("SELECT id from superpowers3 WHERE name = ?");
                $stmt->execute(array($value));
                $power_id = $stmt->fetch(PDO::FETCH_ASSOC);

                $superpowers = $db->prepare("INSERT INTO superusers SET power_id = ?, user_id = ? ");
                $superpowers->execute(array($power_id['id'], $member_id));
            }
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }
    }
}

if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM admins WHERE login = ?");
        $stmt->execute(array($_SERVER['PHP_AUTH_USER']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }

    if (empty($result['password'])) {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Неверный логин</h1>');
        exit();
    }

    if ($result['password'] != md5($_SERVER['PHP_AUTH_PW'])) {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Неверный пароль</h1>');
        exit();
    }

    print('Вы успешно авторизовались и видите защищенные паролем данные.');

    $stmt = $db->prepare("SELECT * FROM users2");
    $stmt->execute([]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT pow.name AS name, count(*) AS amount FROM superusers JOIN superpowers3 pow ON power_id = pow.id GROUP BY power_id");
    $stmt->execute();
    $powersCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <title>Admin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            border: 2px solid #E3E6EC;
            border-collapse: collapse;
        }

        td,
        th {
            font-size: 13px;
            padding: 0px 8px;
        }
    </style>
</head>

<body>
    <div class="records-list">
        <table>
            <tr>
                <th>Название способности</th>
                <th>Количество обладателей</th>
            </tr>
            <?php
            if (!empty($powersCount)) {
                foreach ($powersCount as $value) {
            ?><tr>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['amount'] ?></td>
                    </tr>
            <?php }
            } ?>
        </table>
    </div>
    <div class="records-list">
        <table>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Дата рождения</th>
                <th>Конечности</th>
                <th>Пол</th>
                <th>Команда</th>
                <th>Биография</th>
            </tr>
            <?php
            if (!empty($result)) {
                foreach ($result as $value) {
            ?>
                    <tr>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['email'] ?></td>
                        <td><?php echo $value['date'] ?></td>
                        <td><?php echo $value['gender'] ?></td>
                        <td><?php echo $value['parts'] ?></td>
                        <td>
                            <?php
                            $powers = $db->prepare("SELECT distinct name from superusers join superpowers3 pow on power_id = pow.id where user_id = ?");
                            $powers->execute(array($value['id']));
                            $superpowers = $powers->fetchAll(PDO::FETCH_ASSOC);
                            $str = "";
                            foreach ($superpowers as $power) {
                                $str .= $power['name'] . ',';
                            }
                            ?>
                        </td>
                        <td id="bio">
                            <?php echo $value['bio'] ?>
                        </td>
                        <td class="edit-buttons">
                            <form action="" method="post">
                                <input value="<?php echo $value['id'] ?>" name="edit" type="hidden" />
                                <button id="edit">Edit</button>
                            </form>
                        </td>
                        <td class="edit-buttons">
                            <form action="" method="post">
                                <input value="<?php echo $value['login'] ?>" name="delete" type="hidden" />
                                <button id="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "Записи не найдены";
            }
            ?>
        </table>
    </div>
    <?php if (!empty($_POST['edit'])) {
        include('updateform.php');
    } ?>
</body>

</html>
