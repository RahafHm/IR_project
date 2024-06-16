<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\IOFactory;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "search_engine";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $language = $_POST['language'];
    $files = $_FILES['files'];

    for ($i = 0; $i < count($files['name']); $i++) {
        $filename = $files['name'][$i];
        $fileTmpName = $files['tmp_name'][$i];
        $phpWord = IOFactory::load($fileTmpName);
        $content = '';

        foreach ($phpWord->getSections() as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if (method_exists($element, 'getText')) {
                    $content .= $element-> getText() . ' ';
                }
            }
        }

        $stmt = $conn->prepare("INSERT INTO documents (filename, content, language) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $content, $language);
        $stmt->execute();
    }

    echo "Files uploaded and indexed successfully!";
}
?>

