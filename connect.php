<?php

$connect = new mysqli("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

// Проверка подключения к базе данных
if ($connect->connect_error) {
    die("Ошибка подключения: " . $connect->connect_error);
}

// Выполнение запроса к базе данных для получения данных из таблицы Panels
$sql = "SELECT IP, login, pass FROM Panels";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Получение первой строки результата запроса
    $row = $result->fetch_assoc();

    // Формирование массива данных для отправки в формате JSON
    $data = array(
        'host' => $row['IP'],
        'usr' => $row['login'],
        'pass' => $row['pass']
    );

    // Отправка данных в формате JSON
    echo json_encode($data);
} else {
    echo "Нет данных в таблице Panels.";
}

// Закрытие подключения к базе данных
$connect->close();
?>