<?php include '../includes/header.php'; ?>
<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'assunnah';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch ongoing projects
$sql = "SELECT title, description FROM ongoing_project";
$result = $conn->query($sql);
$projects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[$row['title']] = $row['description'];
    }
}
$conn->close();

// Default project to display (first project in the list)
$default_project = !empty($projects) ? array_key_first($projects) : "";
?>

<!DOCTYPE html>
<html lang="en">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}
.head {
    background-color: #008e48;
    color: white;
    text-align: center;
    padding: 1em;
}
.container {
    display: flex;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
.sidebar {
    width: 200px;
    background-color: #fff;
    padding: 20px;
    border-right: 1px solid #ddd;
}
.sidebar button {
    display: block;
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: none;
    background-color: transparent; /* No background color */
    cursor: pointer;
    text-align: left;
    color: #000; /* Normal text color */
}
.sidebar button.active {
    color: #27ae60; /* Green color for active button */
}
.sidebar button:hover {
    color: #27ae60; /* Green hover effect */
}
.content {
    flex-grow: 1;
    padding: 20px;
    background-color: #fff;
    margin-left: 20px;
    border-radius: 5px;
}
.content h2 {
    margin-top: 0;
    color: #000; /* Black for description title */
}
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ongoing Projects - As-Sunnah Foundation</title>
    
 
</head>
<body>
    <div class ="head">
        <h1>ONGOING PROJECTS</h1>
</div>

    <div class="container">
        <div class="sidebar">
            <?php foreach (array_keys($projects) as $project) { ?>
                <button class="<?php echo ($default_project === $project) ? 'active' : ''; ?>" onclick="showProject('<?php echo htmlspecialchars($project); ?>')"><?php echo htmlspecialchars($project); ?></button>
            <?php } ?>
        </div>
        <div class="content" id="contentArea">
            <?php if ($default_project) { ?>
                <h2><?php echo htmlspecialchars($default_project); ?></h2>
                <?php echo nl2br(htmlspecialchars($projects[$default_project])); ?>
            <?php } else { echo "No ongoing projects found."; } ?>
        </div>
    </div>

    <script>
        function showProject(project) {
            const contentArea = document.getElementById('contentArea');
            const buttons = document.querySelectorAll('.sidebar button');
            
            // Remove active class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            // Add active class to the clicked button
            buttons.forEach(btn => {
                if (btn.textContent === project) {
                    btn.classList.add('active');
                }
            });

            <?php
            foreach ($projects as $key => $value) {
                echo "if (project === '" . htmlspecialchars($key) . "') contentArea.innerHTML = `<h2>" . htmlspecialchars($key) . "</h2>" . addslashes(nl2br(htmlspecialchars($value))) . "`;\n";
            }
            ?>
        }
    </script>
</body>
</html>
<?php include '../includes/footer.php'; ?>