<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-80 h-96 bg-white rounded-xl shadow-xl">
        <div class="flex justify-between p-5">
            <h1 class="font-bold text-xl"> <?php echo date('F Y'); ?></h1>
            <div class="flex gap-3">
                <div class="w-[25px] h-[25px] rounded-full bg-cyan-400"></div>
                <div class="w-[25px] h-[25px] rounded-full bg-cyan-400"></div>
            </div>
        </div>
        <div class="p-2">
            <table class="w-full text-sm text-gray-500" cellpadding="5px">
                <thead>
                    <th class="font-bold ">SUN</th>
                    <th class="font-bold ">MON</th>
                    <th class="font-bold ">TUE</th>
                    <th class="font-bold ">WED</th>
                    <th class="font-bold ">THU</th>
                    <th class="font-bold ">FRI</th>
                    <th class="font-bold ">SAT</th>
                </thead>
                <tbody class="font-semibold text-base">
                    <?php
                    $current_month = date('m');
                    $current_year = date('Y');
                    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
                    $first_day_offset = date('w', strtotime("1-$current_month-$current_year"));

                    $day_counter = 1;
                    $last_day = $first_day_offset + $days_in_month;
                    $previous_month = date('m', strtotime("-1 month"));
                    $previous_month_year = date('Y', strtotime("-1 month"));
                    $days_in_previous_month = cal_days_in_month(CAL_GREGORIAN, $previous_month, $previous_month_year);

                    for ($i = 0; $i < 6; $i++) {
                        echo "<tr class='text-center'>";
                        for ($j = 0; $j < 7; $j++) {
                            $day_number = $i * 7 + $j + 1 - $first_day_offset;
                            if ($day_number >= 1 && $day_number <= $days_in_month) {
                                $is_today = ($day_number == date('j') && $current_month == date('m') && $current_year == date('Y'));
                                echo "<td class='" . ($is_today ? 'bg-blue-500 text-white rounded-full' : '') . "'>$day_number</td>";
                            } else if ($day_number < 1) {
                                $previous_month_day = $days_in_previous_month + $day_number;
                                echo "<td class='text-gray-400'>$previous_month_day</td>";
                            } else {
                                $next_month_day = $day_counter++;
                                echo "<td class='text-gray-400'>$next_month_day</td>";
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
