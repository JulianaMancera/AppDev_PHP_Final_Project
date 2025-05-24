<?php 
include('database.php');
require_once 'database.php';

// Prepare a SELECT statement
$stmt = $pdo->prepare('SELECT * FROM info');
$stmt->execute();
$info = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>OJT Application Portal</title>
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <style>
         body {
            background: linear-gradient(135deg, #6b48ff, #00c6ff);
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
         }
         .table-container {
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            margin: 2rem auto;
            transition: transform 0.3s ease;
         }
         .table-container:hover {
            transform: translateY(-5px);
         }
         .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
         }
         .table {
            border: none;
            min-width: 600px;
         }
         .table thead {
            background: #2563eb;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
         }
         .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
            white-space: nowrap;
         }
         .table tbody tr {
            transition: background 0.3s ease;
         }
         .table tbody tr:hover {
            background: #dbeafe;
            transform: scale(1.01);
         }
         .table a {
            color: #1d4ed8;
            font-weight: 500;
            text-decoration: none;
            position: relative;
         }
         .table a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #1d4ed8;
            transition: width 0.3s ease;
         }
         .table a:hover::after {
            width: 100%;
         }
         h2 {
            color: #1e40af;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
         }
         .description {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
         }
         .btn-create {
            background: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
         }
         .btn-create:hover {
            background: #1d4ed8;
            transform: scale(1.05);
         }
         .thumbnail {
            max-width: 50px;
            max-height: 50px;
            border-radius: 4px;
         }
         @media (max-width: 768px) {
            .table-container {
               padding: 1rem;
            }
            .table th, .table td {
               font-size: 0.9rem;
               padding: 0.5rem;
            }
            .btn-create {
               padding: 0.5rem 1.5rem;
            }
            .description {
               font-size: 1rem;
            }
            .thumbnail {
               max-width: 40px;
               max-height: 40px;
            }
         }
      </style>
   </head>
   <body>
      <div class="container table-container">
         <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">OJT Application Information</h2>
            <a href="create.php" class="btn btn-create">Create</a>
         </div>
         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Photo</th>
                     <th>Name</th>
                     <th>Company</th>
                     <th>Position</th>
                     <th>Email</th>
                     <th>Phone</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($info as $infos):?>
                     <tr>
                        <td>
                           <?php if ($infos['pic']): ?>
                              <img src="<?= htmlspecialchars($infos['pic']) ?>" alt="Photo" class="thumbnail">
                           <?php else: ?>
                              No Photo
                           <?php endif; ?>
                        </td>
                        <td><a href="selected.php?id=<?= htmlspecialchars($infos['id']) ?>"><?= htmlspecialchars($infos['name']) ?></a></td>
                        <td><?= htmlspecialchars($infos['company']) ?></td>
                        <td><?= htmlspecialchars($infos['position']) ?></td>
                        <td><?= htmlspecialchars($infos['email']) ?></td>
                        <td><?= htmlspecialchars($infos['phone']) ?></td>
                     </tr>
                  <?php endforeach;?>
               </tbody>
            </table>
            <a href="index.php" class="btn btn-secondary" style="float: right;">Back</a>
         </div>
      </div>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </body>
</html>