<?php
header('Content-Type: text/html; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if (!empty($_GET['save'])) {
        
        print('Спасибо, результаты сохранены.');
    }
    
    include('form.php');
   
    exit();
}

$errors = FALSE;
if (empty($_POST['name'])) {
    print('Заполните имя.<br/>');
    $errors = TRUE;
}
if (empty($_POST['email'])) {
    print('Заполните email.<br/>');
    $errors = TRUE;
}
if (empty($_POST['date'])) {
    print('Выберите дату.<br/>');
    $errors = TRUE;
}
if (empty($_POST['gender'])) {
    print('Выберите пол.<br/>');
    $errors = TRUE;
}
if (empty($_POST['parts'])) {
    print('Выберите количество конечностей.<br/>');
    $errors = TRUE;
}
if (empty($_POST['powers'])) {
    print('Выберите суперспособнос(ть/ти).<br/>');
    $errors = TRUE;
}
if (empty($_POST['bio'])) {
    print('Расскажите о себе.<br/>');
    $errors = TRUE;
}
if (empty($_POST['policy'])) {
    print('Ознакомтесь с политикой обработки данных.<br/>');
    $errors = TRUE;
}

if ($errors) {
    // При наличии ошибок завершаем работу скрипта.
    exit();
}

// Сохранение в базу данных.
$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$gender = $_POST['gender'];
$parts = $_POST['parts'];
$policy = $_POST['policy'];
$powers = implode(',',$_POST['powers']);

$user = 'u47529';
$pass = '5988897';
$db = new PDO('mysql:host=localhost;dbname=u47529', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO users SET name = ?, email = ?, date = ?, gender = ?, parts = ?, bio = ?, policy = ?");
  $stmt->execute(array($name, $email, $date, $gender, $limbs, $bio, $policy));
  $power_id = $db->lastInsertId();
  
  $superpowers = $db->prepare("INSERT INTO superpowers SET powers = ?, userID = ?");
  $superpowers -> execute(array($powers, $power_id));
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

header('Location: ?save=1');
