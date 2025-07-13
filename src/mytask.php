<?php
session_start();

include 'koneksi.php'; // Sertakan file koneksi ke database

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
// Fungsi untuk mendapatkan daftar tugas pengguna yang login
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


// Fungsi untuk menambahkan tugas baru
function addTask($conn, $user_id, $task_name, $start_time, $end_time, $status, $description) {
    $query = "INSERT INTO task_manager (user_id, task_name, start_time, end_time, status, description) VALUES ('$user_id', '$task_name', '$start_time', '$end_time', '$status', '$description')";
    return mysqli_query($conn, $query);
}

// Ambil user_id dari sesi


// Jika ada permintaan untuk menghitung progres
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['taskId']) && isset($_GET['status'])) {
    // Ambil data dari permintaan
    $taskId = $_GET['id'];
    $status = $_GET['status'];

    // Logika untuk menghitung nilai progres berdasarkan status
    if ($status === 'Not started') {
        $progress = 0;
    } elseif ($status === 'On Progres') {
        $progress = 50;
    } elseif ($status === 'Done') {
        $progress = 100;
    } else {
        $progress = 0; // Nilai default jika status tidak valid
    }

    // Kembalikan nilai progres dalam format JSON
    echo json_encode(['progress' => $progress]);
    exit; // Keluar dari skrip setelah mengirimkan respons JSON
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];
// Jika ada permintaan untuk menambahkan tugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_name'])) {
    // Ambil data dari formulir
    $task_name = $_POST['task_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    // Tambahkan tugas baru ke database
    if (addTask($conn, $user_id, $task_name, $start_time, $end_time, $status, $description)) {
        echo "<script>alert('Tugas berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan tugas');</script>";
    }
}
// Ambil daftar tugas pengguna yang login
$tasks = getTasks($conn, $user_id);

// Fungsi untuk mendapatkan jumlah total tugas pengguna yang login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard-task</title>
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
    <link rel="stylesheet" href="../src/output.css" />
</head>
<style>
            .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
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

