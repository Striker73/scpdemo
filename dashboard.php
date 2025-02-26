<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Табло";
include 'includes/header.php';
?>

<!-- Include sidebar -->
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="top-bar">
        <div class="user-info">
            <img src="https://via.placeholder.com/40" alt="User Avatar">
            <span>Здравейте, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Изход
        </a>
    </div>

    <div class="dashboard-content">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Активни ремонти</h3>
                <div class="number">12</div>
            </div>
            <div class="stat-card">
                <h3>Завършени днес</h3>
                <div class="number">5</div>
            </div>
            <div class="stat-card">
                <h3>Нови клиенти</h3>
                <div class="number">3</div>
            </div>
            <div class="stat-card">
                <h3>Приходи днес</h3>
                <div class="number">2,450 лв.</div>
            </div>
        </div>

        <div class="recent-activity">
            <h2>Последна активност</h2>
            <ul class="activity-list">
                <li class="activity-item">
                    <i class="fas fa-check"></i>
                    Завършен ремонт - BMW X5 (СА 1234 ВР)
                </li>
                <li class="activity-item">
                    <i class="fas fa-user-plus"></i>
                    Нов клиент - Иван Петров
                </li>
                <li class="activity-item">
                    <i class="fas fa-car"></i>
                    Нов автомобил за ремонт - Audi A4 (СВ 5678 АН)
                </li>
                <li class="activity-item">
                    <i class="fas fa-tools"></i>
                    Започнат ремонт - Mercedes C200 (СА 9012 МР)
                </li>
            </ul>
        </div>

        <!-- Recent Repairs Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Recent Repairs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Brand</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT r.*, b.brand_name 
                                     FROM repairs r 
                                     JOIN brands b ON r.brand_id = b.id 
                                     ORDER BY r.start_date DESC 
                                     LIMIT 5"; // Shows only the 5 most recent repairs
                            $repairs = $pdo->query($query);
                            
                            while ($repair = $repairs->fetch()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($repair['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['brand_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['start_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($repair['description']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="repairs.php" class="btn btn-primary">View All Repairs</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>