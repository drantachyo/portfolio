<?php
$conn = new mysqli("localhost", "root", "", "portfolio_db");

// ===== CREATE =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $service_type = $conn->real_escape_string($_POST['service_type']);
        $urgency = $conn->real_escape_string($_POST['urgency']);
        
        $sql = "INSERT INTO orders (name, email, service_type, urgency) VALUES ('$name', '$email', '$service_type', '$urgency')";
        $conn->query($sql);
        header("Location: admin.php");
        exit();
    }
    
    if ($_POST['action'] === 'update') {
        $id = intval($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $service_type = $conn->real_escape_string($_POST['service_type']);
        $urgency = $conn->real_escape_string($_POST['urgency']);
        
        $sql = "UPDATE orders SET name='$name', email='$email', service_type='$service_type', urgency='$urgency' WHERE id=$id";
        $conn->query($sql);
        header("Location: admin.php");
        exit();
    }
}

// ===== DELETE =====
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $conn->query("DELETE FROM orders WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// ===== READ =====
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$orders = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$editId = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$editOrder = null;
if ($editId) {
    $editResult = $conn->query("SELECT * FROM orders WHERE id=$editId");
    $editOrder = $editResult->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin CRUD Dashboard - ReviewFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #1f2937;
            --text: #000000;
            --text-light: #333333;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(-45deg, #f9fafb, #f3f4f6, #e0fdf4, #dbeafe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--text);
            min-height: 100vh;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        h1 {
            font-family: 'Sora', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .header-desc {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .crud-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-card h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: var(--border);
            color: var(--text);
        }

        .btn-secondary:hover {
            background: var(--text-light);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .orders-section {
            margin-top: 3rem;
        }

        .orders-grid {
            display: grid;
            gap: 1.5rem;
        }

        .order-card {
            background: var(--white);
            border-radius: 12px;
            border: 2px solid var(--border);
            padding: 1.5rem;
            transition: all 0.3s ease;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 2rem;
            align-items: start;
        }

        .order-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .order-field {
            display: flex;
            gap: 0.75rem;
        }

        .order-field label {
            font-weight: 600;
            color: var(--text-light);
            min-width: 80px;
            margin: 0;
        }

        .order-field value {
            color: var(--text);
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            width: fit-content;
        }

        .status-done {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-normal {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .status-urgent {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .order-actions {
            display: flex;
            gap: 0.75rem;
            flex-direction: column;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-light);
        }

        .empty-state h2 {
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .edit-badge {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        @media (max-width: 1024px) {
            .crud-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .order-card {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: row;
            }

            .header-desc {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">ReviewFlow</div>
            <ul class="nav-links">
                <li><a href="index.html">← Back to Site</a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <h1>📊 CRUD Dashboard</h1>
        <p class="header-desc">Create, Read, Update, Delete orders</p>

        <!-- CRUD SECTION -->
        <div class="crud-layout">
            <!-- CREATE FORM -->
            <div class="form-card">
                <h2>➕ Create Order</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="form-group">
                        <label for="create-name">Name</label>
                        <input type="text" id="create-name" name="name" required placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label for="create-email">Email</label>
                        <input type="email" id="create-email" name="email" required placeholder="john@example.com">
                    </div>

                    <div class="form-group">
                        <label for="create-service">Service</label>
                        <select id="create-service" name="service_type" required>
                            <option value="">Select service...</option>
                            <option value="Basic">Basic Review</option>
                            <option value="Deep">Deep Analysis</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="create-urgency">Urgency</label>
                        <select id="create-urgency" name="urgency" required>
                            <option value="Normal">Normal</option>
                            <option value="Urgent">Urgent</option>
                            <option value="DONE">Done</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Order</button>
                    </div>
                </form>
            </div>

            <!-- EDIT FORM -->
            <div class="form-card">
                <h2><?php echo $editOrder ? '✏️ Edit Order' : '✏️ No Order Selected'; ?></h2>
                <?php if ($editOrder): ?>
                    <div class="edit-badge">Editing Order #<?php echo $editOrder['id']; ?></div>
                    <form method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $editOrder['id']; ?>">
                        
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" id="edit-name" name="name" required value="<?php echo htmlspecialchars($editOrder['name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" id="edit-email" name="email" required value="<?php echo htmlspecialchars($editOrder['email']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="edit-service">Service</label>
                            <select id="edit-service" name="service_type" required>
                                <option value="Basic" <?php echo $editOrder['service_type'] === 'Basic' ? 'selected' : ''; ?>>Basic Review</option>
                                <option value="Deep" <?php echo $editOrder['service_type'] === 'Deep' ? 'selected' : ''; ?>>Deep Analysis</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit-urgency">Urgency</label>
                            <select id="edit-urgency" name="urgency" required>
                                <option value="Normal" <?php echo $editOrder['urgency'] === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                <option value="Urgent" <?php echo $editOrder['urgency'] === 'Urgent' ? 'selected' : ''; ?>>Urgent</option>
                                <option value="DONE" <?php echo $editOrder['urgency'] === 'DONE' ? 'selected' : ''; ?>>Done</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update Order</button>
                            <a href="admin.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p style="color: var(--text-light); margin-top: 2rem; text-align: center;">Click "Edit" on any order to modify it here</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- READ SECTION -->
        <div class="orders-section">
            <h2>📋 All Orders</h2>
            <div class="orders-grid">
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <h2>No orders yet</h2>
                        <p>Create your first order using the form above</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-info">
                                <div>
                                    <div class="order-field">
                                        <label>ID:</label>
                                        <value>#<?php echo $order['id']; ?></value>
                                    </div>
                                    <div class="order-field">
                                        <label>Name:</label>
                                        <value><?php echo htmlspecialchars($order['name']); ?></value>
                                    </div>
                                    <div class="order-field">
                                        <label>Email:</label>
                                        <value><?php echo htmlspecialchars($order['email']); ?></value>
                                    </div>
                                    <div class="order-field">
                                        <label>Service:</label>
                                        <value><?php echo htmlspecialchars($order['service_type']); ?></value>
                                    </div>
                                    <div class="order-field">
                                        <label>Status:</label>
                                        <span class="status-badge <?php echo 'status-' . strtolower($order['urgency']); ?>">
                                            <?php echo htmlspecialchars($order['urgency']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="?edit=<?php echo $order['id']; ?>" class="btn btn-primary btn-small">✏️ Edit</a>
                                <a href="?del=<?php echo $order['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Delete this order?')">🗑️ Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>