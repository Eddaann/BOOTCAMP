Bootcamp Web - Plataforma Interactiva de AprendizajeBootcamp Web es una aplicación web educativa diseñada para enseñar los fundamentos del desarrollo web (HTML, CSS) de una manera interactiva y gamificada. Los usuarios pueden registrarse, seguir lecciones paso a paso, completar desafíos prácticos y competir en una tabla de clasificación basada en puntos de experiencia (XP).✨ Características PrincipalesAutenticación de Usuarios: Sistema completo de registro, inicio de sesión y cierre de sesión.Recuperación de Contraseña: Funcionalidad de "olvidé mi contraseña" que envía un enlace de restablecimiento por correo electrónico utilizando PHPMailer [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/forgot-password.php].Panel de Usuario (Dashboard): Muestra el progreso del usuario, las lecciones disponibles y las estadísticas como nivel y XP [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/dashboard.php].Lecciones Interactivas: Módulos de aprendizaje estructurados por días, con teoría y actividades prácticas interactivas [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/lessons/leccion1.php, eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/lessons/leccion2.php, eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/lessons/leccion3.php, eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/lessons/leccion4.php].Sistema de Progresión: Las lecciones se desbloquean a medida que el usuario completa las anteriores [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/includes/auth.php].Gamificación: Los usuarios ganan puntos de experiencia (XP) al completar lecciones, lo que les permite subir de nivel [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/complete_lesson.php].Tabla de Clasificación (Leaderboard): Un ranking de usuarios basado en su XP acumulado para fomentar la competencia sana [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/leaderboard.php].Perfiles de Usuario Editables: Los usuarios pueden actualizar su nombre, apodo y subir una foto de perfil [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/profile.php].Tema Claro/Oscuro: Un interruptor de tema para mejorar la experiencia del usuario [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/assets/js/theme-switcher.js].🛠️ Tecnologías UtilizadasBackend: PHPBase de Datos: MySQL / MariaDBFrontend: HTML5, CSS3, JavaScriptLibrerías:PHPMailer: Para el envío de correos electrónicos.🚀 Instalación y Puesta en MarchaSigue estos pasos para configurar el proyecto en tu entorno local.PrerrequisitosUn servidor web local (XAMPP, WAMP, MAMP, etc.).PHP (versión 8.0 o superior recomendada).MySQL o MariaDB.PasosClonar el Repositoriogit clone <URL-DEL-REPOSITORIO>
cd <NOMBRE-DEL-DIRECTORIO>
Configurar la Base de DatosAbre tu gestor de base de datos (como phpMyAdmin).Crea una nueva base de datos llamada bootcamp_db [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/includes/db.php].Importa el archivo bootcamp_db.sql en la base de datos recién creada. Esto creará todas las tablas necesarias y cargará los datos iniciales de las lecciones [cite: eddaann/bootcamp/BOOTCAMP-c2ad858b5e0b59284dae9b9f11a3b7b256c53ae3/bootcamp_db.sql].Configurar la Conexión a la Base de DatosAbre el archivo includes/db.php.Asegúrate de que las credenciales ($host, $db, $user, $pass) coincidan con la configuración de tu servidor de base de datos local.$host = 'localhost';
$db   = 'bootcamp_db';
$user = 'root';
$pass = ''; // Tu contraseña de MySQL
Configurar el Envío de Correos (PHPMailer)Abre el archivo forgot-password.php.Dentro del bloque try, busca las siguientes líneas y actualízalas con tus propias credenciales de un servidor SMTP (en este ejemplo, se usa Gmail con una contraseña de aplicación).// --- ¡CONFIGURA TUS CREDENCIALES AQUÍ! ---
$mail->Username   = 'tu-correo@gmail.com'; // Tu dirección de correo de Gmail
$mail->Password   = 'tu-contraseña-de-aplicacion'; // Tu contraseña de aplicación generada
Nota: Para usar Gmail, necesitas generar una "Contraseña de aplicación". Puedes encontrar cómo hacerlo en la ayuda de Google.Iniciar el ServidorMueve la carpeta del proyecto al directorio raíz de tu servidor web (por ejemplo, htdocs en XAMPP).Inicia los servicios de Apache y MySQL desde el panel de control de tu servidor.Abre tu navegador y ve a http://localhost/<NOMBRE-DEL-DIRECTORIO>.📂 Estructura del Proyecto/
├── assets/                 # Archivos CSS y JavaScript
│   ├── css/
│   └── js/
├── includes/               # Módulos PHP reutilizables
│   ├── auth.php            # Lógica de autenticación y acceso
│   ├── db.php              # Conexión a la base de datos
│   ├── footer.php          # Pie de página
│   ├── header.php          # Cabecera principal
│   └── lesson_header.php   # Cabecera para las lecciones
├── lessons/                # Archivos de cada lección
├── phpmailer/              # Librería PHPMailer
├── uploads/                # Directorio para avatares de usuario
│   └── avatars/
├── bootcamp_db.sql         # Script de la base de datos
├── complete_lesson.php     # Procesa la finalización de lecciones
├── dashboard.php           # Panel principal del usuario
├── forgot-password.php     # Formulario para recuperar contraseña
├── index.php               # Página de inicio
├── leaderboard.php         # Tabla de clasificación
├── login.php               # Formulario de inicio de sesión
├── logout.php              # Cierra la sesión del usuario
├── profile.php             # Edición del perfil de usuario
├── register.php            # Formulario de registro
└── reset-password.php      # Formulario para establecer nueva contraseña