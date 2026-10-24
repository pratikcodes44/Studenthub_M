<?php
require_once 'bootstrap.php';
require_admin();
$pageTitle = 'Fetch API Student Records';
require 'partials/header.php';
?>
<p class="eyebrow">Practical 13</p>
<h1>Student records with Fetch API</h1>
<p class="muted">Add, edit and delete records through a PHP JSON endpoint without refreshing the page.</p>
<div id="api-message" class="notice" hidden>
</div>
<section class="panel">
    <h2 id="form-title">Add student</h2>
    <form id="student-form" class="form-grid">
        <input type="hidden" id="student-id">
        <div class="form-group">
<label for="full-name">Full name</label>
<input id="full-name" required>
</div>
        <div class="form-group">
<label for="student-email">Email</label>
<input type="email" id="student-email" required>
</div>
        <div class="form-group">
<label for="student-department">Department</label>
<select id="student-department">
<option>CE</option>
<option>IT</option>
<option>CSE</option>
</select>
</div>
        <div class="form-group">
<label for="student-year">Year</label>
<select id="student-year">
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
</select>
</div>
        <div>
<button class="button" type="submit">Save student</button>
<button class="button button-outline" type="button" id="clear-form">Clear</button>
</div>
    </form>
</section>
<div class="section-head">
<div>
<h2>Student list</h2>
<p>Changes appear as soon as the API responds.</p>
</div>
<button class="button button-outline button-small" type="button" id="refresh-list">Refresh list</button>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Year</th>
<th>Actions</th>
</tr>
</thead>
<tbody id="student-body">
<tr>
<td colspan="6">Loading...</td>
</tr>
</tbody>
</table>
</div>
<script>
const form = document.getElementById('student-form');
const messageBox = document.getElementById('api-message');
const tableBody = document.getElementById('student-body');
const idInput = document.getElementById('student-id');

function showMessage(message, isError = false) {
    messageBox.hidden = false;
    messageBox.className = isError ? 'notice error' : 'notice success';
    messageBox.textContent = message;
}

async function loadStudents() {
    tableBody.innerHTML = '<tr><td colspan="6">Loading students...</td></tr>';
    try {
        const response = await fetch('api_students.php');
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Could not load students.');
        tableBody.innerHTML = '';
        result.data.forEach(function (student) {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${student.id}</td><td>${escapeText(student.full_name)}</td><td>${escapeText(student.email)}</td><td>${escapeText(student.department)}</td><td>${escapeText(student.year_no)}</td><td><button class="button button-small" data-edit="${student.id}">Edit</button> <button class="button button-small" style="background:#c83848" data-delete="${student.id}">Delete</button></td>`;
            row.querySelector('[data-edit]').addEventListener('click', function () { editStudent(student); });
            row.querySelector('[data-delete]').addEventListener('click', function () { deleteStudent(student.id); });
            tableBody.appendChild(row);
        });
        if (!result.data.length) tableBody.innerHTML = '<tr><td colspan="6">No student records found.</td></tr>';
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="6">Unable to load student data.</td></tr>';
        showMessage(error.message, true);
    }
}

function editStudent(student) {
    idInput.value = student.id;
    document.getElementById('full-name').value = student.full_name;
    document.getElementById('student-email').value = student.email;
    document.getElementById('student-department').value = student.department;
    document.getElementById('student-year').value = student.year_no;
    document.getElementById('form-title').textContent = 'Edit student';
}

function clearForm() {
    form.reset();
    idInput.value = '';
    document.getElementById('form-title').textContent = 'Add student';
}

form.addEventListener('submit', async function (event) {
    event.preventDefault();
    const id = idInput.value;
    const data = {
        id: id,
        full_name: document.getElementById('full-name').value.trim(),
        email: document.getElementById('student-email').value.trim(),
        department: document.getElementById('student-department').value,
        year_no: document.getElementById('student-year').value
    };
    const method = id ? 'PUT' : 'POST';
    try {
        const response = await fetch('api_students.php', {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Operation failed.');
        showMessage(result.message);
        clearForm();
        loadStudents();
    } catch (error) {
        showMessage(error.message, true);
    }
});

async function deleteStudent(id) {
    if (!confirm('Delete this student record?')) return;
    try {
        const response = await fetch('api_students.php', {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Delete failed.');
        showMessage(result.message);
        loadStudents();
    } catch (error) {
        showMessage(error.message, true);
    }
}

function escapeText(value) {
    return String(value).replace(/[&<>"']/g, function (char) {
        return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'}[char];
    });
}

document.getElementById('clear-form').addEventListener('click', clearForm);
document.getElementById('refresh-list').addEventListener('click', loadStudents);
loadStudents();
</script>
<?php require 'partials/footer.php'; ?>
