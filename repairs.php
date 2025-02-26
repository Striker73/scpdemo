<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Debug: Print any SQL errors
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repairs Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="img/logo.jpg" alt="Logo" class="img-fluid">
                </div>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Табло</span>
                    </a>
                </li>
                <li class="active">
                    <a href="repairs.php">
                        <i class="fas fa-wrench"></i>
                        <span>Ремонти</span>
                    </a>
                </li>
                <li>
                    <a href="clients.php">
                        <i class="fas fa-users"></i>
                        <span>Клиенти</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Изход</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </nav>

            <div class="container">
                <h2>Добави нов ремонт</h2>
                
                <!-- Add Repair Form -->
                <form method="POST" action="process_repair.php">
                    <input type="hidden" name="action" value="add_repair">
                    
                    <div class="form-group">
                        <label for="brand_id">Избери марка:</label>
                        <select name="brand_id" id="brand_id" class="form-control" required>
                            <option value="">Избери марка</option>
                            <?php
                            $brandQuery = "SELECT id, brand_name FROM brands ORDER BY brand_name";
                            $brands = $pdo->query($brandQuery);
                            while ($brand = $brands->fetch()) {
                                echo "<option value='" . htmlspecialchars($brand['id']) . "'>" . 
                                     htmlspecialchars($brand['brand_name']) . 
                                     "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Начална дата:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание:</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Добави ремонт</button>
                </form>

                <h2>Списък с ремонти</h2>
                <!-- Display Repairs -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Марка</th>
                                <th>Начална дата</th>
                                <th>Статус</th>
                                <th>Описание</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Make sure this is the exact query being used
                            $query = "SELECT r.*, b.brand_name 
                                     FROM repairs r 
                                     JOIN brands b ON r.brand_id = b.id 
                                     ORDER BY r.start_date DESC";
                            $repairs = $pdo->query($query);
                            
                            while ($repair = $repairs->fetch()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($repair['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['brand_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['start_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['description']) . "</td>";
                                echo "<td>
                                        <a href='edit_repair.php?id=" . $repair['id'] . "' class='btn btn-sm btn-primary'>Редактирай</a>
                                        <a href='delete_repair.php?id=" . $repair['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Сигурни ли сте?\")'>Изтрий</a>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>