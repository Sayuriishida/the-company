<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('location: ../views');
}

require "../classes/User.php";

$user_obj = new User;
$all_users = $user_obj->getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Dashboard</title>
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark" style="margin-bottom: 80px">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">
                <h1 class="h3"><i class="fa-solid fa-bolt"></i> The Company</h1>
            </a>
            <div class="navbar-nav">
                <span class="navbar-text"><?= $_SESSION['full_name'] ?></span>
                <form action="../actions/logout.php" method="post" class="d-flex ms-2">
                    <button type="submit" class="text-danger bg-transparent border-0">Log out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="row justify-content-center gx-0">
        <div class="col-6">
            <h2 class="text-center"><i class="fa-solid fa-users"></i> User List</h2>

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>
                            <!-- for photo -->
                        </th>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Username</th>
                        <th>
                            <!-- Action Buttons -->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($user = $all_users->fetch_assoc()) {
                    ?>
                    <tr>
                        <td>
                            <?php if ($user['photo']) { ?>
                            <img src="../assets/images/<?= $user['photo'] ?>" alt=""
                                class="d-block mx-auto dashboard-photo">
                            <?php } else { ?>
                            <i class="fa-solid fa-circle-user text-secondary d-block text-center dashboard-icon"></i>
                            <?php } ?>
                        </td>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['last_name'] ?></td>
                        <td><?= $user['first_name'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td>
                            <?php if ($_SESSION['id'] == $user['id']) { ?>
                            <a href="edit-user.php" class="btn btn-outline-warning border-0 btn-sm" title="Edit"><i
                                    class="fa-solid fa-pen"></i></a>
                            <a href="delete-user.php" class="btn btn-outline-danger border-0 btn-sm" title="delete"><i
                                    class="fa-solid fa-minus"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>