<?php
include('database.php');

// Get ID from query string
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: main.php');
    exit;
}

// Fetch applicant details
$sql = 'SELECT * FROM info WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$info = $stmt->fetch();

if (!$info) {
    header('Location: main.php');
    exit;
}

$uploadError = '';
$uploadSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $file = $_FILES['photo'];

        // Validate file type and size
        if (!in_array($file['type'], $allowedTypes)) {
            $uploadError = 'Invalid file type. Only JPEG, PNG, and GIF are allowed.';
        } elseif ($file['size'] > $maxSize) {
            $uploadError = 'File size exceeds 2MB limit.';
        } else {
            $uploadDir = 'uploads/';
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Update database with photo path
                $sql = 'UPDATE info SET photo_path = :photo_path WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'photo_path' => $uploadPath,
                    'id' => $id
                ]);
                $uploadSuccess = 'Photo uploaded successfully!';
            } else {
                $uploadError = 'Failed to upload photo. Please try again.';
            }
        }
    } else {
        $uploadError = 'No file selected.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
   <head>   
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Upload Applicant Photo</title>
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <style>
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
            flex-wrap: wrap;
         }
         .alert {
            margin-bottom: 1.5rem;
            text-align: center;
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
         <h1>Upload Photo for <?= htmlspecialchars($info['name']) ?></h1>
         <?php if ($uploadError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($uploadError) ?></div>
         <?php endif; ?>
         <?php if ($uploadSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($uploadSuccess) ?></div>
         <?php endif; ?>
         <?php if ($info['photo_path']): ?>
            <img src="<?= htmlspecialchars($info['photo_path']) ?>" alt="Applicant Photo" class="img-preview">
         <?php endif; ?>
         <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
               <label for="photo" class="form-label">Select Photo</label>
               <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/gif" required>
            </div>
            <div class="button-group">
               <input type="submit" name="submit" value="Upload" class="btn btn-primary">
               <a href="selected.php?id=<?= htmlspecialchars($info['id']) ?>" class="btn btn-secondary">Back</a>
            </div>
         </form>
      </div>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </body>
</html>