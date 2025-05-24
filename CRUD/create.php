<?php
include('database.php');

// Ensure uploads directory exists
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $company = htmlspecialchars(trim($_POST['company'] ?? ''));
    $position = htmlspecialchars(trim($_POST['position'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $pic = null;

    // Validate inputs
    if (empty($name) || empty($company) || empty($position) || empty($email) || empty($phone)) {
        $uploadError = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $uploadError = 'Invalid email format.';
    } elseif (!preg_match('/^\+?[1-9]\d{1,14}$/', $phone)) {
        $uploadError = 'Invalid phone number format.';
    } else {
        // Handle photo upload (required)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            $file = $_FILES['photo'];

            if (!in_array($file['type'], $allowedTypes)) {
                $uploadError = 'Invalid file type. Only JPEG, PNG, and GIF are allowed.';
            } elseif ($file['size'] > $maxSize) {
                $uploadError = 'File size exceeds 2MB limit.';
            } else {
                $fileName = uniqid() . '-' . basename($file['name']);
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $pic = $uploadPath;
                } else {
                    $uploadError = 'Failed to upload photo.';
                }
            }
        } else {
            $uploadError = 'Photo is required.';
        }

        if (!$uploadError) {
            $sql = 'INSERT INTO info (name, company, position, email, phone, pic) VALUES (:name, :company, :position, :email, :phone, :pic)';
            $stmt = $pdo->prepare($sql);
            $params = [
                'name' => $name,
                'company' => $company,
                'position' => $position,
                'email' => $email,
                'phone' => $phone,
                'pic' => $pic
            ];
            try {
                $stmt->execute($params);
                header('Location: main.php');
                exit;
            } catch (PDOException $e) {
                $uploadError = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Applicant</title>
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
        .form-container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            transition: transform 0.3s ease;
        }
        .form-container:hover {
            transform: translateY(-5px);
        }
        h1 {
            color: #1e40af;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-label {
            color: #1e40af;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-control {
            border: 2px solid #2563eb;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 8px rgba(29, 78, 216, 0.4);
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
        .form-group {
            margin-bottom: 1.5rem;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .alert {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }
            .form-control {
                font-size: 0.9rem;
            }
            .btn-primary, .btn-secondary {
                padding: 0.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h1>Create New Applicant Record</h1>
        <?php if ($uploadError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($uploadError) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="form-label">Enter Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="company" class="form-label">Enter Company</label>
                <input type="text" name="company" id="company" class="form-control" value="<?= htmlspecialchars($_POST['company'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="position" class="form-label">Enter Position</label>
                <input type="text" name="position" id="position" class="form-control" value="<?= htmlspecialchars($_POST['position'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Enter Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Enter Phone #</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="photo" class="form-label">Upload Photo</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/gif" required>
            </div>
            <div class="button-group">
                <input type="submit" name="submit" value="Add" class="btn btn-primary">
                <a href="main.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>