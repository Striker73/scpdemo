<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$page_title = "Клиенти";
include 'includes/header.php';
?>

<!-- Include sidebar -->
<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="top-bar">
        <h1>Управление на клиенти</h1>
        <button class="add-client-btn" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Добави клиент
        </button>
    </div>

    <div class="client-grid">
        <?php
        // Get all clients
        $stmt = $pdo->query("SELECT * FROM clients ORDER BY name");
        $clients = $stmt->fetchAll();
        ?>
        <?php foreach ($clients as $client): ?>
        <div class="client-card">
            <h3><?= htmlspecialchars($client['name']) ?></h3>
            <p><i class="fas fa-phone"></i> <?= htmlspecialchars($client['phone']) ?></p>
            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($client['email']) ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($client['address']) ?></p>
            <div class="client-actions">
                <button class="edit-btn" onclick="showEditModal(<?= $client['id'] ?>, '<?= htmlspecialchars($client['name']) ?>', '<?= htmlspecialchars($client['phone']) ?>', '<?= htmlspecialchars($client['email']) ?>', '<?= htmlspecialchars($client['address']) ?>')">
                    <i class="fas fa-edit"></i> Редактирай
                </button>
                <button class="delete-btn" onclick="deleteClient(<?= $client['id'] ?>)">
                    <i class="fas fa-trash"></i> Изтрий
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Add Client Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Добави нов клиент</h2>
            <form action="clients.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Име</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Имейл</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="address">
                </div>
                <button type="submit" class="add-client-btn">Добави</button>
                <button type="button" onclick="hideModal('addModal')" class="delete-btn">Отказ</button>
            </form>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Редактирай клиент</h2>
            <form action="clients.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="client_id" id="edit_client_id">
                <div class="form-group">
                    <label>Име</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" id="edit_phone" required>
                </div>
                <div class="form-group">
                    <label>Имейл</label>
                    <input type="email" name="email" id="edit_email">
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="address" id="edit_address">
                </div>
                <button type="submit" class="edit-btn">Запази</button>
                <button type="button" onclick="hideModal('editModal')" class="delete-btn">Отказ</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showAddModal() {
        document.getElementById('addModal').style.display = 'block';
    }

    function showEditModal(id, name, phone, email, address) {
        document.getElementById('edit_client_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_address').value = address;
        document.getElementById('editModal').style.display = 'block';
    }

    function hideModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function deleteClient(id) {
        if (confirm('Сигурни ли сте, че искате да изтриете този клиент?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'clients.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'client_id';
            idInput.value = id;
            
            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
</body>
</html>