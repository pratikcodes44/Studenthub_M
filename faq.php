<?php
require_once 'bootstrap.php';
$pageTitle = 'FAQ';
$faqs = json_decode(file_get_contents(__DIR__ . '/faqs.json'), true) ?: [];
require 'partials/header.php';
?>
<p class="eyebrow">Need a hand?</p>
<h1>Frequently asked questions</h1>
<p class="muted">Click any question to show or hide its answer.</p>
<div class="narrow">
<?php foreach ($faqs as $index => $faq): ?>
    <div class="faq-item">
<button type="button" class="faq-question" data-faq-question><?= e($faq['question']) ?><span>＋</span>
</button>
<div class="faq-answer" hidden><?= e($faq['answer']) ?></div>
</div>
<?php endforeach; ?>
</div>
<?php require 'partials/footer.php'; ?>
