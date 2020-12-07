<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fibonacci numbers</title>
</head>
<body>
<form method="post">
    <label for="number">Enter positive number to calculate Fibonacci number</label>
    <input type="number" name="number" id="number" min="1">
    <input type="submit">
</form>
<?php
if(array_key_exists('number', $_POST)) {
    $numb = $_POST["number"];
    $fibonacciNumber = 0;
    if($numb == 1) {
        $fibonacciNumber = 0;
    } elseif ($numb == 2) {
        $fibonacciNumber = 1;
    } else {
        $temp1 = 0;
        $temp2 = 1;
        for($i = 2; $i < $numb; $i++) {
            $fibonacciNumber = $temp1 + $temp2;
            $temp1 = $temp2;
            $temp2 = $fibonacciNumber;
        }
    }
    echo "Your Fibonacci number = $fibonacciNumber";
}
?>
</body>

</html>