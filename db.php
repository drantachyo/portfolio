<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";

// Подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Забираем данные
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$service_type = $conn->real_escape_string($_POST['service_type']);
$urgency = $conn->real_escape_string($_POST['urgency']);

// Вставляем
$sql = "INSERT INTO orders (name, email, service_type, urgency) 
        VALUES ('$name', '$email', '$service_type', '$urgency')";

$success = $conn->query($sql) === TRUE;
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? 'Order Confirmed' : 'Order Error'; ?> - ReviewFlow</title>
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
            --text: #111827;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(-45deg, #f9fafb, #f3f4f6, #e0fdf4, #dbeafe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(-45deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(0deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.6s ease-out;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .status-icon.success {
            background: rgba(16, 185, 129, 0.1);
            animation: checkmark 0.8s ease-out;
        }

        .status-icon.error {
            background: rgba(239, 68, 68, 0.1);
        }

        h1 {
            font-family: 'Sora', sans-serif;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .message {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        .order-details {
            background: var(--white);
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-light);
        }

        .detail-value {
            color: var(--text);
            font-weight: 500;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
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
            transform: translateY(-2px);
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 2px solid var(--danger);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .error-box h3 {
            color: var(--danger);
            margin-bottom: 0.5rem;
        }

        .error-box p {
            color: var(--text-light);
        }

        @media (max-width: 600px) {
            .container {
                padding: 2rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <div class="status-icon success">✓</div>
            <h1>Order Confirmed! 🎉</h1>
            <p class="message">Your portfolio review order has been successfully created. We'll review your portfolio and send feedback to your email.</p>

            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($service_type); ?> Review</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Turnaround:</span>
                    <span class="detail-value"><?php echo $urgency === 'Urgent' ? 'Express (24 hours)' : 'Standard (3 days)'; ?></span>
                </div>
            </div>

            <p class="message" style="font-size: 0.95rem; color: var(--text-light); margin-bottom: 0;">Check your email for confirmation and next steps.</p>

            <div class="actions">
                <a href="index.html" class="btn btn-primary">← Back Home</a>
                <a href="booking.html" class="btn btn-secondary">New Order</a>
            </div>

        <?php else: ?>
            <div class="status-icon error">⚠️</div>
            <h1>Order Error</h1>
            <p class="message">Something went wrong while processing your order. Please try again.</p>

            <div class="error-box">
                <h3>Error Details:</h3>
                <p>Unable to save your order to the database. Please contact support if the problem persists.</p>
            </div>

            <div class="actions">
                <a href="booking.html" class="btn btn-primary">← Try Again</a>
                <a href="index.html" class="btn btn-secondary">Back Home</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>