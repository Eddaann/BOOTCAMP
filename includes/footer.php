</main>
<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> Bootcamp Web. Todos los derechos reservados.</p>
    </div>
</footer>
<!-- Scripts -->
<script src="<?= $base_path ?>assets/js/theme-switcher.js"></script>
<script src="<?= $base_path ?>assets/js/main.js"></script>
<?php if (isset($is_lesson_page) && $is_lesson_page): ?>
    <script src="<?= $base_path ?>assets/js/lesson-script.js"></script>
<?php endif; ?>
<?php // --- ¡AQUÍ ESTÁ EL CAMBIO! --- ?>
<?php if (isset($is_profile_page) && $is_profile_page): ?>
    <script src="<?= $base_path ?>assets/js/profile-preview.js"></script>
<?php endif; ?>
</body>
</html>