<body class="font-[Poppins] bg-slate-200 ">
    <section class="flex ">
        <!-- Sidebar -->
        <div id="Sidebar"
            class="w-[240px] flex flex-col gap-[30px] p-[30px] shrink-0 h-screen  bg-white rounded-xl mt-10 my-10 ml-8">
            <!-- Konten Sidebar -->
            <div class="flex justify-center items-center">
                <img src="../public/img/Listify.svg" class="-ml-3" alt="logo">
            </div>
            <div class="general-menu flex flex-col gap-[18px]">
                <h3 class="font-semibold text-base leading-[21px] text-[#C2BEBE]">Main</h3>
                <a href="home.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 15 15" class="text-2xl"><path fill="currentColor" d="M7.825.12a.5.5 0 0 0-.65 0L0 6.27v7.23A1.5 1.5 0 0 0 1.5 15h4a.5.5 0 0 0 .5-.5v-3a1.5 1.5 0 0 1 3 0v3a.5.5 0 0 0 .5.5h4a1.5 1.5 0 0 0 1.5-1.5V6.27z"/></svg>
                    </div>
                    <p class="font-semibold">Home</p>
                </a>
                <a href="mytask.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl bg-[#6E15FF] drop-shadow-[0_10px_20px_rgba(84,0,222,100)] text-white">
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
            <a href="index.php" 
                
                class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                <div class="w-6 h-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl">
                        <path fill="currentColor" d="M12 3.25a.75.75 0 0 1 0 1.5a7.25 7.25 0 0 0 0 14.5a.75.75 0 0 1 0 1.5a8.75 8.75 0 1 1 0-17.5"/>
                        <path fill="currentColor" d="M16.47 9.53a.75.75 0 0 1 1.06-1.06l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H10a.75.75 0 0 1 0-1.5h8.19z"/>
                    </svg>
                </div>
                <p class="font-semibold">Log Out</p>
            </a>
            <!-- Tambahkan konten sidebar sesuai kebutuhan -->
        </div>
        <!-- Konten Utama -->
        <div id="Content"
            class="flex flex-col bg-taskia-background-grey rounded-l-[60px] w-full max-h-screen overflow-y-scroll p-[50px] gap-[30px]">
            <div class="dashboard-nav bg-white flex justify-between items-center w-full -mt-2 p-4 rounded-[18px] animate__animated animate__fadeIn">
                <div class=" flex flex-col  ml-8">
                <h1 class="text-left font-bold text-[#5400DE]">My Task</h1>
                <p class="text-left text-[#5B5B5B] font-semibold">Today. <?php echo date("d M Y"); ?></p>
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

            <!-- Konten Dashboard -->
            <div class="flex flex-col gap-[30px]">
                <!-- Daftar Tugas -->
                <div class="content-header flex justify-between items-center">
                    <div class="flex gap-3 items-center">
                        <button id="modalButton" onclick="openModal()"
                            class="bg-[#6E15FF] hover:bg-sky-400 text-white font-bold text-base py-3 px-4 rounded-lg m-4 animate__animated animate__fadeIn">
                            Add task
                        </button>
                    </div>
                    <div>
                        <form action="" method="POST" class="animate__animated animate__fadeIn">
                            <input type="search" name="cari" id="searchInput" placeholder="search...."
                                class="p-3 w-56 rounded-xl active:ring-cyan-300">
                        </form>
                    </div>
                </div>

                <!-- Tabel Daftar Tugas -->
                <div class="flex flex-col gap-[30px]">
                    <table id="taskTable"
                        class="w-full text-base text-left rtl:text-right text-gray-700  rounded-lg animate__animated animate__fadeIn">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 ">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Tugas</th>
                                <th scope="col" class="px-6 py-3">Mulai</th>
                                <th scope="col" class="px-6 py-3">Tenggat</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                             
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Tampilkan daftar tugas menggunakan PHP -->
                            <?php foreach ($tasks as $index => $task) : ?>
                            <tr class="bg-white border-b  hover:bg-gray-50 ">
                                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap "><?= $index + 1 ?></th>
                                <td class="px-6 py-4"><?= $task['task_name'] ?></td>
                                <td class="px-6 py-4"><?= $task['start_time'] ?></td>
                                <td class="px-6 py-4"><?= $task['end_time'] ?></td>
                                <td class="px-6 py-4">
                                <select onchange="updateStatus(<?= $task['id'] ?>, this.value)">
                                    <option value="Not started" <?= $task['status'] == 'Not started' ? 'selected' : '' ?>>Not started</option>
                                    <option value="On Progres" <?= $task['status'] == 'On Progres' ? 'selected' : '' ?>>On Progres</option>
                                    <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                                </select>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <a href="mytask_edit.php? id=<?= $task['id']?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline edit-task" ><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="text-2xl hover:text-green-500" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3"/></g></svg></a>
                                        
                                        <a href="hapustask.php? id=<?= $task['id']?>"class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="text-2xl hover:text-red-500" viewBox="0 0 24 24"><path fill="currentColor" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zM19 4h-3.5l-1-1h-5l-1 1H5v2h14z"/></svg></a>
                                    </div>
                                    
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="modalContent" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 transition-all">
        <!-- Konten Modal -->
        <div class="w-full h-screen flex justify-center items-center">
                    <div class="w-[800px] h-[500px] bg-white rounded-xl shadow-xl px-5 py-3 overflow-y-auto ">
                        <!-- Modal content -->
                        <div class="modal-content py-4 text-left px-6">
                            <!-- Title -->
                            <div class=" w-[30px] h-[30px] rounded-full bg-purple-500 flex justify-center items-center -ml-12 -mt-9 xl:fixed">
                                <button onclick="closeModal()" class="modal-close cursor-pointer z-50 ">
                                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                        <path
                                            d="M13.06 4.94a.75.75 0 011.06 1.06L11.06 9l3.06 3.06a.75.75 0 11-1.06 1.06L10 10.06l-3.06 3.06a.75.75 0 11-1.06-1.06L8.94 9 5.88 5.94a.75.75 0 011.06-1.06L10 7.06l3.06-3.06z" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Form -->
                            <div class="m-8">
                                <h1 class="font-bold text-2xl">Tambahkan Tugasmu</h1>
                                <p class="text-sm">Lorem ipsum dolor sit amet consectetur. Metus proin ultrices erat sed ipsum condimentum ac.</p>
                            </div>
                            
                            <form class="rounded-lg border px-5 py-4" action="" method="post">
                                <div class="flex justify-around">
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="task_name">
                                                Nama Tugas
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="task_name" name="task_name" type="text" placeholder="Masukkan Nama Tugas" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_time">
                                                Waktu Mulai
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_time" name="start_time" type="date" required>
                                        </div> 
                                    </div>
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                                Status
                                            </label>
                                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                                <option selected disabled>Pilih Status</option>
                                                <option value="Not started">Not started</option> 
                                                <option value="On Progres">On Progres</option>
                                                <option value="Done">Done</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="end_time">
                                                Waktu Berakhir
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_time" name="end_time" type="date" required>
                                        </div>
                                    </div>
                                </div>
                                <hr class="w-full mx-auto mb-2">
                                <div class="flex justify-center items-center flex-col">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                            Deskripsi
                                        </label>
                                        <textarea class="shadow appearance-none border rounded w-[650px] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Masukkan Deskripsi"></textarea>
                                    </div>
                                    <div class="">
                                        <button class="bg-gradient-to-r from-[#5505D9] to-[#5400DE] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-[650px] focus:outline-none focus:shadow-outline shadow-lg shadow-[#A245FF]" type="submit">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>

    <!-- EDIT MODAL -->
        <div id="editModal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 ">
        <!-- Konten Modal -->
        <div class="w-full h-screen flex justify-center items-center">
                    <div class="w-[800px] h-[500px] bg-white rounded-xl shadow-xl px-5 py-3 overflow-y-auto ">
                        <!-- Modal content -->
                        <div class="modal-content py-4 text-left px-6">
                            <!-- Title -->
                            <div class=" w-[30px] h-[30px] rounded-full bg-purple-500 flex justify-center items-center -ml-12 -mt-9 xl:fixed">
                                <button onclick="closeEditModal()" class="modal-close cursor-pointer z-50 ">
                                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                        <path
                                            d="M13.06 4.94a.75.75 0 011.06 1.06L11.06 9l3.06 3.06a.75.75 0 11-1.06 1.06L10 10.06l-3.06 3.06a.75.75 0 11-1.06-1.06L8.94 9 5.88 5.94a.75.75 0 011.06-1.06L10 7.06l3.06-3.06z" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Form -->
                            <div class="m-8">
                                <h1 class="font-bold text-2xl">Edit Tugasmu</h1>
                                <p class="text-sm">Lorem ipsum dolor sit amet consectetur. Metus proin ultrices erat sed ipsum condimentum ac.</p>
                            </div>
                            <?php  
                                $id_user = $_GET['id'];
                                $query = "SELECT * FROM task_manager WHERE id = '$id_user'";
                                $sql = mysqli_query($conn, $query);

                                foreach ($sql as $tampil) {
                                ?> 
                            <form class="rounded-lg border px-5 py-4" action="update_mytask.php" method="post">
                                <input type="hidden" name="id"  value="<?= $tampil['id']?>">
                                <div class="flex justify-around">
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="task_name">
                                                Nama Tugas
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $tampil['task_name']?>" id="task_name" name="task_name" type="text" placeholder="Masukkan Nama Tugas" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_time">
                                                Waktu Mulai
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $tampil['start_time']?>" id="start_time" name="start_time" type="datetime-local" required>
                                        </div> 
                                    </div>
                                    <div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                                Status
                                            </label>
                                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= $tampil['status']?>" required>
                                                <option selected disabled>Pilih Status</option>
                                                <option <?php if($tampil['status'] == 'Not started') {echo'selected';} ?>  value="Not started">Not started</option> 
                                                <option <?php if($tampil['status'] == 'On Progres') {echo'selected';} ?>  value="On Progres">On Progres</option>
                                                <option <?php if($tampil['status'] == 'Done') {echo'selected';} ?>  value="Done">Done</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="end_time">
                                                Waktu Berakhir
                                            </label>
                                            <input class="shadow appearance-none border rounded w-72  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_time" name="end_time" value="<?= $tampil['end_time']?>" type="datetime-local" required>
                                        </div>
                                    </div>
                                </div>
                                <hr class="w-full mx-auto mb-2">
                                <div class="flex justify-center items-center flex-col">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                            Deskripsi
                                        </label>
                                        <textarea class="shadow appearance-none border rounded w-[650px] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description"  placeholder="Masukkan Deskripsi"></textarea>
                                    </div>
                                    <div class="">
                                        <button class="bg-gradient-to-r from-[#5505D9] to-[#5400DE] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-[650px] focus:outline-none focus:shadow-outline shadow-lg shadow-[#A245FF]" type="submit">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
    </div>

    <script>
    function openModal() {
        var modal = document.getElementById('modalContent');
        modal.classList.remove('hidden');
    }
    function closeModal() {
        var modal = document.getElementById('modalContent');
        modal.classList.add('hidden');
    }
    // Edit Modal
    function openEditModal() {
        var modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
    }
    function closeEditModal() {
        var modal = document.getElementById('editModal');
        modal.classList.add('hidden');
    }

    // Panggil fungsi openModal() saat tombol modal diklik
    var modalButton = document.getElementById('modalButton');
    modalButton.addEventListener('click', openModal);

    var modalButton = document.getElementById('EditmodalButton');
    modalButton.addEventListener('click', openEditModal);

    function openEditModal(event) {
    event.preventDefault(); // Mencegah aksi default dari tautan
    var modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
}

    function updateStatus(taskId, newStatus) {
        // Lakukan kueri atau panggil API untuk memperbarui status tugas di database
        // Setelah berhasil, Anda bisa mengubah tampilan secara dinamis
        // Contoh: Setelah berhasil diperbarui di database, ubah warna latar belakang opsi dropdown yang terpilih
        var selectedOption = document.querySelector('select option:checked');
        selectedOption.style.backgroundColor = getBackgroundColor(newStatus);
    }
    // Fungsi untuk mendapatkan warna latar belakang berdasarkan status
    function getBackgroundColor(status) {
        switch (status) {
            case 'Belum di mulai':
                return 'red';
            case 'On Progres':
                return 'blue';
            case 'Selesai':
                return 'green';
            default:
                return '';
        }
    }
    function updateStatus(taskId, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText;
                console.log(response);
                // Jika berhasil, perbarui tampilan
                if (response === "success") {
                    var statusElement = document.getElementById("status_" + taskId);
                    statusElement.textContent = newStatus;
                    // Sesuaikan warna latar belakang sesuai dengan status
                    switch (newStatus) {
                        case "Belum di mulai":
                            statusElement.style.backgroundColor = "#FF6347"; // Merah
                            break;
                        case "On Progres":
                            statusElement.style.backgroundColor = "#4169E1"; // Biru
                            break;
                        case "Selesai":
                            statusElement.style.backgroundColor = "#32CD32"; // Hijau
                            break;
                        default:
                            break;
                    }
                }
            }
        };
        xhr.send("taskId=" + taskId + "&newStatus=" + newStatus);
    }
    // mengambil waktu secara realtime
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

    // 
    
    document.addEventListener("DOMContentLoaded", function() {
        var searchInput = document.querySelector("input[name='cari']");

        if (searchInput) {
            searchInput.addEventListener("input", function() {
                var keyword = this.value.trim().toLowerCase();
                var rows = document.querySelectorAll("#taskTable tbody tr");

                rows.forEach(function(row) {
                    var found = false;
                    row.querySelectorAll("td").forEach(function(cell) {
                        if (cell.textContent.toLowerCase().includes(keyword)) {
                            found = true;
                        }
                    });
                    if (found) {
                        row.style.display = ""; // Tampilkan baris jika cocok dengan pencarian
                    } else {
                        row.style.display = "none"; // Sembunyikan baris jika tidak cocok
                    }
                });
            });
        }
    });

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
        // Redirect to the logout page which handles the logout process
        window.location.href = 'logout.php';
    });




    </script>
</body>
</html>
