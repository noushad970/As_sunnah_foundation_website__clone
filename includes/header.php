<!-- header.php -->
<style>
    /* style.css */


.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
}
.logo {
    display: flex;
    align-items: center;
}
.logo img {
    height: 60px;
    margin-right: 10px;
}
.logo .title h1 {
    margin: 0;
    font-size: 20px;
    color: #1a472a;
}
.logo .title p {
    margin: 0;
    font-size: 12px;
    color: #555;
}
.top-actions {
    display: flex;
    align-items: center;
}
.social-icons a {
    margin: 0 5px;
    color: #333;
    font-size: 16px;
}
.language-buttons button {
    margin: 0 2px;
    padding: 5px 10px;
    background-color: #0a773d;
    color: white;
    border: none;
    cursor: pointer;
}
.account-buttons .btn {
    margin: 0 5px;
    padding: 7px 12px;
    background-color: #0a773d;
    color: white;
    text-decoration: none;
    border-radius: 3px;
}
.main-nav {
    background-color: #0a4724;
}
.main-nav ul {
    list-style: none;
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 20px 0
}
.main-nav ul li {
    margin: 0 10px;
}
.main-nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 14px;
}

 /* header button hover */
/* General header link styles */
.main-nav a {
    position: relative;
    color: green;
    text-decoration: none;
    padding-bottom: 5px;
}

/* Add the underline on hover */
.main-nav a::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    height: 2px;
    width: 0;
    background-color: green;
    transition: width 0.3s ease;
}

.main-nav a:hover::after {
    width: 100%;
}

</style>
<header>
    <div class="top-bar">
        <div class="logo">
            <img src="../assets/images/logo.png" alt="As-Sunnah Foundation Logo">
            <div class="title">
                <h1>As-Sunnah Foundation Clone by Noushad</h1>
                <p>For Upliftment, With Sunnah</p>
            </div>
        </div>
        <div class="top-actions">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="mailto:info@example.com"><i class="fas fa-envelope"></i></a>
                <a href="tel:+880123456789"><i class="fas fa-phone"></i></a>
            </div>
            <div class="language-buttons">
            </div>
            <div class="account-buttons">
                <a href="../pages/adminsignin.php" class="btn">Admin</a>
                <a href="../pages/donate.php" class="btn">Donate</a>
            </div>
        </div>
    </div>
    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ongoing_project.php">Ongoing Programs</a></li>
            <li><a href="projects.php">Projects</a></li>
            <li><a href="members.php">Lifetime & Donor Member</a></li>
            <!-- <li><a href="gallery.php">Gallery</a></li> -->
            <li><a href="videos.php">Video</a></li>
            <li><a href="volunteer_registration.php">Volunteer Registration</a></li>
            <!-- <li><a href="news.php">News</a></li> -->
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
    </nav>
</header>
<script src="assets/js/script.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
