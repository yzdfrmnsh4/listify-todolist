<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<body class="font-[Poppins] bg-slate-100">
    <div class="w-full h-screen flex justify-center items-center">
        <div class="w-[800px] h-[520px] bg-white rounded-xl shadow-2xl flex animate__animated animate__fadeIn ">
            <!-- <div class="flex"> -->
                <div class="w-[50%] h-full  px-5 py-5">
                    <form action="aksi_login.php" method="POST" class="px-7 ">
                        <h1 class="text-3xl font-medium mb-1 mx-1">Welcome </h1>
                        <h1 class="text-3xl font-bold mb-3 mx-1 bg-gradient-to-r from-black via-purple-400 to-purple-600 bg-clip-text">To Listify!</h1>
                        <p class="text-xs font-medium text-slate-400 mb-4 mx-1">Login into your account use To-doApp</p>
                        <label for="email" class="" >
                            <span class="font-semibold mx-1 my-4 after:content-['*'] after:text-pink-500">Email</span>
                            <input type="email" name="email" id="email" placeholder="Masukan email" class="w-full px-3 py-2 text-sm border shadow rounded-md  mt-2 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-sky-400 focus:border-sky-400 invalid:text-pink-500 invalid:focus:ring-pink-700 invalid:focus:border-pink-700 peer">
                            <p class="text-sm m-1 text-pink-700 invisible peer-invalid:visible">Email tidak valid</p>
                        </label>
                        <label for="password">
                            <span class="font-semibold mx-1 my-4 after:content-['*'] after:text-pink-500">Password</span>
                            <input type="password" name="password" id="password" placeholder="Masukan password" class="px-3 py-2 text-sm border shadow rounded-md w-full mt-2 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-sky-400 focus:border-sky-400 invalid:text-pink-500 invalid:focus:ring-pink-700 invalid:focus:border-pink-700 peer">
                                <!-- Icon untuk menampilkan dan menyembunyikan password -->
                            <p class="text-right text-slate-400 mt-2 mb-2 text-xs font-medium"><a href="http://">forgot your password?</a></p>
                        </label>
                        <button class="w-full bg-[#6E15FF] text-slate-200 font-semibold py-2 rounded-md">Login</a></button>
                        <p class="text-xs mt-2 font-medium mb-24">Don't have account?<span class="mx-1 text-purple-600"><a href="register.php">Create here</a></span></p>
                        <p class="text-center text-xs text-black font-medium -mt-10">&copy; Copyright by To-doAppUI. All rights reserved.</p>
                    </form>   
                </div>
                <div class="w-[50%] bg-orange-500  h-full rounded-tr-xl rounded-br-xl">
                    <img src="../img/animasi.svg" alt="animasi" class="w-full h-full rounded-tr-xl rounded-br-xl">
                </div>
        </div>
    </div>
    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    });
</script>
</body>
</html>