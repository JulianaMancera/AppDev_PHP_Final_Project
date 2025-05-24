<?php
include('database.php');

// Get ID from query string
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: main.php');
    exit;
}

// Select statement with placeholder for id
$sql = 'SELECT * FROM info WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$info = $stmt->fetch();

if (!$info) {
    header('Location: main.php');
    exit;
}

// Check if the image file exists and is readable
$imageExists = $info['pic'] && file_exists($info['pic']) && is_readable($info['pic']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Applicant Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Same styles as provided in the original file */
        body {
            background: linear-gradient(135deg, #1e3a8a, #60a5fa);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card-container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            transition: transform 0.3s ease;
        }
        .card-container:hover {
            transform: translateY(-5px);
        }
        h1 {
            color: #1e40af;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .info-label {
            color: #1e40af;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #1f2937;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            word-break: break-word;
        }
        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            transform: scale(1.05);
        }
        .btn-secondary {
            background: #6b7280;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-secondary:hover {
            background: #4b5563;
            transform: scale(1.05);
        }
        .btn-danger {
            background: #dc2626;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-danger:hover {
            background: #b91c1c;
            transform: scale(1.05);
        }
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        .img-preview {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 1rem;
            border-radius: 8px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .alert {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .card-container {
                padding: 1.5rem;
            }
            .info-value {
                font-size: 1rem;
            }
            .btn-primary, .btn-secondary, .btn-danger {
                padding: 0.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container card-container">
        <h1>Selected Applicant Record</h1>
        <?php if ($info['pic'] && !$imageExists): ?>
            <div class="alert alert-warning">Image file is missing or inaccessible: <?= htmlspecialchars($info['pic']) ?></div>
        <?php elseif ($imageExists): ?>
            <img src="<?= htmlspecialchars($info['pic']) ?>" alt="Applicant Photo" class="img-preview">
        <?php else: ?>
            <div class="alert alert-info">No photo available.</div>
        <?php endif; ?>
        <div class="info-group">
            <div class="info-label">Name</div>
            <div class="info-value"><?= htmlspecialchars($info['name']) ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Company</div>
            <div class="info-value"><?= htmlspecialchars($info['company']) ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Position</div>
            <div class="info-value"><?= htmlspecialchars($info['position']) ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Email</div>
            <div class="info-value"><?= htmlspecialchars($info['email']) ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Phone</div>
            <div class="info-value"><?= htmlspecialchars($info['phone']) ?></div>
        </div>
        <div class="button-group">
            <a href="index.php" class="btn btn-secondary">Back to Main</a>
            <a href="edit.php?id=<?= htmlspecialchars($info['id']) ?>" class="btn btn-primary">Edit</a>
            <form action="delete.php" method="POST">
                <input type="hidden" name="_method" value="delete">
                <input type="hidden" name="id" value="<?= htmlspecialchars($info['id']) ?>">
                <input type="submit" name="submit" value="Delete" class="btn btn-danger">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>