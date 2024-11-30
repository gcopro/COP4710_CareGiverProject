<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Community</title>
    <link rel="stylesheet" href="../frontend/styling/style_front_page.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="nav-bar">
            <div class="logo">
                <h1>Caregiver Community</h1>
            </div>
            <div class="nav-links">
                <a href="../COP4710_CareGiverProject/front/pages/login.php" class="secondary-button">Login</a>
                <a href="../COP4710_CareGiverProject/front/pages/signup.php" class="cta-button">Sign Up</a>
            </div>
        </div>
    </header>


    <!-- Job Listings Section -->
    <section class="job-postings">
        <div class="container">
            <!-- Filter Bar -->
            <div class="filter-bar">
                <h3>Filter Nurses Available</h3>
                <form id="filterForm">
                    <div class="filter-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" placeholder="Enter city or area">
                    </div>

                    <div class="filter-group">
                        <label for="hours">Hours per week</label>
                        <select id="hours" name="hours">
                            <option value="">Any</option>
                            <option value="0-10">0-10 hours</option>
                            <option value="10-20">10-20 hours</option>
                            <option value="20-30">20-30 hours</option>
                            <option value="30+">30+ hours</option>
                        </select>
                    </div>

                    <button type="button" onclick="applyFilters()">Apply Filters</button>
                </form>
            </div>

            <!-- Scrollable Job Listings -->
            <div class="scrollable-jobs">
                <h2>Nurses Available</h2>
                <div class="job-list">
                    <div class="job">
                        <h3>Registered Nurse for Elderly Care</h3>
                        <p>Location: Downtown, New York</p>
                        <p>Hours: 4 hours daily, 5 days a week</p>
                        <p>Rate: 30 Care Dollars per hour</p>
                        <button>View Profile</button>
                    </div>
                    <div class="job">
                        <h3>Weekend Care Nurse for Senior Couple</h3>
                        <p>Location: San Francisco</p>
                        <p>Hours: 6 hours on Saturdays and Sundays</p>
                        <p>Rate: 30 Care Dollars per hour</p>
                        <button>View Profile</button>
                    </div>
                    <div class="job">
                        <h3>Nurse for Bedridden Patient Care</h3>
                        <p>Location: Miami</p>
                        <p>Hours: 8 hours daily, 7 days a week</p>
                        <p>Rate: 30 Care Dollars per hour</p>
                        <button>View Profile</button>
                    </div>
                    <!-- Additional listings can go here -->
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <h2>How It Works</h2>
            <div class="steps">
                <div class="step">
                    <h3>1. Register</h3>
                    <p>Sign up and get 2,000 Care Dollars to start hiring or offering caregiving services.</p>
                </div>
                <div class="step">
                    <h3>2. Hire or Provide Care</h3>
                    <p>Use Care Dollars to hire caregivers or provide services and earn Care Dollars.</p>
                </div>
                <div class="step">
                    <h3>3. Create Contracts</h3>
                    <p>Set up contracts with clear terms, including rates, hours, and services provided.</p>
                </div>
                <div class="step">
                    <h3>4. Rate & Review</h3>
                    <p>After each contract, rate your caregiver or client to help others make informed decisions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Why Choose Us?</h2>
            <div class="feature-list">
                <div class="feature">
                    <h3>Verified Professionals</h3>
                    <p>All caregivers are thoroughly vetted to ensure quality and trustworthiness.</p>
                </div>
                <div class="feature">
                    <h3>Earn and Spend Care Dollars</h3>
                    <p>Care Dollars allow you to manage caregiving services with ease and flexibility.</p>
                </div>
                <div class="feature">
                    <h3>Flexible Hours</h3>
                    <p>Find caregivers who fit your schedule or offer services during your free time.</p>
                </div>
                <div class="feature">
                    <h3>Direct Communication</h3>
                    <p>Communicate directly with caregivers or families to ensure the right fit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2>What Our Members Say</h2>
            <div class="testimonial">
                <p>"I found the perfect caregiver for my mother through this platform. The process was easy and straightforward!"</p>
                <span>- Sarah J., Member</span>
            </div>
            <div class="testimonial">
                <p>"The Care Dollars system is great! I can offer my services flexibly and get paid easily."</p>
                <span>- Tom L., Member</span>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta">
        <div class="container">
            <h2>Join Today!</h2>
            <p>Start your caregiving journey or find the perfect caregiver for your loved ones.</p>
            <a href="register.html" class="cta-button">Get Started</a>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Caregiver Community. All rights reserved.</p>
            <p><a href="about.html">About Us</a> | <a href="contact.html">Contact</a> | <a href="terms.html">Terms of Service</a></p>
        </div>
    </footer>
</body>
</html>
