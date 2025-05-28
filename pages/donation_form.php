
<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Form - As-Sunnah Foundation</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .head {
            background-color: #008e48;
            color: white;
            text-align: center; font-size: 34px;font-weight: bold;
            padding: 1em;
        }
        .container {
            margin-top: 70px;
            margin-left:400px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }
        .container h2 {
            color: #1a5c38;
            margin-bottom: 20px;
        }
        .container p {
            color: #555;
            margin-bottom: 20px;
        }
        .donation-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .donation-box div {
            flex: 1;
            margin: 0 10px;
        }
        .donation-box input, .donation-box select {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            height: 50px;
            box-sizing: border-box;
        }
        .donation-box select {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
        .donate-button {
            background-color: #1a5c38;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .donate-button:hover {
            background-color: #145f30;
        }
        @media (max-width: 600px) {
            .container {
                width: 90%;
                margin-top: 70px;
            }
            .donation-box {
                flex-direction: column;
            }
            .donation-box div {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="head">
        DONATION
    </div>

    <div class="container">
        <?php
        $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
        ?>
        <h2><?php echo $category; ?></h2>
        <p>Thank you for your interest in donating to <?php echo $category; ?>. Please fill in the details below to proceed with your donation.</p>
        <form method="POST" action="">
            <div class="donation-box">
                <div>
                    <label>Donation Category</label>
                    <select name="category" disabled>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    </select>
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                </div>
                <div>
                    <label>Donator Number</label>
                    <input type="text" name="donator_number" placeholder="Enter your number" required>
                </div>
                <div>
                    <label>Donation Amount (BDT)</label>
                    <input type="number" name="donation_amount" placeholder="Enter amount" required>
                </div>
            </div>
            <button type="submit" name="donate" class="donate-button">Donate</button>
        </form>

        <?php
        if (isset($_POST['donate'])) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "assunnah";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Create table if it doesn't exist
            $sql = "CREATE TABLE IF NOT EXISTS donations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category VARCHAR(100),
                donator_number VARCHAR(20),
                amount DECIMAL(10, 2),
                payment_status VARCHAR(20) DEFAULT 'Pending'
            )";
            $conn->query($sql);

            // Prepare and bind
            $category = $_POST['category'];
            $donator_number = $_POST['donator_number'];
            $amount = $_POST['donation_amount'];
            $payment_status = 'Pending';

            $stmt = $conn->prepare("INSERT INTO donations (category, donator_number, amount, payment_status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $category, $donator_number, $amount, $payment_status);

            if ($stmt->execute()) {
                echo "<p style='color:green;text-align:center;'>Donation recorded successfully! Payment status: Pending</p>";
            } else {
                echo "<p style='color:red;text-align:center;'>Error recording donation: " . $conn->error . "</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>


<?php include '../includes/footer.php'; ?>