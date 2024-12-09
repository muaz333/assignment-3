
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Form Validation</title>
</head>
<body class="container mt-5">
    <h1>Product Form</h1>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= $_POST['name'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= $_POST['title'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="<?= $_POST['quantity'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= $_POST['price'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
</html>
<?php
$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (empty($_POST['name'])) {
        $errors[] = "Name is required.";
    } else {
        $data['name'] = htmlspecialchars($_POST['name']);
    }

    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $data['email'] = htmlspecialchars($_POST['email']);
    }

    if (empty($_POST['title'])) {
        $errors[] = "Title is required.";
    } else {
        $data['title'] = htmlspecialchars($_POST['title']);
    }

    if (empty($_POST['quantity']) || $_POST['quantity'] < 1) {
        $errors[] = "Quantity must be at least 1.";
    } else {
        $data['quantity'] = intval($_POST['quantity']);
    }

    if (empty($_POST['price']) || !is_numeric($_POST['price'])) {
        $errors[] = "Price must be a valid number.";
    } else {
        $data['price'] = floatval($_POST['price']);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extensions = ['jpg'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Only JPG images are allowed.";
        } elseif ($_FILES['image']['size'] > 5242880) { 
            $errors[] = "Image size must be less than 5MB.";
        } else {
            $data['image'] = htmlspecialchars($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $_FILES['image']['name']);
        }
    } else {
        $errors[] = "Image is required.";
    }

    if (empty($errors)) {
        echo "<div class='alert alert-success'>Form submitted successfully!</div>";
        echo "<h4>Submitted Data:</h4>";
        echo "<p><strong>Name:</strong> {$data['name']}</p>";
        echo "<p><strong>Email:</strong> {$data['email']}</p>";
        echo "<p><strong>Title:</strong> {$data['title']}</p>";
        echo "<p><strong>Quantity:</strong> {$data['quantity']}</p>";
        echo "<p><strong>Price:</strong> {$data['price']}</p>";
        echo "<p><strong>Image:</strong> <img src='uploads/{$data['image']}' alt='Uploaded Image' class='img-fluid'></p>";
    }
}
?>

