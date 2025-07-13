<!-- npx tailwindcss -i ./src/input.css -o ./src/output.css --watch -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Listify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../src/output.css" />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <style>
    * {
      scroll-behavior: smooth;
    }

    .animate-typing {
      display: inline-block;
      animation: typing-animation 0.5s linear;
    }

    /* Keyframes untuk animasi */
    @keyframes typing-animation {
      from {
        width: 0;
      }
      to {
        width: 1ch;
      }
}
  </style>
  <body class=" ">
    <!-- navbar -->
    <nav class="py-1  px-4 fixed -mt-5 z-40 w-full  animate__animated animate__fadeInDown ">
      <div class="mx-auto flex justify-between lg:justify-evenly items-center">
        <div class="">
          <img class="lg:w-40" src="../public/img/Listify.svg" alt="logo" />
        </div>
        <div
          class="nav-links md:static absolute md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 font-semibold"
        >
          <ul
            class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-6"
          >
            <li>
              <a class="hover:text-purple-400" href="#home">Home</a>
            </li>
            <li>
              <a class="hover:text-purple-400" href="#about">About</a>
            </li>
            <li>
              <a class="hover:text-purple-400" href="#program">Program</a>
            </li>
            <li>
              <a class="hover:text-purple-400" href="#Service">Service</a>
            </li>
          </ul>
        </div>
        <div class="flex items-center gap-3">
          <button
            class="bg-[#5400DE] px-2 py-3 text-sm rounded-md text-white font-semibold shadow-lg  w-20 mx-3 hidden md:block lg:block hover:bg-indigo-600 hover:shadow-indigo-500 transition-all"
          >
          <a href="register.php" class="">Sign Up</a>
            
          </button>
          <button
            class="bg-[#1BC8FF] px-2 py-3 text-sm rounded-md text-white font-semibold shadow-lg  w-20 mx-3 hidden md:block lg:block hover:bg-cyan-400 hover:shadow-cyan-300 transition-all"
          >
          <a href="login.php">Login</a>
            
          </button>
          <ion-icon
            onclick="onToggleMenu(this)"
            name="menu-outline"
            class="text-3xl cursor-pointer md:hidden lg:hidden xl:hidden"
          ></ion-icon>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <!-- Section 1 -->
    <section id="home">
      <div class="container mx-auto bg px-20 py-8 mt-4">
        <div class="grid grid-cols-12 md:justify-items-center">
          <div class="col-span-12 md:col-span-4 lg:col-span-4 order-1 ">
            <div class="mt-4 mb-4 md:mt-40 md:mb-40  h-96 ml-6">
              <h1
                class="text-center font-bold text-3xl md:text-4xl md:text-left lg:text-6xl lg:text-left animate__animated animate__fadeInUp " 
              >
                Atur daftar tugas dari Manapun
              </h1>
              <p
                class="text-sm font-semibold text-gray-700 text-center mt-2 md:text-base md:text-left md:mt-4 lg:text-left lg:mt-4 animate__animated animate__fadeInUp"
              >
                Temukan solusi untuk mengatur dan menyelesaikan tugas dengan efisien. 
                Bergabunglah sekarang!
              </p>
              <div class="flex justify-start items-start md:block  lg:block mt-6">
                <a
                  href="#"
                  class="w-32 lg:w-40 px-4 py-3 rounded-md font-bold bg-purple-700 text-white mt-4 mx-auto shadow-purple-600 shadow-lg cursor-pointer text-center animate__animated animate__fadeInUp "
                >
                  Ayo mulai</a
                >
              </div>
            </div>
          </div>
          <div
            class="col-span-12 md:col-span-8 lg:col-span-8 order-2 hidden md:ml-24 justify-items-center lg:block md:block lg:ml-64 "
          >
            <div class=" animate__animated animate__fadeInUp ">
              <img
                class="w-[664px] h-[664px]"
                src="../public/img/skyblie.svg"
                alt="..."
              />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 1 -->

    <!-- Quotes Section -->
    <section id="about" class="p-10">
      <div class="mx-auto px-10 py-4">
        <h1 class="text-center font-bold text-2xl text-[#5400DE] mb-3">
          Tujuan Kami
        </h1>
        <p class="text-center font-semibold xl:w-3/5 text-base md:w-3/4 mx-auto text-gray-500">
          <span id="animated-text">
           
          </span>
          
        </p>
      </div>
    </section>
    <!-- End Quotes Section -->

    <!-- Section 3 -->
    <section >
      <div class="w-full mx-auto bg px-20 py-8 mt-4">
        <div class="grid grid-cols-12 jus">
          <div class="col-span-12 md:col-span-4 lg:col-span-4 order-1">
            <div class=" md: mt-24 lg:mt-48">
              <h4
                class="font-semibold text-lg text-[#C985FF] uppercase mb-5 text-center md:text-left lg:text-left"
              >
                Global To-do list
              </h4>
              <h1
                class="font-bold text-3xl text-center mb-10 md:text-left  lg:text-5xl lg:text-left"
              >
                Kelola agenda milik Anda dari mana saja
              </h1>
              <hr
                class="bg-[#C985FF] p-1 rounded-xl w-56 mx-auto mb-8 lg:ml-0"
              />
              <p
                class="text-sm font-normal text-center text-[#9B9B9B] w-full md:text-left lg:text-left lg:text-base"
              >
                Lorem ipsum dolor sit amet consectetur. Libero sem sit
                ullamcorper massa faucibus sed lorem commodo. Tellus viverra in
                ac justo a auctor bibendum id.
              </p>
            </div>
          </div>
          <div
            class="col-span-12 md:col-span-8 lg:col-span-8 order-2 hidden lg:block md:block md:ml-32 lg:ml-64"
          >
            <div class="-mr-20">
              <img src="../public/rectangle/purple.svg" alt="" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 3 -->

    <!-- Section 4 -->
    <section>
      <div class="w-full mx-auto bg px-20 py-8 mt-4">
        <div class="grid grid-cols-12">
          <div
            class="col-span-12 md:col-span-8 lg:col-span-8 order-1 hidden lg:block md:block md:mr-32 lg:mr-64"
          >
            <div class="-ml-20">
              <img src="../public/rectangle/blue.svg" alt="" />
            </div>
          </div>
          <div class="col-span-12 md:col-span-4 lg:col-span-4 order-2">
            <div class=" md:mt-24 lg:mt-48">
              <h4
                class="font-semibold text-lg text-[#8BF8FF] uppercase mb-5 text-center md:text-left lg:text-left"
              >
                Global To-do list
              </h4>
              <h1
                class="font-bold text-3xl text-center mb-10 md:text-left lg:text-5xl lg:text-left"
              >
                Buat daftar sempurna untuk setiap kebutuhan.
              </h1>
              <hr
                class="bg-[#8BF8FF] p-1 rounded-xl w-56 mx-auto mb-8 lg:ml-0"
              />
              <p
                class="text-sm font-normal text-center text-[#9B9B9B] w-full md:text-left lg:text-left lg:text-base"
              >
                Lorem ipsum dolor sit amet consectetur. Libero sem sit
                ullamcorper massa faucibus sed lorem commodo. Tellus viverra in
                ac justo a auctor bibendum id.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 4 -->

    <!-- Section 5 -->
    <section>
      <div class="w-full mx-auto bg px-20 py-8 mt-4">
        <div class="grid grid-cols-12">
          <div class="col-span-12 md:col-span-4 lg:col-span-4 order-1">
            <div class="md:mt-24  lg:mt-48">
              <h4
                class="font-semibold text-lg text-[#58FF69] uppercase mb-5 text-center md:text-left lg:text-left"
              >
                Global To-do list
              </h4>
              <h1
                class="font-bold text-3xl text-center mb-10 md:text-left lg:text-5xl lg:text-left"
              >
                Buat Rencana, Wujudkan Impian Anda
              </h1>
              <hr
                class="bg-[#58FF69] p-1 rounded-xl w-56 mx-auto mb-8 lg:ml-0"
              />
              <p
                class="text-sm font-normal text-center text-[#9B9B9B] w-full md:text-left lg:text-left lg:text-base"
              >
                Lorem ipsum dolor sit amet consectetur. Libero sem sit
                ullamcorper massa faucibus sed lorem commodo. Tellus viverra in
                ac justo a auctor bibendum id.
              </p>
            </div>
          </div>
          <div
            class="col-span-12 md:col-span-8 lg:col-span-8 order-2 hidden lg:block md:block md:ml-32 lg:ml-64"
          >
            <div class="-mr-20">
              <img src="../public/rectangle/green.svg" alt="" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 5 -->

    <!-- Section 6 -->
    <section>
      <div class="w-full mx-auto bg px-20 py-8 mt-4">
        <div class="grid grid-cols-12">
          <div
            class="col-span-12 md:col-span-8 lg:col-span-8 order-1 hidden lg:block md:block md:mr-32 lg:mr-64"
          >
            <div class="-ml-20">
              <img src="../public/rectangle/ungu.svg" alt="" />
            </div>
          </div>
          <div class="col-span-12 md:col-span-4 lg:col-span-4 order-2">
            <div class="md:mt-24 lg:mt-48">
              <h4
                class="font-semibold text-lg text-[#FF53D9] uppercase mb-5 text-center md:text-left lg:text-left"
              >
                Global To-do list
              </h4>
              <h1
                class="font-bold text-3xl text-center mb-10 lg:text-5xl md:text-left lg:text-left"
              >
                Dapatkan Kontrol Penuh atas Aktivitas Anda.
              </h1>
              <hr
                class="bg-[#FF53D9] p-1 rounded-xl w-56 mx-auto mb-8 lg:ml-0"
              />
              <p
                class="text-sm font-normal text-center text-[#9B9B9B] w-full md:text-left lg:text-left lg:text-base"
              >
                Lorem ipsum dolor sit amet consectetur. Libero sem sit
                ullamcorper massa faucibus sed lorem commodo. Tellus viverra in
                ac justo a auctor bibendum id.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Section 6 -->

    <!-- Section 7 (GRID IMAGE) -->
    <section id="program" class="mb-20 md:mt-8">
      <div class="mb-8">
        <h1
          class="font-bold text-2xl text-center lg:text-4xl lg:font-bold lg:text-center"
        >
          Perencanaan yang Mudah
        </h1>
        <p
          class="text-sm text-center font-normal text-[#6B7280] mt-5 lg:text-lg"
        >
          Selesaikan tugas dengan lebih efisien.
        </p>
      </div>

      <div
        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 justify-items-center px-8"
      >
        <div class="">
          <div>
            <img src="../public/grid/1.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
        <div class="">
          <div>
            <img src="../public/grid/2.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
        <div class="">
          <div>
            <img src="../public/grid/3.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
        <div class="">
          <div>
            <img src="../public/grid/4.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
        <div class="">
          <div>
            <img src="../public/grid/5.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
        <div class="">
          <div>
            <img src="../public/grid/6.png" alt="..." />
          </div>
          <div class="mt-5 mb-16">
            <h1 class="font-medium text-xl text-center">Intuitive Design</h1>
            <p class="text-sm text-center text-[#6B7280] mt-3">
              Experience a clean, smart interface.
            </p>
          </div>
        </div>
      </div>
    </section>
    <!-- END Section 7 (GRID IMAGE)  -->

    <!-- Section 8 -->
    <section class="container mx-auto">
      <div class="flex justify-center items-center">
        <img src="../public/img/card.svg" alt="bergabung.png" />
      </div>
    </section>
    <!-- END section 8 -->

    <!-- Footer -->
    <footer id="Service" class=" aria-labelledby="footer-heading">
      <h2 id="footer-heading" class="sr-only">Footer</h2>
      <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
        <div class="xl:grid xl:grid-cols-3 xl:gap-8">
          <div class="space-y-8">
            <h1 class="font-bold text-4xl text-transparent bg-clip-text text-center md:text-center bg-gradient-to-r from-purple-400 to-purple-700  inline-block lg:text-left">Listify</h1>
            <p class="text-sm leading-6 text-gray-600 w-3/4 text-center mx-auto md:text-center md:mx-auto lg:text-left lg:mx-0">
              Lorem ipsum dolor sit amet consectetur. Libero sem sit ullamcorper massa faucibus sed lorem commodo. Tellus viverra in ac justo a auctor bibendum id.
            </p>
            <div class="flex space-x-6 justify-center md:justify-center lg:justify-start">
              <a href="#" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Facebook</span>
                <svg
                  class="h-6 w-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
              <a href="#" class="text-yellow-500 hover:text-gray-500">
                <span class="sr-only">the team is on fire</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="w-4 h-4"
                >
                  <path
                    fill-rule="evenodd"
                    d="M8.074.945A4.993 4.993 0 0 0 6 5v.032c.004.6.114 1.176.311 1.709.16.428-.204.91-.61.7a5.023 5.023 0 0 1-1.868-1.677c-.202-.304-.648-.363-.848-.058a6 6 0 1 0 8.017-1.901l-.004-.007a4.98 4.98 0 0 1-2.18-2.574c-.116-.31-.477-.472-.744-.28Zm.78 6.178a3.001 3.001 0 1 1-3.473 4.341c-.205-.365.215-.694.62-.59a4.008 4.008 0 0 0 1.873.03c.288-.065.413-.386.321-.666A3.997 3.997 0 0 1 8 8.999c0-.585.126-1.14.351-1.641a.42.42 0 0 1 .503-.235Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-blue-4 00">
                <span class="sr-only">X</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="w-4 h-4"
                >
                  <path
                    d="M2.75 2a.75.75 0 0 0-.75.75v10.5a.75.75 0 0 0 1.5 0v-2.624l.33-.083A6.044 6.044 0 0 1 8 11c1.29.645 2.77.807 4.17.457l1.48-.37a.462.462 0 0 0 .35-.448V3.56a.438.438 0 0 0-.544-.425l-1.287.322C10.77 3.808 9.291 3.646 8 3a6.045 6.045 0 0 0-4.17-.457l-.34.085A.75.75 0 0 0 2.75 2Z"
                  />
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">GitHub</span>
                <svg
                  class="h-6 w-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">YouTube</span>
                <svg
                  class="h-6 w-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
            </div>
          </div>
          <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
            <div class="md:grid md:grid-cols-2 md:gap-8 px-12 md:px-12 lg:px-0">
              <div>
                <h3 class="text-base uppercase font-semibold leading-6 text-gray-900">
                  Solutions
                </h3>
                <ul role="list" class="mt-6 space-y-4">
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Marketing</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Analytics</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Commerce</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Insights</a
                    >
                  </li>
                </ul>
              </div>
              <div class="mt-10 md:mt-0">
                <h3 class="text-base uppercase font-semibold leading-6 text-gray-900">
                  Support
                </h3>
                <ul role="list" class="mt-6 space-y-4">
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Pricing</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Documentation</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Guides</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >API Status</a
                    >
                  </li>
                </ul>
              </div>
            </div>
            <div class="md:grid md:grid-cols-2 md:gap-8">
              <div>
                <h3 class="text-base uppercase font-semibold leading-6 text-gray-900">
                  Company
                </h3>
                <ul role="list" class="mt-6 space-y-4">
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >About</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Blog</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Jobs</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Press</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900"
                      >Partners</a
                    >
                  </li>
                </ul>
              </div>
              <div class="mt-10 md:mt-0">
                <h3 class="text-base uppercase font-semibold leading-6 text-gray-900">
                  Contact us
                </h3>
                <ul role="list" class="mt-6 space-y-4">
                  <li>
                    
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900 mb-4 flex items-center justify-center md:justify-start"
                      >
                      <span class="me-3 [&>svg]:h-5 [&>svg]:w-5">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="currentColor">
                          <path
                            fill-rule="evenodd"
                            d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z"
                            clip-rule="evenodd" />
                        </svg>
                      </span>
                      (+62) 8818208207
                      </a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="text-sm leading-6 text-gray-600 hover:text-gray-900 mb-4 flex items-center justify-center md:justify-start"
                      >
                      <span class="me-3 [&>svg]:h-5 [&>svg]:w-5">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="currentColor">
                          <path
                            d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                          <path
                            d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                        </svg>
                      </span>
                      Listify@gmail.com
                      </a
                    >
                  </li>
                  
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 lg:mt-24">
          <p class="text-xs leading-5 text-gray-500 text-center md:text-center lg:text-start">
            &copy; 2024 By ListifyUI. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
    <!-- End Footer -->

    <script src="script.js"></script>
    <script>
      

      const navLinks = document.querySelector(".nav-links");
      function onToggleMenu(e) {
        e.name = e.name === "menu" ? "close" : "menu";
        navLinks.classList.toggle("top-[9%]");
      }

      // animasi text
    // Teks yang ingin dianimasikan
var textToAnimate = "Kami bertujuan untuk menyederhanakan rutinitas harian Anda dengan antarmuka daftar tugas yang intuitif.";

// Selector untuk span tempat animasi teks
var animatedTextElement = document.getElementById("animated-text");

// Variabel untuk menentukan karakter ke berapa yang akan ditampilkan
var charIndex = 0;

// Waktu interval antara penambahan karakter (ms)
var typingSpeed = 100;

// Fungsi untuk menambahkan satu karakter ke dalam span dengan interval waktu tertentu
function typeWriter() {
  if (charIndex < textToAnimate.length) {
    animatedTextElement.textContent += textToAnimate.charAt(charIndex);
    charIndex++;
    setTimeout(typeWriter, typingSpeed);
  } else {
    // Reset charIndex ke 0 dan kosongkan teks pada animatedTextElement
    charIndex = 0;
    animatedTextElement.textContent = "";
    // Mulai animasi lagi
    setTimeout(typeWriter, typingSpeed);
  }
}

// Panggil fungsi typeWriter() untuk memulai animasi
typeWriter();


    </script>
  </body>
</html>
