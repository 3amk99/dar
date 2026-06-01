<?php
$student_id = $_GET['id'];
?>

<!DOCTYPE html>
<html>

<head>

    <title>
        Student Statistics
    </title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        body
        {
            font-family: Arial;
            padding: 20px;
        }

        .box
        {
            width: 900px;
            margin: auto;
        }

        select
        {
            padding: 8px;
            margin-bottom: 20px;
        }

        canvas
        {
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
        }

    </style>

</head>

<body>

    <div class="box">

        <h2>
            Student Statistics
        </h2>

        <select id="type">

            <option value="day">
                Day (08 - 18)
            </option>

            <option value="week">
                Week
            </option>

            <option value="month">
                Month
            </option>

        </select>
        <input type="date" id="date">
        <canvas id="chart"></canvas>

    </div>

<script>

let chart;
let studentId = <?= $student_id ?>;

document.getElementById("type").addEventListener("change", loadData);
document.getElementById("date").addEventListener("change", loadData);

loadData();

function loadData()
{
    let type = document.getElementById("type").value;
    let date = document.getElementById("date").value;

    let url = "get_stats.php?student_id=" + studentId + "&type=" + type;

    if (type == "day" && date != "")
    {
        url += "&date=" + date;
    }

    if (type == "day" && !date)
    {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("date").value = today;
        date = today;
        url += "&date=" + date;
    }

    fetch(url)
    .then(response => response.json())
    .then(data =>
    {
        let labels = [];
        let absent = [];
        let colors = [];



        // DAY LOGIC
        if (type == "day")
        {
            let map = {};

            data.forEach(item =>
            {
                map[item.hour] = item.status;
            });

            for (let h = 8; h <= 18; h++)
            {
                labels.push(h + ":00");

                // PAUSE
                if (h >= 13 && h <= 14)
                {
                    absent.push(0);
                    colors.push("gray");
                }

                // NO DATA for this hour
                else if (typeof map[h] === "undefined")
                {
                    absent.push(0);
                    colors.push("#525252");
                }

                // ABSENT
                else if (map[h] == "absent")
                {
                    absent.push(1);
                    colors.push("red");
                }

                // PRESENT
                else
                {
                    absent.push(1);
                    colors.push("green");
                }
            }
        }



        // WEEK LOGIC
        if (type == "week")
        {
            data.forEach(item =>
            {
                labels.push("Week " + item.week);
                absent.push(item.absent);
                colors.push("red");
            });
        }



        // MONTH LOGIC
        if (type == "month")
        {
            data.forEach(item =>
            {
                labels.push("Month " + item.month);
                absent.push(item.absent);
                colors.push("red");
            });
        }



        if (chart)
        {
            chart.destroy();
        }



        chart = new Chart(
            document.getElementById("chart"),
            {
                type: "bar",

                data:
                {
                    labels: labels,

                    datasets:
                    [
                        {
                            label: "Absence",

                            data: absent,

                            backgroundColor: colors
                        }
                    ]
                }
            }
        );
    });
}

</script>

</body>

</html>