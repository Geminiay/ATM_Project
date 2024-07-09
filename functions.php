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

    $sql = "SELECT denomination, quantity FROM banknotes WHERE denomination IN (200, 100, 50, 20, 10, 5) ORDER BY denomination DESC";
    $result = $conn->query($sql);

    $banknotes = [
        200 => 0,
        100 => 0,
        50 => 0,
        20 => 0,
        10 => 0,
        5 => 0
    ];

    while ($row = $result->fetch_assoc()) {
        $banknotes[$row['denomination']] = $row['quantity'];
    }

    $withdraw200 = 0;
    $withdraw100 = 0;
    $withdraw50 = 0;
    $withdraw20 = 0;
    $withdraw10 = 0;
    $withdraw5 = 0;

    $remaining = $amount;

    foreach ($banknotes as $denomination => $quantity) {
        $numNotes = min(floor($remaining / $denomination), $quantity);
        $remaining -= $numNotes * $denomination;
        if ($denomination == 200) $withdraw200 = $numNotes;
        if ($denomination == 100) $withdraw100 = $numNotes;
        if ($denomination == 50) $withdraw50 = $numNotes;
        if ($denomination == 20) $withdraw20 = $numNotes;
        if ($denomination == 10) $withdraw10 = $numNotes;
        if ($denomination == 5) $withdraw5 = $numNotes;
    }

    if ($remaining > 0) {
        return "Insufficient funds or appropriate denominations.";
    }

    if ($withdraw200 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw200 WHERE denomination = 200");
    if ($withdraw100 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw100 WHERE denomination = 100");
    if ($withdraw50 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw50 WHERE denomination = 50");
    if ($withdraw20 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw20 WHERE denomination = 20");
    if ($withdraw10 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw10 WHERE denomination = 10");
    if ($withdraw5 > 0) $conn->query("UPDATE banknotes SET quantity = quantity - $withdraw5 WHERE denomination = 5");

    $conn->close();

    return "Withdraw successful. Banknotes dispensed: <br>200 x $withdraw200<br>100 x $withdraw100<br>50 x $withdraw50<br>20 x $withdraw20<br>10 x $withdraw10<br>5 x $withdraw5";
}

function deposit($deposit5, $deposit10, $deposit20, $deposit50, $deposit100, $deposit200) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($deposit5 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit5 WHERE denomination = 5");
    if ($deposit10 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit10 WHERE denomination = 10");
    if ($deposit20 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit20 WHERE denomination = 20");
    if ($deposit50 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit50 WHERE denomination = 50");
    if ($deposit100 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit100 WHERE denomination = 100");
    if ($deposit200 > 0) $conn->query("UPDATE banknotes SET quantity = quantity + $deposit200 WHERE denomination = 200");

    $conn->close();
    return "Deposit successful.";
}
?>
