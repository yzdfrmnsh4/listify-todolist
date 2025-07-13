window.addEventListener("scroll", () => {
    const navbar = window.document.querySelector("nav");
    navbar.classList.toggle("bg-white", window.scrollY > 1);
    navbar.classList.toggle("shadow-lg", window.scrollY > 1);
})