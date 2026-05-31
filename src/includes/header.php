<?php require_once __DIR__ . '/session.php'; ?>
<!DOCTYPE html>
<html lang="sq">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PostaWeb - Platforma e Sherbimit Postar</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<nav class="navbar">

    <div class="nav-container">

      

        <a href="index.php" class="nav-logo">

            <i class="fa-solid fa-box-open"></i>

            <span>PostaWeb</span>

        </a>

        

        <ul class="nav-links">


            <li>

                <a href="index.php#hero">

                    <i class="fa-solid fa-house"></i>

                </a>

            </li>

          

            <li>

                <a href="index.php#about">

                    <i class="fa-solid fa-circle-info"></i>

                </a>

            </li>

            <li>

                <a href="index.php#contact">

                    <i class="fa-solid fa-envelope"></i>

                </a>

            </li>

           

            <li class="account-wrapper">

                <a href="javascript:void(0)" id="navAccount">

                    <i class="fa-solid fa-user"></i>

                </a>

                <?php if (isLoggedIn()): ?>

                <div id="accountDropdown" class="dropdown" style="display:none;">

                    <p class="dropdown-name">

                        <?= htmlspecialchars($_SESSION['full_name']) ?>

                    </p>

                    <p class="dropdown-email">

                        <?= htmlspecialchars($_SESSION['email']) ?>

                    </p>

                    <hr>

                    <a href="<?= $_SESSION['role'] === 'admin' ? 'admin.php' : 'dashboard.php' ?>">

                        Dashboard

                    </a>
<?php if ($_SESSION['role'] !== 'admin'): ?>
<a href="profile.php">Profili Im</a>
<?php endif; ?>

                    <a href="api/auth/logout.php" class="logout">

                        Dil

                    </a>

                </div>

                <?php endif; ?>

            </li>

        </ul>

    </div>

</nav>