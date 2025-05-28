<!-- index.php -->
<?php include '../includes/header.php'; ?>

<!-- donation box -->
<div class="donation-box">
    <form action="donate.php" method="POST">
        <select name="category" required>
            <option value="">Select Donation Category</option>
            <option value="Donate Old People">Donate Old People</option>
            <option value="Donate Poor People">Donate Poor People</option>
            <option value="Donate Autisms">Donate Autisms</option>
            <option value="Donate Jobless People">Donate Jobless People</option>
            <option value="Donate for Festival">Donate for Festival</option>
        </select>

        <input type="text" name="donator_number" placeholder="Your Phone Number" required>

        <input type="number" name="amount" placeholder="Amount to Donate (BDT)" required>

        <button type="submit">Donate with SSLCommerz</button>
    </form>
</div>
<!-- images slider -->
<div class="slider-box">
    <div class="slider">
        <img src="../assets/images/image1.jpg" alt="Slide 1">
        <img src="../assets/images/image2.jpg" alt="Slide 2">
        <img src="../assets/images/image3.jpg" alt="Slide 3">
        <img src="../assets/images/image4.jpg" alt="Slide 4">
        <img src="../assets/images/image5.jpg" alt="Slide 5">
        <img src="../assets/images/image6.jpg" alt="Slide 6">
        <img src="../assets/images/image7.jpg" alt="Slide 7">
    </div>
</div>

<link rel="stylesheet" href="../assets/css/home.css">
<script src="../assets/js/home.js"></script>
<?php include '../includes/footer.php'; ?>
