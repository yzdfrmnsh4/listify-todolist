<?php
session_start();

include 'koneksi.php';


// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

$userName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''; // Menggunakan operator ternary untuk memeriksa apakah session ada
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Menggunakan operator ternary untuk memeriksa apakah session ada

date_default_timezone_set('Asia/Jakarta');

$tgl = date("Y-m-d");

$currentDate = date("Y-m-d");
// Query to count notifications from task_manager table
$queryTaskManagerCount = "
    SELECT 
        COUNT(*) AS notification_count
    FROM 
        task_manager 
    WHERE 
        user_id = '$user_id' AND
        status IN ('Not Started', 'On Progres')
        AND DATEDIFF(end_time, '$currentDate') IN (-1, 0, 1, 2, 3)
";
$resultTaskManagerCount = mysqli_query($conn, $queryTaskManagerCount);

$notificationCountTaskManager = 0; // Initialize notification count for task_manager

if ($resultTaskManagerCount) {
    $row = mysqli_fetch_assoc($resultTaskManagerCount);
    $notificationCountTaskManager = $row['notification_count'];
} else {
    echo "Error: " . $queryTaskManagerCount . "<br>" . mysqli_error($conn);
}

// Query to count notifications from task table for today's tasks
$queryTaskCount = "
    SELECT 
        COUNT(*) AS notification_count 
    FROM 
        task 
    WHERE 
        user_id = '$user_id' AND
        DATE(tanggal) = '$currentDate'
";
$resultTaskCount = mysqli_query($conn, $queryTaskCount);

$notificationCountTask = 0; // Initialize notification count for task

if ($resultTaskCount) {
    $row = mysqli_fetch_assoc($resultTaskCount);
    $notificationCountTask = $row['notification_count'];
} else {
    echo "Error: " . $queryTaskCount . "<br>" . mysqli_error($conn);
}

// Total notification count
$notificationCount = $notificationCountTaskManager + $notificationCountTask;

// Query to get notifications from task_manager table
$queryTaskManager = "
    SELECT 
        task_name, 
        DATEDIFF(end_time, '$currentDate') AS interval_tgl 
    FROM 
        task_manager 
    WHERE 
        user_id = '$user_id' AND
        status IN ('Not Started', 'On Progres') AND 
        DATEDIFF(end_time, '$currentDate') IN (0, 1, 2, 3, -1)
";
$resultTaskManager = mysqli_query($conn, $queryTaskManager);

// Query to get notifications from task table for today's date
$queryTask = "
    SELECT 
        task_name 
    FROM 
        task 
    WHERE 
        user_id = '$user_id' AND
        DATE(tanggal) = '$currentDate'
";
$resultTask = mysqli_query($conn, $queryTask);

/// Combine results and count notifications
$notifications = [];
if ($resultTaskManager) {
    while ($task = mysqli_fetch_assoc($resultTaskManager)) {
        $notifications[] = [
            'message' => $task['task_name'],
            'interval' => $task['interval_tgl'],
            'type' => 'task_manager'
        ];
    }
}
if ($resultTask) {
    while ($task = mysqli_fetch_assoc($resultTask)) {
        $notifications[] = [
            'message' => $task['task_name'],
            'type' => 'task'
        ];
    }
}

// Fungsi untuk mendapatkan jumlah total tugas pengguna yang login
function getTotalTasks($conn, $user_id) {
    $query = "SELECT COUNT(*) AS total_tasks FROM task_manager WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_tasks'];
    } else {
        return 0;
    }
}

// Fungsi untuk mendapatkan jumlah total tugas pengguna yang login
function getTotalTodo($conn, $user_id) {
    $query = "SELECT COUNT(*) AS total_todo FROM task WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_todo'];
    } else {
        return 0;
    }
}

// untuk menampilkan data
function getTasks($conn, $user_id) {
    $tasks = array();
    $query = "SELECT * FROM task_manager WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }
    return $tasks;
}



// Ambil daftar tugas pengguna yang login
$tasks = getTasks($conn, $user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard-home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.8.12/tailwind-experimental.min.css'>
    <link rel="stylesheet" href="../src/output.css" />
</head>
<style>
            .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            margin-top: 1rem;
        }
        .dropdown-content .dropdown-item {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #ddd;
        }
        .dropdown-content .dropdown-item:hover {
            background-color: #f1f1f1;
        }
        .show {
            display: block;
        }
