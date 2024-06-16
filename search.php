<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "search_engine";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function booleanSearch($conn, $query) {
    $sql = "SELECT * FROM documents WHERE content LIKE '%$query%'";
    $result = $conn->query($sql);
    return $result;
}

function extendedBooleanSearch($conn, $query) {
    // Implement extended boolean search logic
}

function vectorSearch($conn, $query) {
    // Implement vector search logic
}

$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $_POST['query'];
    $searchType = $_POST['search_type'];

    switch ($searchType) {
        case 'boolean':
            $searchResults = booleanSearch($conn, $query);
            break;
        case 'extended_boolean':
            $searchResults = extendedBooleanSearch($conn, $query);
            break;
        case 'vector':
            $searchResults = vectorSearch($conn, $query);
            break;
    }
}
?>
<?php


function booleanModelSearch($query, $pdo) {
    $terms = explode(' ', $query);
    $queryParts = [];
    $params = [];

    foreach ($terms as $term) {
        if (strtolower($term) == 'and') {
            $queryParts[] = 'AND';
        } elseif (strtolower($term) == 'or') {
            $queryParts[] = 'OR';
        } elseif (strtolower($term) == 'not') {
            $queryParts[] = 'NOT';
        } else {
            $queryParts[] = 'content LIKE ?';
            $params[] = '%' . $term . '%';
        }
    }

    $sql = 'SELECT * FROM documents WHERE ' . implode(' ', $queryParts);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $algorithm = $_GET['algorithm'];

    if ($algorithm == 'boolean') {
        $results = booleanModelSearch($query, $pdo);
    }
}
?>



    <?php if ($searchResults): ?>
        <h2>Search Results</h2>
        <ul>
            <?php while ($row = $searchResults->fetch_assoc()): ?>
                <li>
                    <strong><?php echo $row ['filename']; ?></strong><br>
                    <?php echo $row['content']; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
