<?php
require_once 'bootstrap.php';
$pageTitle = 'About';
require 'partials/header.php';
?>
<section class="panel">
    <p class="eyebrow">About the project</p>
    <h1>Made for students, kept simple.</h1>
    <p>StudentWDF is a small academic portal created as a Web Development Fundamentals project. It brings together responsive web design, JavaScript interaction, PHP forms and MySQL database work.</p>
</section>
<div class="section-head">
<div>
<h2>What I practised</h2>
<p>Each feature applies one or more topics from the practical list.</p>
</div>
</div>
<section class="grid">
    <article class="card">
<div class="card-icon">🎨</div>
<h3>Frontend</h3>
<p>HTML forms, responsive CSS, Grid, Flexbox and browser-side validation.</p>
</article>
    <article class="card">
<div class="card-icon">⚙️</div>
<h3>JavaScript</h3>
<p>DOM events, Fetch API, JSON data, search, sorting and pagination.</p>
</article>
    <article class="card">
<div class="card-icon">🗄️</div>
<h3>Backend</h3>
<p>PHP processing, sessions, prepared statements, database records and audit logs.</p>
</article>
</section>
<?php require 'partials/footer.php'; ?>
