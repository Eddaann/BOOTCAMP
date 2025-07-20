<?php
$page_title = "Crea tu primera página web";
$is_lesson_page = false;
include 'includes/header.php';
?>

  <section class="hero">
    <div class="container" style="text-align: center; padding: 4rem 2rem;">
      <h2 style="font-size: 3rem; margin-bottom: 1rem;">¡Crea tu primera página web desde cero!</h2>
      <p style="font-size: 1.25rem; max-width: 600px; margin: auto;">Un bootcamp gratuito, interactivo y moderno para que aprendas las bases del desarrollo web.</p>
      <a href="register.php" class="btn" style="display: inline-block; margin-top: 2rem; padding: 1rem 2.5rem; background: var(--primary-color); color: white; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 1.1rem; transition: transform 0.3s, box-shadow 0.3s;">¡Empieza a Aprender Ahora!</a>
    </div>
  </section>

  <div class="container">
      <h3 style="text-align:center; font-size: 2rem; margin-bottom: 3rem;">¿Qué aprenderás?</h3>
      <div class="lesson-grid">
          <div class="lesson-card"><h3>HTML Básico</h3><p>Aprende la estructura fundamental de la web con HTML.</p></div>
          <div class="lesson-card"><h3>CSS Esencial</h3><p>Aplica estilos y haz que tu página se vea increíble.</p></div>
          <div class="lesson-card"><h3>Diseño Adaptable</h3><p>Asegúrate que tu sitio funcione en cualquier dispositivo.</p></div>
          <div class="lesson-card"><h3>Publicación Web</h3><p>Sube tu página a internet y compártela con el mundo.</p></div>
      </div>
  </div>

<?php include 'includes/footer.php'; ?>