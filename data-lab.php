<?php
require_once 'bootstrap.php';
$pageTitle = 'Data Lab';
require 'partials/header.php';
?>
<p class="eyebrow">Practical 6</p>
<h1>Student Data Lab</h1>
<p class="muted">This page loads sample records from students.json using the Fetch API.</p>
<section class="panel">
    <div class="form-grid">
        <div class="form-group">
<label for="searchInput">Search name or email</label>
<input id="searchInput" placeholder="Start typing...">
</div>
        <div class="form-group">
<label for="departmentFilter">Department</label>
<select id="departmentFilter">
<option value="all">All departments</option>
<option value="CE">CE</option>
<option value="IT">IT</option>
<option value="CSE">CSE</option>
</select>
</div>
        <div class="form-group">
<label for="sortOrder">Sort by name</label>
<select id="sortOrder">
<option value="asc">A to Z</option>
<option value="desc">Z to A</option>
</select>
</div>
    </div>
    <div id="data-status" class="notice">Loading student data...</div>
    <div id="student-list" class="grid">
</div>
    <div id="pagination" class="pagination">
</div>
</section>
<script>
let allStudents = [];
let currentPage = 1;
const perPage = 6;

async function loadStudents() {
    const status = document.getElementById('data-status');
    try {
        const response = await fetch('students.json');
        if (!response.ok) throw new Error('Could not read students.json');
        allStudents = await response.json();
        status.hidden = true;
        showStudents();
    } catch (error) {
        status.className = 'notice error';
        status.textContent = 'Student data could not be loaded. Please refresh the page.';
    }
}

function showStudents() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const department = document.getElementById('departmentFilter').value;
    const sortOrder = document.getElementById('sortOrder').value;
    let results = allStudents.filter(function (student) {
        const matchesText = student.name.toLowerCase().includes(search) || student.email.toLowerCase().includes(search);
        const matchesDepartment = department === 'all' || student.department === department;
        return matchesText && matchesDepartment;
    });
    results.sort(function (a, b) {
        return sortOrder === 'asc' ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name);
    });

    const start = (currentPage - 1) * perPage;
    const pageStudents = results.slice(start, start + perPage);
    const list = document.getElementById('student-list');
    list.innerHTML = '';
    if (pageStudents.length === 0) {
        list.innerHTML = '<div class="empty-state">No records match your search.</div>';
    }
    pageStudents.forEach(function (student) {
        const card = document.createElement('article');
        card.className = 'card';
        const name = document.createElement('h3');
        name.textContent = student.name;
        const email = document.createElement('p');
        email.textContent = student.email;
        email.className = 'muted';
        const dept = document.createElement('span');
        dept.className = 'tag';
        dept.textContent = student.department;
        card.append(name, email, dept);
        list.appendChild(card);
    });

    const pages = Math.ceil(results.length / perPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let page = 1; page <= pages; page++) {
        const button = document.createElement('button');
        button.className = 'page-number' + (page === currentPage ? ' active' : '');
        button.textContent = page;
        button.addEventListener('click', function () { currentPage = page; showStudents(); });
        pagination.appendChild(button);
    }
}

document.getElementById('searchInput').addEventListener('input', function () { currentPage = 1; showStudents(); });
document.getElementById('departmentFilter').addEventListener('change', function () { currentPage = 1; showStudents(); });
document.getElementById('sortOrder').addEventListener('change', function () { currentPage = 1; showStudents(); });
loadStudents();
</script>
<?php require 'partials/footer.php'; ?>
