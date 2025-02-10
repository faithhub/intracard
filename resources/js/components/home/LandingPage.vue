<template>
<div id="app">
    <!-- Navigation -->
    <header class="navbar">
        <div class="container">
            <a href="#" class="logo">
                <img src="@assets/logos/intracard.png" alt="Piggyvest Logo">
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Save</a></li>
                    <li><a href="#">Invest</a></li>
                    <li><a href="#">Stories</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Resources</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <button class="sign-in">Sign in</button>
                <button class="create-account">Create free account</button>
            </div>
            <div class="mobile-menu">
                <button class="menu-button">☰</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <span class="badge">The 2024 PiggyVest Savings Report</span>
                <h1>The Better Way to Save & Invest</h1>
                <p>
                    Piggyvest helps over 5 million customers achieve their financial goals
                    by helping them save and invest with ease.
                </p>
                <div class="download-buttons">
                    <button class="get-iphone">Get on iPhone</button>
                    <button class="get-android">Get on Android</button>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/pexels_ketut_subiyanto_4350099_bc5e069ebe/pexels_ketut_subiyanto_4350099_bc5e069ebe.avif" alt="Hero Image" />
            </div>
        </div>
    </section>

    <!-- Savings Section -->
    <section class="savings">
        <div class="container">
            <h2>Many ways to build your savings</h2>
            <p>Earn 12%-20% when you save with any of these PiggyVest plans.</p>
            <div class="savings-grid">
                <div class="savings-item blue">
                    <h3>Automated Savings</h3>
                    <p>Build a dedicated savings faster on your terms, automatically or manually.</p>
                    <span>Piggybank</span>
                    <img src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/iphonw_a485f92ad1/iphonw_a485f92ad1.avif" alt="Fixed Savings">
                </div>
                <div class="savings-item">
                    <h3>Fixed Savings</h3>
                    <p>Lock money away for a fixed duration without having access to it until maturity.</p>
                    <span>Safelock</span>
                    <img src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/i_Phone_14_1_d7b9154111/i_Phone_14_1_d7b9154111.png" alt="Fixed Savings Image" />
                </div>
                <div class="savings-item">
                    <h3>Goal-oriented Savings</h3>
                    <p>Reach all your savings goals faster. Save towards multiple goals on your own or with a group.</p>
                    <span>Target Savings</span>
                    <div class="image-container">
                        <img src="https://via.placeholder.com/100" alt="Fixed Savings">
                    </div>
                </div>
                <div class="savings-item">
                    <h3>Flex Naira</h3>
                    <p>Save, transfer, manage, organise, and withdraw your money at any time.</p>
                    <span>Flex Naira</span>
                    <div class="image-container">
                        <img src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/i_Phone_14_1_d7b9154111/i_Phone_14_1_d7b9154111.png" alt="Fixed Savings">
                    </div>
                </div>
                <div class="savings-item">
                    <h3>Flex Dollar</h3>
                    <p>Save and grow your money in foreign currencies such as Dollars.</p>
                    <span>Flex Dollar</span>
                    <div class="image-container">
                        <img src="https://via.placeholder.com/100" alt="Fixed Savings">
                    </div>
                </div>
                <div class="savings-item">
                    <h3>HouseMoney</h3>
                    <p>Plan for your rent and household expenses.</p>
                    <span>House Money</span>
                    <div class="image-container">
                        <img src="https://via.placeholder.com/100" alt="Fixed Savings">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2>Features</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <h3>Smart Savings</h3>
                    <p>Automate your savings and achieve your financial goals effortlessly.</p>
                </div>
                <div class="feature-item">
                    <h3>Investments</h3>
                    <p>Invest in low-risk opportunities and watch your money grow.</p>
                </div>
                <div class="feature-item">
                    <h3>Security</h3>
                    <p>Your funds are safe with industry-standard security measures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <h2>What Our Users Say</h2>
            <div class="testimonial">
                <blockquote>
                    "PiggyVest has completely transformed my savings habit!"
                </blockquote>
                <cite>- Jane Doe</cite>
            </div>
            <div class="testimonial">
                <blockquote>
                    "I love how easy it is to invest and earn with PiggyVest."
                </blockquote>
                <cite>- John Smith</cite>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faqs" class="faqs">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <h3>How does PiggyVest work?</h3>
                <p>PiggyVest helps you save automatically and invest in profitable opportunities.</p>
            </div>
            <div class="faq-item">
                <h3>Is my money safe?</h3>
                <p>Yes! We use the best security practices to keep your money safe.</p>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 PiggyVest. All rights reserved.</p>
        </div>
    </footer>
</div>
</template>

<script>
export default {
    name: "PiggyVestApp",
};
</script>

<style scoped>
/* General Reset */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f7f9fc;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logo img {
    height: 40px;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
}

.nav-links a {
    text-decoration: none;
    color: #1d1d1d;
    font-weight: 500;
}

.auth-buttons {
    display: flex;
    gap: 10px;
}

.sign-in {
    background: none;
    border: 1px solid #1d1d1d;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
}

.create-account {
    background: #0d6efd;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
}

.menu-button {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }

    .menu-button {
        display: block;
    }
}

/* Hero Section */
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f0f8ff;
    padding: 50px 0;
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 50px;
}

.hero-text {
    max-width: 500px;
}

.badge {
    background-color: #0066cc;
    color: #fff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    margin-bottom: 20px;
    display: inline-block;
}

.hero-text h1 {
    font-size: 36px;
    color: #1d1d1d;
    margin: 10px 0;
}

.hero-text p {
    font-size: 18px;
    color: #555;
    margin: 20px 0;
}

.download-buttons {
    display: flex;
    gap: 10px;
}

.get-iphone,
.get-android {
    background: #0066cc;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
}

.hero-image img {
    max-width: 100%;
    border-radius: 10px;
}

/* Savings Section */
.savings {
    padding: 50px 0;
}

.savings h2 {
    font-size: 28px;
    text-align: center;
    color: #1d1d1d;
    margin-bottom: 10px;
}

.savings p {
    text-align: center;
    color: #555;
    margin-bottom: 30px;
}

.savings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

/* .savings-item {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.savings-item {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s ease;
}

.savings-item:hover {
    background-color: #0d6efd;
    color: #fff;
} */

.savings-item:hover h3,
.savings-item:hover p,
.savings-item:hover span {
    color: #fff;
}


/* .savings-item:hover .image-container {
    width: 100px;
    height: 100px;
    bottom: 10px;
    right: 10px;
} */

/* .savings-item:hover .image-container img {
    opacity: 1;
    transform: scale(1);
} */

.savings-item {
  position: relative;
  overflow: hidden;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.savings-item:hover {
  background-color: #0066cc; /* Change the color */
  color: #fff; /* Change text color */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.savings-item:hover img {
  transform: translate(-50%, -50%);
  opacity: 1;
}

.savings-item img {
  position: absolute;
  bottom: 10px;
  right: 10px;
  transform: translate(100%, 100%);
  opacity: 0;
  transition: transform 0.4s ease, opacity 0.4s ease;
  max-width: 50%;
  border-radius: 8px;
  pointer-events: none;
}
</style>
