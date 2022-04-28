<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!empty($_SESSION['login'])) {
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['logout'])) {
    session_destroy();
    $_SESSION['login'] = '';
    header('Location: ./?logout=1');
  }
  if (!empty($_GET['error'])) {
    print('<div>Не верный пароль/логин проверьте корректность введенных данных</div>');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            width: 150px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
        <form action="" method="POST">
            <div>
                <span>Логин:</span>
                <input name="login" />
            </div>
            <div>
                <span>Пароль:</span>
                <input name="pass" />
            </div>
            <input type="submit" value="Войти" />
          </form>
    </div>
</body>
</html>

<?php
}

else {
  $user = 'u47529';
  $pass = '5988897';
  $db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

  $member = $_POST['login'];
  $member_pass_hash = md5($_POST['pass']);

  try {
    $stmt = $db->prepare("SELECT * FROM users2 WHERE login = ?");
    $stmt->execute(array($member));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }
  if ($result['pass'] == $member_pass_hash) {

    $_SESSION['login'] = $_POST['login'];
    $_SESSION['uid'] = $result['id'];

    header('Location: ./');
  } else {
    header('Location: ?error=1');
  }
}