<?php
require '../config.php';

// Fetch all service images from database
$images_result = $conn->query("SELECT * FROM service_images ORDER BY uploaded_date DESC");
$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - HD Media</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .services-gallery {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .gallery-image {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .gallery-image.active {
            opacity: 1;
        }

        .gallery-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .gallery-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e94560;
        }

        .gallery-dot.active {
            background: #e94560;
            transform: scale(1.3);
        }

        .gallery-dot:hover {
            background: #00d4ff;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="../index.html"><img src="../images/hd-media-logo.svg" alt="HD Media Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="../index.html">Home</a></li>
            <li><a href="Services.php" class="active">Services</a></li>
            <li><a href="Portfolio.html">Portfolio</a></li>
            <li><a href="Pricing.html">Pricing</a></li>
            <li><a href="Team.html">Team</a></li>
            <li><a href="Testimonials.html">Testimonials</a></li>
            <li><a href="Process.html">Process</a></li>
            <li><a href="FAQ.html">FAQ</a></li>
            <li><a href="Bookings.html">Bookings</a></li>
            <li><a href="About.html">About</a></li>
            <li><a href="Contact.html">Contact</a></li>
        </ul>
    </nav>
    <section class="services">
        <div class="services-wrapper">
            <div class="services-container">
                <h1>Our Photography Services</h1>
                <p>We offer a range of photography services to capture your special moments beautifully.</p>
                <div class="service">
                    <h3>📷 Weddings</h3>
                    <p>Capture your special day with stunning wedding photography that lasts a lifetime.</p>
                </div>
                <div class="service">
                    <h3>🎭 Portrait Photography</h3>
                    <p>Professional portrait photography to highlight your personality and style.</p>
                </div>
                <div class="service">
                    <h3>🎂 Birthday Shoots</h3>
                    <p>Celebrate your birthday with fun and memorable photography sessions.</p>
                </div>
                <div class="service">
                    <h3>🎓 Graduation Photography</h3>
                    <p>Capture the moment you achieve success with high-quality graduation photos.</p>
                </div>
                <div class="service">
                    <h3>🎉 Event Photography</h3>
                    <p>Whether it's a corporate event, party, or concert, we capture every special moment.</p>
                </div>
                <div class="service">
                    <h3>⛪ Live Church Broadcasts</h3>
                    <p>Professional live streaming services for church services, worship events, and religious gatherings.</p>
                </div>
                <div class="service">
                    <h3>📹 Live Event Streaming</h3>
                    <p>Stream your events live to reach audiences worldwide in crystal clear high definition.</p>
                </div>
                <div class="service">
                    <h3>🎬 Video Production & Editing</h3>
                    <p>Complete video production services from concept to final edit with professional quality.</p>
                </div>
                <div class="service">
                    <h3>🚁 Drone Photography & Videography</h3>
                    <p>Stunning aerial perspectives using advanced drone technology for unique viewpoints.</p>
                </div>
                <div class="service">
                    <h3>📦 Product Photography</h3>
                    <p>High-quality product photography perfect for ecommerce stores and marketing materials.</p>
                </div>
                <div class="service">
                    <h3>🏠 Real Estate Photography</h3>
                    <p>Professional property photography, virtual tours, and 360-degree views for real estate.</p>
                </div>
                <div class="service">
                    <h3>📱 Social Media Content Creation</h3>
                    <p>Engaging content tailored for Instagram, TikTok, Facebook, and other social platforms.</p>
                </div>
                <div class="service">
                    <h3>💼 Corporate Photography</h3>
                    <p>Professional business photography for headshots, team photos, and corporate events.</p>
                </div>
                <div class="service">
                    <h3>👨‍👩‍👧‍👦 Family Portrait Sessions</h3>
                    <p>Capture precious family moments with professional styling and beautiful settings.</p>
                </div>
                <div class="service">
                    <h3>💼 Professional Headshots</h3>
                    <p>High-quality professional headshots for LinkedIn, acting portfolios, and corporate profiles.</p>
                </div>
                <div class="service">
                    <h3>🎥 Video Testimonials</h3>
                    <p>Professional video testimonials and client success stories for your business.</p>
                </div>
                <div class="service">
                    <h3>📸 Documentary Photography</h3>
                    <p>Candid, storytelling photography that captures authentic moments and emotions.</p>
                </div>
            </div>
            <div class="services-background">
                <div class="services-gallery">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($image['image_name']); ?>"
                             class="gallery-image <?php echo $index === 0 ? 'active' : ''; ?>"
                             data-index="<?php echo $index; ?>">
                    <?php endforeach; ?>
                    
                    <!-- Gallery Controls (Dots) -->
                    <div class="gallery-controls">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="gallery-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 onclick="currentSlide(<?php echo $index; ?>)"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentImageIndex = 0;
        const totalImages = <?php echo count($images); ?>;

        // Auto-rotate images every 5 seconds
        setInterval(() => {
            currentImageIndex = (currentImageIndex + 1) % totalImages;
            updateGallery();
        }, 5000);

        function currentSlide(index) {
            currentImageIndex = index;
            updateGallery();
        }

        function updateGallery() {
            // Hide all images
            const images = document.querySelectorAll('.gallery-image');
            images.forEach(img => img.classList.remove('active'));

            // Show current image
            images[currentImageIndex].classList.add('active');

            // Update dots
            const dots = document.querySelectorAll('.gallery-dot');
            dots.forEach((dot, index) => {
                dot.classList.remove('active');
                if (index === currentImageIndex) {
                    dot.classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
