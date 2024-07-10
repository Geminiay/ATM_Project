<?php
require_once 'db_config.php';

function withdraw($amount) {
    if ($amount % 5 != 0) {
        return "Amount must be a multiple of 5.";
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM banknotes ORDER BY denomination DESC";
    $result = $conn->query($sql);
    $banknotes = [];
    while ($row = $result->fetch_assoc()) {
        $banknotes[$row['denomination']] = $row['quantity'];
    }

    $withdrawNotes = [];
    $originalAmount = $amount;

    foreach ($banknotes as $denomination => $quantity) {
        if ($amount == 0) break;
        $numNotes = min(floor($amount / $denomination), $quantity);
        if ($numNotes > 0) {
            $withdrawNotes[$denomination] = $numNotes;
            $amount -= $numNotes * $denomination;
        }
    }

    if ($amount > 0) {
            return "Insufficient funds or appropriate denominations.";
        
    }

    if ($amount > 0) {
        return "Insufficient funds or appropriate denominations.";
    }

    foreach ($withdrawNotes as $denomination => $numNotes) {
        $sql = "UPDATE banknotes SET quantity = quantity - $numNotes WHERE denomination = $denomination";
        $conn->query($sql);
    }

    $conn->close();
    return $withdrawNotes;
}

function deposit($depositNotes) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($depositNotes as $denomination => $quantity) {
        $sql = "UPDATE banknotes SET quantity = quantity + $quantity WHERE denomination = $denomination";
        $conn->query($sql);
    }

    $conn->close();
    return "Deposit successful.";
}
?>
