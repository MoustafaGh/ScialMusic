<?php
session_start();
include 'config.php';

if (isset($_POST['Logout_btn'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Fetch total users and admins count for the summary dashboard
$userCountQuery = "SELECT COUNT(*) AS total_users FROM users";
$adminCountQuery = "SELECT COUNT(*) AS total_admins FROM admin";

$userCountResult = mysqli_query($conn, $userCountQuery);
$adminCountResult = mysqli_query($conn, $adminCountQuery);

$totalUsers = mysqli_fetch_assoc($userCountResult)['total_users'];
$totalAdmins = mysqli_fetch_assoc($adminCountResult)['total_admins'];
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
            <h1 class="m-0 text-light">SocialMedia</h1>
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
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="col-md-10 p-4">
            <!-- Summary Dashboard -->
            <section class="mb-5">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary">Total Users</h5>
                                <h2 class="card-text text-dark fw-bold"><?= $totalUsers ?></h2>
                                <i class="fas fa-users fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-success shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title text-success">Total Admins</h5>
                                <h2 class="card-text text-dark fw-bold"><?= $totalAdmins ?></h2>
                                <i class="fas fa-user-shield fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Search Management -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Search Management</h3>
                    </div>
                    <div class="card-body">
                        <!-- Search Form -->
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" name="searchQuery" class="form-control" placeholder="Search for a user or admin..." required>
                                </div>
                                <div class="col-md-4">
                                    <select name="searchType" class="form-select">
                                        <option value="users">Users</option>
                                        <option value="admin">Administrators</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="searchBtn" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['searchBtn'])) {
                            $searchQuery = mysqli_real_escape_string($conn, $_POST['searchQuery']);
                            $searchType = $_POST['searchType'];

                            if ($searchType === "users") {
                                $query = "SELECT id, fname, lname, country, email, phone, username FROM users WHERE fname LIKE '%$searchQuery%' OR lname LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
                            } else {
                                $query = "SELECT username, email FROM admin WHERE username LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
                            }

                            $res = mysqli_query($conn, $query);

                            if (mysqli_num_rows($res) > 0) {
                                echo "<table class='table table-hover'>";
                                echo "<thead class='table-primary'>";
                                if ($searchType === "users") {
                                    echo "<tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Country</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Username</th>
                                        <th>Actions</th>
                                    </tr>";
                                } else {
                                    echo "<tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                    </tr>";
                                }
                                echo "</thead>";
                                echo "<tbody>";
                                
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>";
                                    if ($searchType === "users") {
                                        echo "
                                        <td>{$row['id']}</td>
                                        <td>{$row['fname']}</td>
                                        <td>{$row['lname']}</td>
                                        <td>{$row['country']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['phone']}</td>
                                        <td>{$row['username']}</td>
                                        <td>
                                            <div class='btn-group'>
                                                <form action='edit_user.php' method='POST'>
                                                    <input type='hidden' name='edit_id' value='{$row['id']}'>
                                                    <button type='submit' name='edituser_btn' class='btn btn-success btn-sm'>Edit</button>
                                                </form>
                                                <form action='delete_user.php' method='POST'>
                                                    <input type='hidden' name='delete_id' value='{$row['id']}'>
                                                    <button type='submit' name='deleteuser_btn' class='btn btn-danger btn-sm'>Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                        ";
                                    } else {
                                        echo "<td>{$row['username']}</td>";
                                        echo "<td>{$row['email']}</td>";
                                    }
                                    echo "</tr>";
                                }

                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo "<p class='text-center text-danger'>No results found for '$searchQuery'.</p>";
                            }
                        }
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
