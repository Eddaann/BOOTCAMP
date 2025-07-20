<?php 
$lesson_id = 1; 
require_once __DIR__ . '/../includes/auth.php'; 

$page_title = "Módulo 1: HTML, El Esqueleto de la Web";
$is_lesson_page = true;
include __DIR__ . '/../includes/header.php'; 

// Obtener info de la lección de la BD
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$lesson_id]);
$lesson = $stmt->fetch();
?>

<div class="container">
  <div class="lesson-container">
    <h2><?= htmlspecialchars($lesson['titulo']) ?> 🦴</h2>
    <div class="activity-instructions">
        <h3>Teoría y Conceptos Clave</h3>
        <p>¡Hola a todos! Antes de empezar a escribir código, es crucial entender qué es HTML. Piensen en HTML (HyperText Markup Language) como el esqueleto de una casa. No define los colores ni los muebles, pero sí establece las habitaciones, las puertas y las ventanas.</p>
    </div>

    <!-- Video de Apoyo -->
    <?php if (!empty($lesson['youtube_id'])): ?>
    <div class="video-container">
        <h3>Video de Apoyo</h3>
        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($lesson['youtube_id']) ?>" 
                title="YouTube video player" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    </div>
    <?php endif; ?>
  </div>
  
  <div class="lesson-container" style="margin-top: 2rem;">
    <h3>Parte Práctica: ¡Manos a la Obra!</h3>
  </div>
  <div class="activity-wizard">
    <!-- El resto del contenido de la lección no cambia -->
    <div class="activity-step active" data-step="1">
        <!-- ... tu código de actividades ... -->
    </div>
    <!-- ... más actividades ... -->
    <div class="activity-step" data-step="12">
         <div class="activity-header"><h3>¡Módulo <?= $lesson_id ?> Completado! ✅</h3></div>
         <div class="activity-instructions completion-form">
            <p>¡Felicidades! Has dominado las etiquetas fundamentales de HTML. Estás listo para el siguiente desafío.</p>
            <form action="../complete_lesson.php" method="POST" style="display: inline;">
                <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                <button type="submit" class="btn-action btn-verify">Marcar como Completado (+100 XP)</button>
            </form>
         </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
