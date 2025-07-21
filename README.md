# Bootcamp Web - Plataforma Interactiva de Aprendizaje

**Bootcamp Web** es una aplicación educativa diseñada para enseñar los fundamentos del desarrollo web (HTML, CSS, JavaScript) de forma interactiva y gamificada. Los usuarios pueden registrarse, avanzar en lecciones estructuradas, completar desafíos prácticos y competir en una tabla de clasificación basada en puntos de experiencia (XP).

## ✨ Características Principales

- **Autenticación de Usuarios:** Registro, inicio de sesión y cierre de sesión seguros. Ref: `includes/auth.php`
- **Recuperación de Contraseña:** Envia un enlace de restablecimiento por correo usando PHPMailer. Ref: `forgot-password.php`
- **Panel de Usuario:** Visualiza el progreso, lecciones disponibles, nivel y XP del usuario. Ref: `dashboard.php`
- **Lecciones Interactivas:** Módulos estructurados por días con teoría y ejercicios prácticos. Ref: `lessons/leccion1.php`, `lessons/leccion2.php`, `lessons/leccion3.php`, `lessons/leccion4.php`
- **Sistema de Progresión:** Desbloquea lecciones al completar las anteriores. Ref: `includes/auth.php`
- **Gamificación:** Gana XP al completar lecciones y sube de nivel. Ref: `complete_lesson.php`
- **Tabla de Clasificación:** Ranking de usuarios basado en XP para una competencia sana. Ref: `leaderboard.php`
- **Perfiles Editables:** Actualiza nombre, apodo y foto de perfil. Ref: `profile.php`
- **Tema Claro/Oscuro:** Interruptor de tema para una mejor experiencia de usuario. Ref: `assets/js/theme-switcher.js`


## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP
- **Base de Datos:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Librerías:** PHPMailer: Envío de correos electrónicos.


## 🚀 Instalación y Configuración
Sigue estos pasos para configurar el proyecto en tu entorno local.

### Prerrequisitos

- Servidor web local (XAMPP, WAMP, MAMP, etc.).
- PHP 8.0 o superior.
- MySQL o MariaDB.
- Git.

### Pasos

#### 1. Clonar el Repositorio

```bash
git clone https://github.com/Eddaann/BOOTCAMP
cd BOOTCAMP
```


#### 2. Configurar la Base de Datos

1. Abre tu gestor de base de datos (ej. phpMyAdmin).
2. Crea una base de datos llamada *bootcamp_db*. Ref: `includes/db.php`
3. Importa el archivo `bootcamp_db.sql` para crear las tablas y cargar datos iniciales. Ref: `bootcamp_db.sql`


#### 3. Configurar la Conexión a la Base de Datos

- Edita `includes/db.php` con las credenciales de tu servidor:
```php
$host = 'localhost';
$db   = 'bootcamp_db';
$user = 'root';
$pass = ''; // Tu contraseña de MySQL
```


### 4. Configurar el Envío de Correos (PHPMailer)

Edita `forgot-password.php` con tus credenciales SMTP:
```php
$mail->Username = 'tu-correo@gmail.com';
$mail->Password = 'tu-contraseña-de-aplicacion';
```

Nota: Usa una contraseña de aplicación de Gmail para Gmail.


### 5. Iniciar el Servidor

Mueve el proyecto al directorio raíz de tu servidor web (ej. htdocs en XAMPP).
Inicia Apache y MySQL.
Abre http://localhost/<NOMBRE-DEL-DIRECTORIO> en tu navegador.




## 📂 Estructura del Proyecto
```
/
├── assets/                 # Archivos CSS y JavaScript
├── includes/               # Módulos PHP reutilizables
├── lessons/                # Archivos de lecciones
├── phpmailer/              # Librería PHPMailer
├── uploads/                # Directorio para avatares
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
└── reset-password.php      # Formulario para nueva contraseña
```

## 📝 Notas Adicionales

Asegúrate de que el directorio uploads/ tenga permisos de escritura para subir fotos de perfil.
Revisa la documentación de PHPMailer para configuraciones avanzadas de correo.
Este proyecto está diseñado para entornos educativos, pero puede extenderse con más lecciones o funcionalidades.


## 🤝 Contribuciones
¡Las contribuciones son bienvenidas! Si deseas mejorar el proyecto:

Haz un fork del repositorio.
Crea una rama para tu cambio (git checkout -b feature/nueva-funcionalidad).
Envía un pull request con una descripción clara de los cambios.


## 📧 Contacto
Para dudas o sugerencias, contáctame a través de GitHub Issues.
