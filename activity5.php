<?php
session_start();

$exchangeRates = array(
    "USD_CAD" => 1.43,
    "USD_EUR" => 0.93,
    "USD_USD" => 1,
    "CAD_USD" => 0.7,
    "CAD_EUR" => 0.65,
    "CAD_CAD" => 1,
    "EUR_USD" => 1.08,
    "EUR_CAD" => 1.54,
    "EUR_EUR" => 1
);

function getExchangeRate($from, $to, $rates) {
    $key = "{$from}_{$to}";
    return isset($rates[$key]) ? $rates[$key] : false;
}

$converted_amount = "";

if (isset($_GET['convert'])) {
    $from_value = floatval($_GET['from_value']);
    $from_currency = $_GET['from_currency'];
    $to_currency = $_GET['to_currency'];

    $rate = getExchangeRate($from_currency, $to_currency, $exchangeRates);
    if ($rate !== false) {
        $converted_amount = $from_value * $rate;
    } else {
        echo "<p style='color:red;'>Conversion rate not available!</p>";
        $converted_amount = 0;
    }

    $_SESSION['from_value'] = $from_value;
    $_SESSION['from_currency'] = $from_currency;
    $_SESSION['to_currency'] = $to_currency;
    $_SESSION['converted_amount'] = $converted_amount;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Currency Calculator</title>
    <meta name="description" content="CENG 311 Inclass Activity 5" />
</head>
<body>

    <form action="activity5.php" method="GET">
        <table>
            <tr>
                <td>From:</td>
                <td><input type="text" name="from_value" value="<?php echo isset($_SESSION['from_value']) ? $_SESSION['from_value'] : ''; ?>" required/></td>
                <td>Currency:</td>
                <td>
                    <select name="from_currency">
                        <option value="USD" <?php echo (isset($_SESSION['from_currency']) && $_SESSION['from_currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                        <option value="CAD" <?php echo (isset($_SESSION['from_currency']) && $_SESSION['from_currency'] == 'CAD') ? 'selected' : ''; ?>>CAD</option>
                        <option value="EUR" <?php echo (isset($_SESSION['from_currency']) && $_SESSION['from_currency'] == 'EUR') ? 'selected' : ''; ?>>EUR</option>
                    </select>
                </td>	
            </tr>
            <tr>
                <td>To:</td>
                <td><input type="text" name="to_value" value="<?php echo isset($_SESSION['converted_amount']) ? $_SESSION['converted_amount'] : ''; ?>" readonly/></td>
                <td>Currency:</td>
                <td>
                    <select name="to_currency">
                        <option value="USD" <?php echo (isset($_SESSION['to_currency']) && $_SESSION['to_currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                        <option value="CAD" <?php echo (isset($_SESSION['to_currency']) && $_SESSION['to_currency'] == 'CAD') ? 'selected' : ''; ?>>CAD</option>
                        <option value="EUR" <?php echo (isset($_SESSION['to_currency']) && $_SESSION['to_currency'] == 'EUR') ? 'selected' : ''; ?>>EUR</option>
                    </select>
                </td>	
            </tr>
            <tr>
                <td colspan="4" align="right">
                    <input type="submit" name="convert" value="Convert"/>
                </td>	
            </tr>
        </table>
    </form>	

    <?php
    if (isset($_SESSION['converted_amount'])) {
        echo "<p><strong>{$_SESSION['from_value']} {$_SESSION['from_currency']} = {$_SESSION['converted_amount']} {$_SESSION['to_currency']}</strong></p>";
    }
    ?>

</body>
</html>
