<?php
// Define articles as an array
$articles = [
    ["title" => "Wisata Pantai Indah", "category" => "Pantai", "content" => "Nikmati keindahan pantai dengan pasir putih."],
    ["title" => "Wisata Buatan Seru", "category" => "Wisata Buatan", "content" => "Wahana seru untuk liburan keluarga."],
    ["title" => "Pendakian Gunung Merapi", "category" => "Gunung", "content" => "Pengalaman mendaki yang menantang di Gunung Merapi."],
    ["title" => "Alam Liar Kalimantan", "category" => "Alam", "content" => "Jelajahi alam liar di hutan Kalimantan."],
    ["title" => "Pemandian Air Panas Ciater", "category" => "Air Terjun", "content" => "Nikmati pemandian air panas di tengah alam."],
    ["title" => "Air Terjun Tumpak Sewu", "category" => "Air Terjun", "content" => "Keindahan air terjun di lereng gunung."],
];

// Function to send the uploaded image URL to the Flask API and get the predicted category
function getPredictedCategory($imagePath) {
    $flask_url = "http://127.0.0.1:5000/predict?image=" . urlencode($imagePath);
    $response = file_get_contents($flask_url);
    
    // Echo the full API response for debugging
    echo "<h3>API Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    // Decode and return the predicted category
    $data = json_decode($response, true);
    return $data['prediction'] ?? null;
}

// Handle file upload and filter articles based on predicted category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Ensure the /filter directory exists
    $upload_dir = __DIR__ . '/filter/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Save the uploaded file in the /filter directory
    $image_path = $upload_dir . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    
    // Create the URL for the Flask server
    $image_url = "http://yourdomain.com/filter/" . basename($_FILES['image']['name']);
    
    // Get the predicted category from Flask
    $predicted_category = getPredictedCategory($image_url);
    if ($predicted_category) {
        // Filter articles by the predicted category
        $filtered_articles = array_filter($articles, function ($article) use ($predicted_category) {
            return $article['category'] === $predicted_category;
        });
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Article Filter by Category</title>
</head>
<body>
    <h1>Articles</h1>

    <!-- Display all articles or filtered articles -->
    <?php
    $display_articles = isset($filtered_articles) ? $filtered_articles : $articles;
    foreach ($display_articles as $article) {
        echo "<h2>" . htmlspecialchars($article['title']) . "</h2>";
        echo "<p><strong>Category:</strong> " . htmlspecialchars($article['category']) . "</p>";
        echo "<p>" . htmlspecialchars($article['content']) . "</p><hr>";
    }
    ?>

    <!-- File upload form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Upload and Filter</button>
    </form>
</body>
</html>
