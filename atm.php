<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>ATM</h1>
        <div>
            <h2>Withdraw Money</h2>
            <form action="atm.php" method="post">
                <label for="withdrawAmount">Amount:</label>
                <input type="number" id="withdrawAmount" name="withdrawAmount" required>
                <button type="submit" name="action" value="withdraw">Withdraw</button>
            </form>
            <div class="result">
                <?php
                require_once 'functions.php';

                if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'withdraw') {
                    $amount = intval($_POST['withdrawAmount']);
                    $result = withdraw($amount);
                    if (is_array($result)) {
                        echo "Withdraw successful. Banknotes dispensed: <br>";
                        foreach ($result as $denomination => $count) {
                            echo "$denomination x $count<br>";
                        }
                    } else {
                        echo $result;
                    }
                }
                ?>
            </div>
        </div>
        <div>
            <h2>Deposit Money</h2>
            <form action="atm.php" method="post">
                <label for="deposit5">5:</label>
                <input type="number" id="deposit5" name="deposit[5]" value="0" required>
                <br>
                <label for="deposit10">10:</label>
                <input type="number" id="deposit10" name="deposit[10]" value="0" required>
                <br>
                <label for="deposit20">20:</label>
                <input type="number" id="deposit20" name="deposit[20]" value="0" required>
                <br>
                <label for="deposit50">50:</label>
                <input type="number" id="deposit50" name="deposit[50]" value="0" required>
                <br>
                <label for="deposit100">100:</label>
                <input type="number" id="deposit100" name="deposit[100]" value="0" required>
                <br>
                <label for="deposit200">200:</label>
                <input type="number" id="deposit200" name="deposit[200]" value="0" required>
                <br>
                <button type="submit" name="action" value="deposit">Deposit</button>
            </form>
            <div class="result">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'deposit') {
                    $depositNotes = $_POST['deposit'];
                    $result = deposit($depositNotes);
                    echo $result;
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>