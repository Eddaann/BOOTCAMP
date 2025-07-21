<?php
session_start();
// Si el usuario ya está logueado, redirigir al dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootcamp de Código - Convierte Ideas en Realidad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a; /* Un negro un poco más suave */
        }
        .hero-gradient-text {
            background: -webkit-linear-gradient(45deg, #38bdf8, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .cta-button {
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="text-white">

    <!-- Header -->
    <header class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
        </h1>
        <nav class="hidden md:flex space-x-6 items-center">
            <a href="#features" class="hover:text-cyan-400 transition-colors">Características</a>
            <a href="#curriculum" class="hover:text-cyan-400 transition-colors">Plan de Estudios</a>
            <a href="login.php" class="hover:text-cyan-400 transition-colors">Iniciar Sesión</a>
            <a href="register.php" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-lg cta-button">¡Inscríbete Ahora!</a>
        </nav>
        <button class="md:hidden text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </header>

    <!-- Hero Section -->
    <main class="container mx-auto px-6 text-center pt-24 pb-32">
        <h2 class="text-4xl md:text-6xl font-extrabold leading-tight">
            Convierte tus Ideas en <br> <span class="hero-gradient-text">Páginas Web Increíbles</span>
        </h2>
        <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-400">
            Únete a nuestro bootcamp completo de 4 semanas y aprende a construir sitios web interactivos desde cero. No necesitas experiencia, ¡solo ganas de crear!
        </p>
        <div class="mt-8">
            <a href="register.php" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 px-8 rounded-lg text-lg cta-button inline-block">
                ¡Quiero Empezar Mi Aventura!
            </a>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-bold text-center mb-12">¿Por qué nuestro Bootcamp?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="bg-gray-800 p-8 rounded-xl text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="h-12 w-12 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Aprende Haciendo</h4>
                    <p class="text-gray-400">Nuestros laboratorios interactivos te permiten escribir código y ver los resultados al instante. ¡La mejor forma de aprender es programando!</p>
                </div>
                <!-- Feature Card 2 -->
                <div class="bg-gray-800 p-8 rounded-xl text-center">
                    <div class="flex justify-center mb-4">
                         <svg class="h-12 w-12 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Compite y Gana</h4>
                    <p class="text-gray-400">Con nuestro sistema de ranking en vivo, cada laboratorio completado te da puntos. ¡Demuestra quién es el mejor y gana premios!</p>
                </div>
                <!-- Feature Card 3 -->
                <div class="bg-gray-800 p-8 rounded-xl text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="h-12 w-12 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Crea un Proyecto Real</h4>
                    <p class="text-gray-400">No solo aprenderás teoría. Al final del bootcamp, habrás construido tu propio proyecto web para añadir a tu portafolio.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Curriculum Section -->
    <section id="curriculum" class="py-20">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-bold text-center mb-12">Tu Viaje en 4 Semanas</h3>
            <div class="max-w-3xl mx-auto space-y-10">
                <!-- Week 1 -->
                <div class="flex items-start">
                    <div class="bg-cyan-500 text-white font-bold rounded-full h-16 w-16 flex items-center justify-center text-xl mr-6 flex-shrink-0">S1</div>
                    <div>
                        <h4 class="text-2xl font-bold text-cyan-400">Fundamentos y HTML5</h4>
                        <p class="text-gray-400 mt-1">Entenderás cómo funciona la web y dominarás <span class="font-semibold text-white">HTML</span> para construir el esqueleto de cualquier página, desde textos y listas hasta imágenes y videos.</p>
                    </div>
                </div>
                <!-- Week 2 -->
                <div class="flex items-start">
                    <div class="bg-indigo-500 text-white font-bold rounded-full h-16 w-16 flex items-center justify-center text-xl mr-6 flex-shrink-0">S2</div>
                    <div>
                        <h4 class="text-2xl font-bold text-indigo-400">Estilo con CSS3</h4>
                        <p class="text-gray-400 mt-1">Aprenderás a usar <span class="font-semibold text-white">CSS</span> para dar vida a tus páginas con colores, tipografías y layouts profesionales. Crearemos diseños que se adapten a cualquier dispositivo (Diseño Responsivo).</p>
                    </div>
                </div>
                <!-- Week 3 -->
                <div class="flex items-start">
                    <div class="bg-fuchsia-500 text-white font-bold rounded-full h-16 w-16 flex items-center justify-center text-xl mr-6 flex-shrink-0">S3</div>
                    <div>
                        <h4 class="text-2xl font-bold text-fuchsia-400">Interactividad con JavaScript</h4>
                        <p class="text-gray-400 mt-1">Descubrirás el poder de <span class="font-semibold text-white">JavaScript</span> para manipular tu página, responder a eventos del usuario y crear experiencias dinámicas como calculadoras o galerías interactivas.</p>
                    </div>
                </div>
                 <!-- Week 4 -->
                <div class="flex items-start">
                    <div class="bg-rose-500 text-white font-bold rounded-full h-16 w-16 flex items-center justify-center text-xl mr-6 flex-shrink-0">S4</div>
                    <div>
                        <h4 class="text-2xl font-bold text-rose-400">Proyecto Final y Despliegue</h4>
                        <p class="text-gray-400 mt-1">Aplicarás todo lo aprendido para construir un <span class="font-semibold text-white">proyecto final</span> de tu elección. Al terminar, lo desplegarás en internet para que todo el mundo pueda verlo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 bg-gray-900">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-3xl md:text-4xl font-bold mb-4">¿Listo para empezar a crear?</h3>
            <p class="text-gray-400 text-lg mb-8 max-w-xl mx-auto">Las plazas son limitadas. ¡Asegura tu lugar y da el primer paso para convertirte en un desarrollador web!</p>
            <a href="register.php" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 px-8 rounded-lg text-lg cta-button inline-block">
                ¡Sí, quiero inscribirme!
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8">
        <div class="container mx-auto px-6 text-center text-gray-500">
            <p>&copy; 2025 Código Bootcamp. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>