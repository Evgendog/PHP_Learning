<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar</title>
    <style>
        table, th, td {
            border: 1px solid rgba(53,123,38,0.85);
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .day-names {
            background-color: rgba(118,123,114,0.56);
        }
        .workweek {
            background-color: rgba(37,254,215,0.62);
        }
        .weekend {
            background-color: rgba(254,94,222,0.62);
        }
        .blank {
            background-color: rgba(53,123,38,0.56);
        }
    </style>
</head>
<body>
<?php
require_once 'UsefulFunctions.php';
$browser = getBroAndPlatInfo();
$badbrowsers = ['Internet Explorer', 'Mozilla Firefox'];
$isbad = in_array($browser['broname'], $badbrowsers);
?>
<form method="post">
    <?php
    if ($isbad) {
    ?>
    <label for="year">Введите год (принимаются значения от 1920 до 2120)</label>
    <input type="number" name="year" id="year" min="1920" max="2120" placeholder="2020" required> <br>
    <label for="month">Введите месяц (принимаются значения от 1 до 12)</label>
    <input type="number" name="month" id="month" min="1" max="12" placeholder="11" required> <br>
    <?php
    } else {
    ?>
    <label for="date">Выберите/введите месяц и год (принимаются даты от 01.1920 до 12.2120)</label>
    <input type="month" id="date" name="date" min="1920-01" max="2120-12" required><br>
    <?php
    }
    ?>
    <input type="submit" value="Получить календарь на выбранный месяц" style="margin-top: 5px">
</form>
<?php
date_default_timezone_set('Etc/GMT-7');
if ($isbad ? isset($_POST['year']) && isset($_POST['month']) : isset($_POST['date'])) {
    $month = $isbad ? $_POST['month'] : date('m', strtotime($_POST['date']));
    $year = $isbad ? $_POST['year'] : date('Y', strtotime($_POST['date']));
    $lastDayOfMonth = date("t", strtotime("$year-$month-01"));
    if (date("N", strtotime("$year-$month-01")) == 1 && $lastDayOfMonth == 28) {
        $amountOfWeeks = 4;
    } elseif (date("N", strtotime("$year-$month-01")) > date("N", strtotime("$year-$month-$lastDayOfMonth")) && $lastDayOfMonth > 28) {
        $amountOfWeeks = 6;
    } else {
        $amountOfWeeks = 5;
    }
    $daysOfMonth = [];
    $dayToStart = date("N", strtotime("$year-$month-01"));
    $numberOfDay = 1;
    for ($i = 0; $i < $amountOfWeeks; $i++) {
        $temp = [];
        for ($j = 0; $j < 7; $j++) {
            $temp[] = ($i == 0 && $j + 1 < $dayToStart) || $numberOfDay > $lastDayOfMonth ? '' : $numberOfDay;
            $numberOfDay = $i == 0 && $j + 1 < $dayToStart ? $numberOfDay : $numberOfDay + 1;
        }
        $daysOfMonth[] = $temp;
    }
    $monthsList = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
    ?>
    <h1>Календарь на <?= $monthsList[$month - 1] . " " . $year ?>-го года</h1>
    <table>
        <tr class="day-names">
            <th>Пн</th>
            <th>Вт</th>
            <th>Ср</th>
            <th>Чт</th>
            <th>Пт</th>
            <th>Сб</th>
            <th>Вс</th>
        </tr>
        <?php
        foreach ($daysOfMonth as $weeks) {
            $i = 1;
            ?>
            <tr>
                <?php
                foreach ($weeks as $day) {
                    if ($day == '') {
                        ?>
                        <td class="blank"><?= $day ?></td>
                        <?php
                    } elseif ($i < 6) {
                        ?>
                        <td class="workweek"><?= $day ?></td>
                        <?php
                    } else {
                        ?>
                        <td class="weekend"><?= $day ?></td>
                        <?php
                    }
                    $i += 1;
                    ?>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
?>
</body>
</html>