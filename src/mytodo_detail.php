<?php
include 'koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$userName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''; // Menggunakan operator ternary untuk memeriksa apakah session ada
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Menggunakan operator ternary untuk memeriksa apakah session ada
$user_id = $_SESSION['user_id'];

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

// Function to handle insertion of a new sub-task
function insertSubTask($conn, $task_id, $user_id, $item_name) {
    $is_completed = 0;
    $stmt = $conn->prepare("INSERT INTO task_items (task_id, user_id, item_name, is_completed) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $task_id, $user_id, $item_name, $is_completed);
    return $stmt->execute();
}

// Function to handle updating the completion status of a sub-task
function updateSubTask($conn, $item_id, $is_completed) {
    $stmt = $conn->prepare("UPDATE task_items SET is_completed = ? WHERE item_id = ?");
    $stmt->bind_param("ii", $is_completed, $item_id);
    return $stmt->execute();
}

// Function to handle deletion of a sub-task
function deleteSubTask($conn, $item_id) {
    $stmt = $conn->prepare("DELETE FROM task_items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    return $stmt->execute();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'insert') {
        $task_id = $_POST['task_id'];
        $user_id = $_POST['user_id'];
        $item_name = $_POST['item_name'];
        if (insertSubTask($conn, $task_id, $user_id, $item_name)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($action == 'update') {
        $item_id = $_POST['item_id'];
        $is_completed = $_POST['is_completed'];
        if (updateSubTask($conn, $item_id, $is_completed)) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        $item_id = $_POST['item_id'];
        if (deleteSubTask($conn, $item_id)) {
            echo "Record deleted successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Query untuk mendapatkan semua task dari pengguna yang login
$sql = "SELECT * FROM task WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard-todo</title>
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
    /* .modal {
        transform: translate(-50%, -50%);
    } */

    .add-task-button {
        position: fixed;
        top: 20px;
        left: 20px;
    }

    .task {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    
    .modal.active {
        display: flex;
    }
</style>
<body class="font-[Poppins] bg-slate-200">
    <section class="flex ">
        <div id="Sidebar"
            class="w-[240px] flex flex-col gap-[30px] p-[30px] shrink-0 h-screen  bg-white rounded-xl mt-10 my-10 ml-8">
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
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0zM12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036c-.01-.003-.019 0-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M15 2a2 2 0 0 1 1.732 1H18a2 2 0 0 1 2 2v12a5 5 0 0 1-5 5H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.268A2 2 0 0 1 9 2zm-.176 7.379l-4.242 4.243l-1.415-1.415a1 1 0 0 0-1.414 1.414l2.05 2.051a1.1 1.1 0 0 0 1.556 0l4.88-4.879a1 1 0 1 0-1.415-1.414M14.5 4h-5a.5.5 0 0 0-.492.41L9 4.5v1a.5.5 0 0 0 .41.492L9.5 6h5a.5.5 0 0 0 .492-.41L15 5.5v-1a.5.5 0 0 0-.41-.492z"/></g></svg>
                    </div>
                    <p class="font-semibold">My Task</p>
                </a>
                <a href="mytodo.php"
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl bg-[#6E15FF] drop-shadow-[0_10px_20px_rgba(84,0,222,100)] text-white">
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
                    class="flex items-center gap-[10px] p-[12px_16px] h-12 rounded-xl border border-taskia-background-grey mt-16">
                    <div class="w-6 h-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="text-2xl"><path fill="currentColor" d="M12 3.25a.75.75 0 0 1 0 1.5a7.25 7.25 0 0 0 0 14.5a.75.75 0 0 1 0 1.5a8.75 8.75 0 1 1 0-17.5"/><path fill="currentColor" d="M16.47 9.53a.75.75 0 0 1 1.06-1.06l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H10a.75.75 0 0 1 0-1.5h8.19z"/></svg>
                    </div>
                    <p class="font-semibold">Log Out</p>
                </a>
        </div>

        <div id="Content"
        class="flex flex-col bg-taskia-background-grey rounded-l-[60px] w-full max-h-screen overflow-y-scroll p-[50px] gap-[30px]">
        <div class="dashboard-nav bg-white flex justify-between items-center w-full -mt-2 p-4 rounded-[18px] animate__animated animate__fadeIn">
                <div class=" flex flex-col  ml-8">
                <h1 class="text-left font-bold text-[#5400DE]">My Todo</h1>
                <p class="text-left text-[#5B5B5B] font-semibold">Today. <span id="date"></span></p>
                </div>
                <div class="flex gap-[30px] items-center">
                    <div class="flex gap-3 items-center">
                    <button class="relative p-2 text-gray-400 flex justify-center items-center w-12 h-12  hover:bg-gray-100 hover:text-gray-600 focus:bg-gray-100 focus:text-gray-600 rounded-full">
                        <span class="sr-only">Notifications</span>
                        <span class="absolute top-0 right-0 h-2 w-2 mt-1 mr-2 bg-red-500 rounded-full"></span>
                        <span class="absolute top-0 right-0 h-2 w-2 mt-1 mr-2 bg-red-500 rounded-full animate-ping"></span>
                        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-[#5400DE]">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
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
            

            <div class="flex flex-col gap-[30px]">
                <div class="content-header flex justify-between items-center">
                    <div class="flex gap-3 items-center">
                        <button onclick="openModal()" class="bg-[#6E15FF] hover:bg-sky-400 text-white  text-base font-bold py-3 px-4 rounded-lg m-4 animate__animated animate__fadeIn">
                            Add Task
                        </button>
                    </div>
                    <div>
                    <form id="searchForm" action="" method="POST" class="animate__animated animate__fadeIn">
                        <input type="search" name="cari" id="searchInput" placeholder="search...." class="p-3 w-56 rounded-xl active:ring-cyan-300">
                    </form>
                    </div>
                    
                </div>
            </div>
           

            <div class="">
                <div id="modal" class="modal  fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
                    <div class="w-full h-screen flex justify-center items-center">
                        <div class=" bg-white border border-slate-100 px-12 py-8 w-[800px] h-[500px] z-[100] top-[300px] left-1/2 shadow-lg rounded-lg animate__animated animate__fadeIn ">
                        <div class=" w-[30px] h-[30px] rounded-full bg-purple-500 flex justify-center items-center -ml-14 -mt-9 xl:fixed">
                                <button onclick="closeEditModal()" class="modal-close cursor-pointer z-50 ">
                                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                        <path
                                            d="M13.06 4.94a.75.75 0 011.06 1.06L11.06 9l3.06 3.06a.75.75 0 11-1.06 1.06L10 10.06l-3.06 3.06a.75.75 0 11-1.06-1.06L8.94 9 5.88 5.94a.75.75 0 011.06-1.06L10 7.06l3.06-3.06z" />
                                    </svg>
                                </button>
                            </div>
                            <?php  
                                $id_user = $_GET['id'];
                                $query = "SELECT * FROM task WHERE task_id = '$id_user'";
                                $sql = mysqli_query($conn, $query);

                                foreach ($sql as $tampil) {
                            ?> 
                            <h1 class="text-3xl font-bold mb-2"><?php echo $tampil['task_name']; ?></h1>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-400 font-medium"><?php echo $tampil['task_description']; ?></p> 
                                <p class="text-sm text-gray-700 font-medium"><?php echo $tampil['tanggal']; ?></p>
                            </div>
                            <hr class="bg-black mb-5 mt-3">
                            <!-- <div class=" "> -->
                                
                                <?php
                                }
                                ?>
                                <div class="" id="subTodoList">
                                <?php
                                    $query = "SELECT * FROM task_items WHERE user_id = {$_SESSION['user_id']} AND task_id = {$id_user}";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <div class="flex items-center  mb-3">
                                        <input type="checkbox" class="transform scale-150" <?php echo $row['is_completed'] ? 'checked' : ''; ?> onchange="toggleCompleted(this, <?php echo $row['item_id']; ?>)">
                                        <p class="font-medium ml-4 <?php echo $row['is_completed'] ? 'line-through' : ''; ?>"><?php echo htmlspecialchars($row['item_name']); ?></p>
                                        <button onclick="removeSubTodo(<?php echo $row['item_id']; ?>)" class="ml-4 text-red-500 hover:text-red-700">Delete</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            
                                <!-- <div class="block bg-orange-400"> -->
                                    <div id="smallModal" class="smallmodal hidden fixed z-50 p-0 mt-7 overflow-auto">
                                        <!-- <div class="w-full h-screen flex justify-center items-center bg-blue-500"> -->
                                            <div class="bg-slate-100 border border-slate-100 px-5 py-3 shadow-2xl w-[300px] z-[100] mt-0 rounded-lg  ">
                                                <div class="flex justify-end items-center">
                                                    <!-- <h2 class="text-xl font-bold mb-4">Tambah Todo</h2> -->
                                                    <button onclick="closeSmallModal()" class="modal-close cursor-pointer z-50  mb-4 ">❌</button>
                                                </div>
                                                <form id="subTodoForm">
                                                    <input type="hidden" name="action" value="insert">
                                                    <input type="hidden" name="task_id" value="<?php echo $id_user; ?>"> <!-- Adjust as necessary -->
                                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- Adjust as necessary -->
                                                    <div class="mb-4">
                                                        <input type="text" name="item_name" placeholder="Nama kegiatan" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="submitSubTodoForm()" class="bg-[#5400DE]  w-full text-white font-bold py-2 px-4 rounded">Tambah</button>
                                                    </div>
                                                </form>
                                                
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <button onclick="showSmallModal()" class="flex items-center gap-2 cursor-pointer mt-3">
                                        <span class="text-2xl font-semibold text-[#6F6F6F]">+</span>
                                        <p class="font-semibold text-[#6F6F6F] group">Add Todo</p>
                                    </button>
                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                
                <ul id="todoList" class="mt-4 space-y-4">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <li class="p-4" >
                    <a href="mytodo_detail.php? id=<?= $task['id']?>">
                        <div class="w-full h-[100px] bg-white rounded-xl shadow-2xl flex items-center animate__animated animate__fadeIn">
                            <div class="flex items-center justify-between px-2 py-5 sm:px-6 w-full">
                                <div class="flex items-center">
                                    <div class="block">
                                        <div class="flex items-center">
                                            <h3 class="text-xl font-bold"><?php echo htmlspecialchars($row['task_name']); ?></h3>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500"><?php echo date("d M Y, H:i", strtotime($row['tanggal'])); ?></p>
                                            <!-- <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['task_description']); ?></p> -->
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <form method="POST" action="delete_todo.php">
                                        <input type="hidden" name="delete_task_id" value="<?php echo $row['task_id']; ?>">
                                        <button type="submit" class="text-red-500">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </a>
                    </li>
                <?php } ?>
                    <!-- Todo list items will be dynamically added here -->
                </ul>
            </div> 

            
            

    </section>
   <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }
        
        // Fungsi untuk menutup modal
        

        function closeEditModal() {
            window.location.href='mytodo.php';
        }

        document.getElementById('taskForm').addEventListener('submit', function(event) {
    event.preventDefault();
    this.submit();
});

    //     function submitForm() {
    //     // Ambil nilai dari input
    //     var taskName = document.getElementById('taskName').value;
    //     var taskDescription = document.getElementById('taskDescription').value;

    //     // Validasi jika nama tugas tidak kosong
    //     if(taskName.trim() !== '') {
    //         // Set nilai input pada form
    //         document.getElementById('taskForm').submit();
    //     } else {
    //         alert('Task name cannot be empty.');
    //     }
    // }

        // Fungsi untuk menandai tugas sebagai selesai dan mengubah gaya teks
        function toggleTaskStatus(checkbox, taskId) {
            if (checkbox.checked) {
                checkbox.nextElementSibling.classList.add('line-through', 'text-gray-500');
            } else {
                checkbox.nextElementSibling.classList.remove('line-through', 'text-gray-500');
            }

            // Tambahkan logika untuk menyimpan perubahan status ke server jika diperlukan
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

    document.getElementById("searchInput").addEventListener("input", function() {
        var keyword = this.value.trim();

        // Buat AJAX request untuk melakukan pencarian
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "search.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Bersihkan daftar tugas sebelum menambahkan hasil pencarian baru
                document.getElementById("todoList").innerHTML = xhr.responseText;
            }
        };
        xhr.send("cari=" + keyword);
    });

    function showSmallModal() {
            document.getElementById('smallModal').classList.remove('hidden');
        }

        function closeSmallModal() {
            document.getElementById('smallModal').classList.add('hidden');
        }

        

        function submitSubTodoForm() {
            const form = document.getElementById('subTodoForm');
            const formData = new FormData(form);

            fetch('mytodo_detail.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                window.location.reload(); // Refresh the page
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function toggleCompleted(checkbox, itemId) {
            const textElement = checkbox.nextElementSibling;
            const isCompleted = checkbox.checked ? 1 : 0;
            textElement.style.textDecoration = checkbox.checked ? "line-through" : "none";

            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('item_id', itemId);
            formData.append('is_completed', isCompleted);

            fetch('mytodo_detail.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function removeSubTodo(itemId) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('item_id', itemId);

            fetch('mytodo_detail.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                window.location.reload(); // Refresh the page
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }






    </script>
    
</body>
</html>