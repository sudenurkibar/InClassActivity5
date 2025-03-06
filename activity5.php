<?php
session_start();

function getExchangeRate($from, $to) {
    $apiKey = "74d6cda80547a0fb95eddaac";
    $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/$from"; 
    
    try {
        if (!ini_get('allow_url_fopen')) {
            throw new Exception('allow_url_fopen close');
        }
        
        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception('API did not respond');
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON eroor');
        }
        
        if (!isset($data["conversion_rates"]) && isset($data["rates"])) {
            $data["conversion_rates"] = $data["rates"];
        }
        
        if (isset($data["conversion_rates"][$to])) {
            return $data["conversion_rates"][$to];
        }
        
        return false;
    } catch (Exception $e) {
        return false;
    }
}

$converted_amount = "";

if (isset($_GET['convert'])) {
    $from_value = floatval($_GET['from_value']);
    $from_currency = $_GET['from_currency'];
    $to_currency = $_GET['to_currency'];

    if ($from_currency == $to_currency) {
        $converted_amount = $from_value;
    } else {
        $rate = getExchangeRate($from_currency, $to_currency);
        if ($rate) {
            $converted_amount = $from_value * $rate;
        } else {
            echo "<p style='color:red;'>Conversion rate not available!</p>";
            $converted_amount = 0;
        }
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
