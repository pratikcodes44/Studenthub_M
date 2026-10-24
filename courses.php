<?php
require_once 'bootstrap.php';
$pageTitle = 'Courses';
$courses = [
    ['WDF', 'Web Development Fundamentals', 'Build web pages with HTML, CSS, JavaScript and PHP.'],
    ['DSA', 'Data Structures and Algorithms', 'Understand arrays, linked lists, stacks, queues and searching.'],
    ['CN', 'Computer Networks', 'Learn the basics of protocols, network layers and data transfer.'],
    ['OOP', 'Object-Oriented Programming', 'Use classes, objects, inheritance and exception handling.'],
];
require 'partials/header.php';
?>
<p class="eyebrow">Academic resources</p>
<h1>Course directory</h1>
<p class="muted">A simple list of subjects and what you will practise in each one.</p>
<section class="grid">
<?php foreach ($courses as $course): ?>
    <article class="card">
<span class="tag"><?= e($course[0]) ?></span>
<h3 style="margin-top:13px"><?= e($course[1]) ?></h3>
<p><?= e($course[2]) ?></p>
</article>
<?php endforeach; ?>
</section>
<?php require 'partials/footer.php'; ?>
