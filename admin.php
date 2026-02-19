<?php
$conn = new mysqli("localhost", "root", "", "portfolio_db");

// Если нажали на крестик - удаляем из базы (Delete)
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $conn->query("DELETE FROM orders WHERE id=$id");
}

// Если нажали галочку - тупо меняем срочность на "DONE" (Update)
if (isset($_GET['done'])) {
    $id = $_GET['done'];
    $conn->query("UPDATE orders SET urgency='DONE' WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Админка</title>
</head>
<body style="font-family: sans-serif; padding: 20px; background: #f4f4f9;">
    
    <h2>Все заказы</h2>
    
    <table border="1" cellpadding="10" style="border-collapse: collapse; background: white;">
        <tr style="background: #ccc;">
            <th>Имя</th>
            <th>Почта</th>
            <th>Статус</th>
            <th>Что сделать</th>
        </tr>

        <?php
        // Вытаскиваем все записи (Read)
        $res = $conn->query("SELECT * FROM orders");
        
        while($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['urgency'] . "</td>";
            
            // Ссылки передают ID прям в адресную строку
            echo "<td>
                    <a href='?done=" . $row['id'] . "'>[Готово]</a> 
                    <a href='?del=" . $row['id'] . "'>[Удалить]</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <br>
    <a href="index.html">На главную</a>

</body>
</html>