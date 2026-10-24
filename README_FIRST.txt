STUDENTWDF - WEB DEVELOPMENT FUNDAMENTALS PRACTICAL PROJECT

This project is a simple student-level PHP, MySQL, HTML, CSS and JavaScript website made for WDF practicals.

REQUIREMENTS
- XAMPP with Apache and MySQL
- A browser such as Chrome

SETUP
1. Extract StudentWDF.zip.
2. Copy the StudentWDF folder to C:\xampp\htdocs\StudentWDF
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open this URL in your browser:
   http://localhost/StudentWDF/setup.php
5. Wait for the Database Setup Successful message.
6. Open the website:
   http://localhost/StudentWDF/
7. After setup is successful, you may delete setup.php from the folder for safety.

DEMO ADMIN LOGIN
Email: admin@studentwdf.local
Password: Admin@12345

PROJECT FEATURES
- Responsive design using CSS Grid, Flexbox and media queries
- JavaScript theme toggle, banner, FAQ and form validation
- JSON data loaded with Fetch API, search, filter, sorting and pagination
- PHP contact form with validation and CSV/JSON file storage
- MySQL database connection using PDO
- Student registration with password hashing and duplicate-email check
- Login, logout and session-based access control
- Student records CRUD with search
- Event management and basic poster validation
- JSON API and Fetch-based student CRUD without a full page reload
- Admin dashboard with counts and audit log

DATABASE
The project creates the studentwdf_db database automatically. It assumes the default XAMPP MySQL root account has a blank password. If your MySQL password is different, update db.php and setup.php.

IMPORTANT
- Always open the project through http://localhost/StudentWDF/; do not double-click PHP files.
- Replace screenshot boxes in the Word files with screenshots from your own running site and code.
- Use your own real student details on the cover pages.
