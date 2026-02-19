<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";

// Подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Забираем данные
$name = $_POST['name'];
$email = $_POST['email'];
$service_type = $_POST['service_type'];
$urgency = $_POST['urgency'];

// Вставляем
$sql = "INSERT INTO orders (name, email, service_type, urgency) 
        VALUES ('$name', '$email', '$service_type', '$urgency')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Круто, заказ сохранен в базу!</h2>";
    echo "<a href='index.html'>Вернуться на главную</a>";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>