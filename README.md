<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>

  <h1>📬 School Suggestion Box</h1>
  <p><strong>School Suggestion Box</strong> is a web-based application built with PHP, MySQL, HTML, and CSS. It enables students to submit anonymous feedback or suggestions, which administrators can securely view and manage through a dedicated backend panel. Designed for schools or universities to improve communication and transparency.</p>

  <h2>🎯 Features</h2>
  <ul>
    <li>Anonymous suggestion submissions by students</li>
    <li>Administrator panel to view and manage feedback</li>
    <li>Secure login system for administrators</li>
    <li>Responsive design for various devices</li>
  </ul>

  <h2>📁 Project Structure</h2>
  <pre>
School-Suggestion-Box/
├── admin/              --> Admin panel files
├── assets/             --> Images and other assets
├── css/                --> Stylesheets
├── database/           --> SQL files for database setup
├── includes/           --> PHP include files
├── contact.php         --> Contact form page
├── faq.php             --> Frequently Asked Questions page
├── feedbacks.php       --> Page to view submitted feedback
├── index.php           --> Homepage
├── login.php           --> Admin login page
├── register.php        --> Admin registration page
├── LICENSE             --> License file
  </pre>

  <h2>⚙️ Installation</h2>
  <ol>
    <li>Clone the repository:
      <pre><code>git clone https://github.com/Vexx-bit/School-Suggestion-Box.git</code></pre>
    </li>
    <li>Set up a MySQL database and import the provided SQL file:
      <ul>
        <li>Create a new database (e.g., <code>suggestion_box</code>)</li>
        <li>Import the SQL script located in the <code>database/</code> directory</li>
      </ul>
    </li>
    <li>Configure the database connection:
      <ul>
        <li>Open the relevant configuration file in the <code>includes/</code> directory</li>
        <li>Update the database credentials accordingly</li>
      </ul>
    </li>
    <li>Run the application:
      <ul>
        <li>Place the project folder in your web server's root directory (e.g., <code>htdocs</code> for XAMPP)</li>
        <li>Start your web server and navigate to <code>http://localhost/School-Suggestion-Box/</code></li>
      </ul>
    </li>
  </ol>

  <h2>🔐 Administrator Access</h2>
  <ul>
    <li>Register a new administrator account via <code>register.php</code></li>
    <li>Login using the registered credentials via <code>login.php</code></li>
  </ul>

  <h2>📄 License</h2>
  <p>This project is licensed under the terms of the <a href="https://github.com/Vexx-bit/School-Suggestion-Box/blob/main/LICENSE">MIT License</a>.</p>

  <p>Developed with ❤️ by <a href="https://github.com/Vexx-bit">Samuel Kang'ethe</a></p>

</body>
</html>