</style>
<body class="font-[Poppins] bg-slate-200">
    <section class="flex ">
        <!-- open sidebar -->
        <div id="Sidebar"
            class="w-[240px] flex flex-col gap-[30px] p-[30px] shrink-0 h-screen  bg-white rounded-xl mt-10 my-10 ml-8 ">
            <div class="flex justify-center items-center">
                <img src="../public/img/Listify.svg" class="-ml-3" alt="logo">
            </div>
            <div class="general-menu flex flex-col gap-[18px]">
                <h3 class="font-semibold text-base leading-[21px] text-[#C2BEBE]">Main</h3>
                <a href="home.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl bg-[#6E15FF] drop-shadow-[0_8px_20px_rgba(84,0,222,100)] text-white">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 15 15" class="text-2xl"><path fill="currentColor" d="M7.825.12a.5.5 0 0 0-.65 0L0 6.27v7.23A1.5 1.5 0 0 0 1.5 15h4a.5.5 0 0 0 .5-.5v-3a1.5 1.5 0 0 1 3 0v3a.5.5 0 0 0 .5.5h4a1.5 1.5 0 0 0 1.5-1.5V6.27z"/></svg>
                    </div>
                    <p class="font-semibold">Home</p>
                </a>
                <a href="mytask.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0zM12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036c-.01-.003-.019 0-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M15 2a2 2 0 0 1 1.732 1H18a2 2 0 0 1 2 2v12a5 5 0 0 1-5 5H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.268A2 2 0 0 1 9 2zm-.176 7.379l-4.242 4.243l-1.415-1.415a1 1 0 0 0-1.414 1.414l2.05 2.051a1.1 1.1 0 0 0 1.556 0l4.88-4.879a1 1 0 1 0-1.415-1.414M14.5 4h-5a.5.5 0 0 0-.492.41L9 4.5v1a.5.5 0 0 0 .41.492L9.5 6h5a.5.5 0 0 0 .492-.41L15 5.5v-1a.5.5 0 0 0-.41-.492z"/></g></svg>
                    </div>
                    <p class="font-semibold">My Task</p>
                </a>
                <a href="mytodo.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="6" height="6" x="3" y="5" rx="1"/><path d="m3 17l2 2l4-4m4-9h8m-8 6h8m-8 6h8"/></g></svg>
                    </div>
                    <p class="font-semibold">My Todo</p>
                </a>
                <a href="inbox.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><path fill="currentColor" d="M3.464 20.536C4.93 22 7.286 22 12 22c4.714 0 7.071 0 8.535-1.465c1.271-1.27 1.44-3.213 1.462-6.785H18.84c-.974 0-1.229.016-1.442.114c-.214.099-.392.282-1.026 1.02l-.605.707l-.088.102c-.502.587-.9 1.052-1.45 1.305c-.55.253-1.162.253-1.934.252h-.589c-.773 0-1.385.002-1.935-.252c-.55-.253-.948-.718-1.45-1.305l-.088-.102l-.605-.706c-.634-.74-.812-.922-1.026-1.02c-.213-.099-.468-.115-1.442-.115H2.003c.023 3.572.19 5.515 1.461 6.785"/><path fill="currentColor" d="M20.536 3.464C19.07 2 16.714 2 12 2C7.286 2 4.929 2 3.464 3.464C2 4.93 2 7.286 2 12v.25h3.295c.772 0 1.384-.002 1.934.252c.55.253.948.718 1.45 1.305l.088.102l.605.706c.634.74.812.922 1.026 1.02c.213.099.468.115 1.442.115h.32c.974 0 1.229-.016 1.442-.114c.214-.099.392-.282 1.026-1.02l.605-.707l.088-.102c.502-.587.9-1.052 1.45-1.305c.55-.254 1.162-.253 1.935-.252H22V12c0-4.714 0-7.071-1.465-8.536"/></svg>
                    </div>
                    <p class="font-semibold">Inbox</p>
                    <span class=" bg-[#6E15FF] rounded-full font-semibold px-4 py-1 text-white text-center ml-3"><?php echo $notificationCount; ?></span>
                </a>
                <a href="history.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><path fill="currentColor" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z"/></svg>
                    </div>
                    <p class="font-semibold">History</p>
                </a>
                
            </div>
            <hr class="text-taskia-background-grey"> 
            <button 
                id="logoutButton" 
                class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                <div class="w-6 h-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl">
                        <path fill="currentColor" d="M12 3.25a.75.75 0 0 1 0 1.5a7.25 7.25 0 0 0 0 14.5a.75.75 0 0 1 0 1.5a8.75 8.75 0 1 1 0-17.5"/>
                        <path fill="currentColor" d="M16.47 9.53a.75.75 0 0 1 1.06-1.06l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H10a.75.75 0 0 1 0-1.5h8.19z"/>
                    </svg>
                </div>
                <p class="font-semibold">Log Out</p>
            </button>
        </div>
        <!-- End sidebar -->

        <div id="Content"
        class="flex flex-col bg-taskia-background-grey rounded-l-[60px] w-full max-h-screen overflow-y-scroll p-[50px] gap-[30px]">
        <!-- Open navbar -->
            <div class="dashboard-nav bg-white flex justify-between items-center w-full -mt-2 p-4 rounded-[18px] animate__animated animate__fadeIn">
                <div class=" flex flex-col  ml-8">
                <h1 class="text-left font-bold text-[#5400DE]">Home</h1>
                <p class="text-left text-[#5B5B5B] font-semibold">Today. <span id="date"></span></p>
                </div>
                <div class="flex gap-[30px] items-center">
                    <div class="flex gap-3 items-center">
                    <div class="relative">
                    <button class="relative p-2 text-gray-400 flex justify-center items-center w-12 h-12 hover:bg-gray-100 hover:text-gray-600 focus:bg-gray-100 focus:text-gray-600 rounded-full" id="notificationButton">
                                <span class="sr-only">Notifications</span>
                                <?php if ($notificationCount > 0): ?>
                                    <span class="absolute top-0 right-0 h-4 w-4 mt-1 mr-2 bg-red-500 text-white rounded-full text-xs flex items-center justify-center"><?php echo $notificationCount; ?></span>
                                <?php endif; ?>
                                <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-[#5400DE]">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white border  border-gray-400 px-3 py-3 rounded-lg shadow-lg overflow-hidden z-10 hidden">
                                <?php
                                // Display notifications from task_manager table
                                foreach ($notifications as $notification) {
                                    if ($notification['type'] === 'task_manager') {
                                        $task_name = $notification['message'];
                                        $interval_tgl = $notification['interval'];

                                        if ($interval_tgl == 1 || $interval_tgl == 2 || $interval_tgl == 3) {
                                            echo "<div class='dropdown-item border border-b-2 border-gray-400 mb-1 p-2 rounded-lg'>
                                                <div class='font-bold text-sm mb-1 text-gray-800'>
                                                    <ion-icon name='alert-circle' class='text-blue-500 mr-2'></ion-icon>
                                                    Hai $userName, tugasmu <a style='color:red'>$task_name</a> akan berakhir $interval_tgl hari lagi, ayo kerjakan 💪
                                                </div>
                                            </div>";
                                        } elseif ($interval_tgl == 0) {
                                            echo "<div class='dropdown-item border border-b-2 border-gray-400 mb-1 p-2 rounded-lg'>
                                                <div class='font-bold text-sm mb-1 text-gray-800'>
                                                    <ion-icon name='alert-circle' class='text-blue-500 mr-2'></ion-icon>
                                                    Hai $userName, tugasmu <a style='color:red'>$task_name</a> hari ini batas penyelesaian tugas, ayo kerjakan 💪
                                                </div>
                                            </div>";
                                        } elseif ($interval_tgl < 0) {
                                            echo "<div class='dropdown-item border border-b-2 border-gray-400 mb-1 p-2 rounded-lg'>
                                                <div class='font-bold text-sm mb-1 text-gray-800'>
                                                    <ion-icon name='alert-circle' class='text-blue-500 mr-2'></ion-icon>
                                                    Hai $userName, tugasmu <a style='color:red'>$task_name</a> telah melewati batas penyelesaian tugas
                                                </div>
                                            </div>";
                                        }
                                    } elseif ($notification['type'] === 'task') {
                                        $task_name = $notification['message'];

                                        echo "<div class='dropdown-item border border-b-2 border-gray-400 mb-1 p-2 rounded-lg'>
                                            <div class='font-bold text-sm mb-1 text-gray-800'>
                                                <ion-icon name='calendar' class='text-green-500 mr-2'></ion-icon>
                                                Hai $userName, hari ini ada aktivitas <a style='color:red'>$task_name</a>, jangan lupa ya! 💪
                                            </div>
                                        </div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <a href=""
                            class="flex justify-center items-center w-12 h-12 rounded-full ">
                            <button id="fullscreen-button">
                                <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="28"
                                class="hover:bg-gray-100 rounded-full font-bold"
                                viewBox="0 0 24 24"
                                style="fill: #5400DE ; transform: scale() ; ";
                                >
                                <path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"></path>
                                </svg>
                            </button>
                            <script>
                                const fullscreenButton = document.getElementById("fullscreen-button");

                                fullscreenButton.addEventListener("click", toggleFullscreen);

                                function toggleFullscreen() {
                                    const elem = document.documentElement;
                                    if (document.fullscreenEnabled) {
                                        if (!document.fullscreenElement) {
                                            elem.requestFullscreen().catch(err => {
                                                console.error("Error attempting to enable full-screen mode:", err.message);
                                            });
                                        } else {
                                            document.exitFullscreen();
                                        }
                                    } else {
                                        console.error("Fullscreen mode is not supported by this browser.");
                                    }
                                }
                            </script>
                        </a>
                    </div>
                    <div class="flex h-12 border-x border-[0.5px] border-taskia-background-grey"></div>
                    <div class="flex gap-4 items-center">
                        <ion-icon name="moon" class="text-2xl text-[#5400DE]"></ion-icon>
                        <div class="w-12 h-12 rounded-full overflow-hidden">  
                            <img src="../public/img/profil.jpg" class="object-cover h-full w-full" alt="photo">
                        </div>
                        <div class="*:text-left flex flex-col">
                            <p class="text-taskia-grey text-lg w-full text-[#5400DE] font-bold"><?php echo $userName; ?></span>
                            <p class="text-xs text-[#818181]"><?php echo $userEmail; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End navbar -->
            <!-- Open Card total task -->
            <div class="flex flex-col gap-[30px]">   
                <div class="flex flex-col gap-6" id="taskWrapper">
                    <div class="flex justify-between gap-4  ">
                        <div class="bg-white w-72 h-44 rounded-xl animate__animated animate__fadeIn ">
                            <div class="px-8 py-6">
                                <h1 class="text-6xl font-bold"><?php echo getTotalTasks($conn, $user_id); ?></h1>
                                <p class="text-base font-semibold text-gray-400">Total Task</p>
                                <p class="text-base font-semibold text-red-400 mt-8">View</p>
                            </div>  
                        </div>
                        <div class="bg-white w-72 h-44 rounded-xl animate__animated animate__fadeIn z-0">
                            <div class="px-8 py-6">
                                <h1 class="text-6xl font-bold"><?php echo getTotalTodo($conn, $user_id); ?></h1>
                                <p class="text-base font-semibold text-gray-400">Total Todo</p>
                                <p class="text-base font-semibold text-red-400 mt-8">View</p> 
                            </div>     
                        </div>
                        <div class="bg-white w-72 h-44 rounded-xl animate__animated animate__fadeIn ">
                            <div class="px-8 py-6">
                                <h1 class="text-6xl font-bold"><?php echo $notificationCount; ?></h1>
                                <p class="text-base font-semibold text-gray-400">Total inbox</p>
                                <p class="text-base font-semibold text-red-400 mt-8">View</p> 
                            </div>     
                        </div>
                    </div>    
                </div>      
            </div>
            <!-- End Card total task -->
            
            
            <div class="flex flex-col gap-[30px]">
                <div class="content-header flex justify-between items-center">
                    <div class="flex gap-3 items-center">
                        <h1 class="font-bold text-2xl text-slate-700 animate__animated animate__fadeIn">Project Progres</h1>
                    </div>
                    <div>
                        <h1 class="font-bold text-2xl text-slate-700 animate__animated animate__fadeIn">Kalender</h1>
                    </div>
                </div>
            </div>
            <!-- Open Table Task & Kalender -->
            <div class="flex flex-col gap-[20px]">
                <div class="content-header flex justify-between">
                    <!-- Open Table Task -->
                    <table class="w-[600px] text-left  text-gray-500  rounded-lg animate__animated animate__fadeIn">
                        <thead class="text-base text-gray-700 uppercase bg-gray-50 "> 
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Tugas</th>
                                <th scope="col" class="px-6 py-3">Progres</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tasks as $index => $task) : ?>
                            <tr class="bg-white border-b  hover:bg-gray-50 ">
                                <th class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap "><?= $index + 1 ?></th>
                                <td class="px-6 py-2 ">
                                    <span class="text-base text-[#252525]"><?= $task['task_name'] ?></span>
                                    <p class="text-xs text-[#808080]">sampai <?= $task['end_time'] ?></p>
                                </td>
                                <td class="px-3 py-2">
                                    <div id="progressBar<?= $task['id'] ?>" class="w-40 bg-purple-400 p-2 rounded-xl">
                                        <div id="progress<?= $task['id'] ?>" class="w-0 bg-purple-600 h-full"></div>
                                    </div>
                                    <p id="progressText<?= $task['id'] ?>" class="text-xs mt-1 text-[#808080]">0% Complete</p>
                                </td>
                                <td class="px-6 py-2 "><span class="bg-cyan-500 px-2 py-2 rounded-lg text-white p-1 text-xs w-15"><?= $task['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table -->
                    <!-- Open Kalender -->
                    <div class="w-80 h-96 bg-white rounded-xl shadow-xl animate__animated animate__fadeIn">
                        <div class="flex justify-between p-5">
                            <h1 class="font-bold text-xl"> <?php echo date('F Y'); ?></h1>
                            <div class="flex gap-3">
                                <div class="w-[25px] h-[25px] rounded-full bg-cyan-400"></div>
                                <div class="w-[25px] h-[25px] rounded-full bg-cyan-400"></div>
                            </div>
                        </div>
                        <div class="p-2">
                            <table class="w-full text-sm text-gray-500 " cellpadding="5px">
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
                        <!-- End Kalender -->
                    </div> 
                </div>
            </div>
        </div>
        <!-- End Table Task & Kalender -->
    </section> 

    <script>
        function getCurrentTime() {
        const now = new Date();
        const day = now.getDate();
        const month = now.getMonth() + 1;
        const year = now.getFullYear();

        return `${day < 10 ? '0' + day : day} ${getMonthName(month)} ${year}`;
    }

    // Fungsi untuk mendapatkan nama bulan
    function getMonthName(month) {
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return months[month - 1];
    }

    // Fungsi untuk memperbarui waktu secara periodik
    function updateCurrentTime() {
        const currentDateElement = document.getElementById('date');
        if (currentDateElement) {
            currentDateElement.textContent = getCurrentTime();
        }
    }

    // Memanggil fungsi untuk memperbarui waktu saat ini setiap detik
    setInterval(updateCurrentTime, 1000);

    // Memanggil fungsi pertama kali saat halaman dimuat
    updateCurrentTime();

    // Fungsi untuk mengambil data progres dari mytask.php
    function updateProgress(taskId, status) {
            fetch(`mytask.php?taskId=${taskId}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    const progressBar = document.getElementById(`progressBar${taskId}`);
                    const progress = document.getElementById(`progress${taskId}`);
                    const progressText = document.getElementById(`progressText${taskId}`);

                    // Update lebar progres bar
                    progressBar.style.width = `${data.progress}%`;

                    // Update teks progres
                    progressText.textContent = `${data.progress}% Complete`;
                })
                .catch(error => console.error('Error updating progress:', error));
        }

        // Panggil fungsi updateProgress untuk setiap tugas saat halaman dimuat
        document.addEventListener("DOMContentLoaded", () => {
            <?php foreach ($tasks as $task): ?>
                updateProgress(<?= $task['id'] ?>, '<?= $task['status'] ?>');
            <?php endforeach; ?>
        });

        function updateProgressBar(taskId, newStatus) {
        // Kirim permintaan Ajax ke mytask.php dengan data taskId dan newStatus
        fetch('mytask.php?id=' + taskId + '&status=' + newStatus)
            .then(response => response.json())
            .then(data => {
                // Perbarui progres bar sesuai dengan nilai progres yang diberikan dalam respons JSON
                const progressBar = document.getElementById('progressBar' + taskId);
                const progressText = document.getElementById('progressText' + taskId);
                const progressWidth = data.progress + '%';
                progressBar.style.width = progressWidth;
                progressText.textContent = progressWidth + ' Complete';
            })
            .catch(error => console.error('Error:', error));
    }
    document.getElementById('notificationButton').addEventListener('click', function() {
            var dropdown = document.getElementById('notificationDropdown');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        });

        // Optional: Close dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.closest('#notificationButton')) {
                var dropdown = document.getElementById('notificationDropdown');
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        };

        // Display current date
        document.getElementById('date').textContent = new Date().toLocaleDateString();

        document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault();
        
        // Clear cookies
        function clearCookies() {
            document.cookie.split(";").forEach(function(c) { 
                document.cookie = c.trim().split("=")[0] + 
                                  '=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/'; 
            });
        }
        
        clearCookies();
        
        // Redirect to login page
        window.location.href = 'index.php';
    });

    </script>
    
</body>
</html>