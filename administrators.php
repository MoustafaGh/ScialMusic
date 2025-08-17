<?php
session_start();
include 'config.php';

if (isset($_POST['Logout_btn'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SocialMedia Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a href="admin.php" class="navbar-brand d-flex align-items-center">
            <h1 class="m-0 text-light">Socialmedia</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <form action="" method="post" class="ms-auto">
                <button type="submit" name="Logout_btn" class="btn btn-outline-light">Log Out</button>
            </form>
        </div>
    </nav>
</header>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-2 bg-dark text-light min-vh-100 d-flex flex-column">
            <div class="p-4 text-center">
                <h4>Admin Panel</h4>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item">
                    <a href="user_admin.php" class="nav-link text-light"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="administrators.php" class="nav-link text-light"><i class="fas fa-user-shield"></i> Administrators</a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link text-light"><i class="fas fa-users"></i> Users</a>
                </li>
            </ul>
        </aside>
        <main class="col-md-10 p-4">

            <section>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Administrator Management</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $query = "SELECT username, email FROM admin";
                        $res = mysqli_query($conn, $query);

                        echo "
                        <table class='table table-hover'>
                            <thead class='table-primary'>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                        ";

                        if (mysqli_num_rows($res) < 1) {
                            echo "<tr><td colspan='2' class='text-center'>No Administrators Found!</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_array($res)) {
                                echo "
                                <tr>
                                    <td>{$row['username']}</td>
                                    <td>{$row['email']}</td>
                                </tr>
                                ";
                            }
                        }
                        echo "
                            </tbody>
                        </table>";
                        ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
