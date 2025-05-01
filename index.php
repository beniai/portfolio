<?php
// Start session for form feedback
session_start();

// Initialize message variable
$message = '';

// Handle Contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message_text = trim($_POST['message']);
    
    if ($name && $email && $message_text) {
        $message_data = [
            'name' => $name,
            'email' => $email,
            'message' => $message_text,
            'timestamp' => date('c')
        ];
        
        $file_path = 'data/messages.json';
        $messages = [];
        
        // Read existing messages
        if (file_exists($file_path)) {
            $messages = json_decode(file_get_contents($file_path), true);
            if (!is_array($messages)) {
                $messages = [];
            }
        }
        
        // Append new message
        $messages[] = $message_data;
        
        // Save to file
        if (file_put_contents($file_path, json_encode($messages, JSON_PRETTY_PRINT))) {
            $_SESSION['form_message'] = 'Message sent successfully!';
        } else {
            $_SESSION['form_message'] = 'Failed to save message.';
        }
    } else {
        $_SESSION['form_message'] = 'Please fill in all fields.';
    }
    
    // Redirect to clear POST data and show message
    header('Location: #contact');
    exit;
}

// Get form feedback
$form_message = isset($_SESSION['form_message']) ? $_SESSION['form_message'] : '';
unset($_SESSION['form_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Engineer Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://kit.fontawesome.com/4b9ba14b0c.js" crossorigin="anonymous"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
        .animate-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .animate-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 161, 228, 0.2);
        }
        .dark-mode {
            background-color: #1a1a1a;
            color: #e5e7eb;
        }
        .dark-mode .bg-gray-800 {
            background-color: #2d2d2d;
        }
        .dark-mode .bg-white {
            background-color: #333;
        }
        .dark-mode .text-gray-600 {
            color: #b0b0b0;
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .slide-in-left {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .slide-in-right {
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .bounce {
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .circle-progress {
            position: relative;
            width: 120px;
            height: 120px;
        }
        .circle-progress svg {
            transform: rotate(-90deg);
        }
        .circle-bg {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 10;
        }
        .circle-fg {
            fill: none;
            stroke: #00A1E4;
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dasharray 1.5s ease;
        }
        .circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.2rem;
            font-weight: bold;
        }
        .input-focus {
            transition: transform 0.3s ease;
        }
        .input-focus:focus {
            transform: scale(1.02);
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .footer-hover {
            transition: color 0.3s ease;
        }
        .footer-hover:hover {
            color: #00A1E4;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            opacity: 0.5;
        }
        .project-card {
            perspective: 1000px;
            height: 250px;
        }
        .project-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .project-card:hover .project-card-inner {
            transform: rotateY(180deg);
        }
        .project-card-front, .project-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .project-card-back {
            transform: rotateY(180deg);
            padding: 1rem;
            text-align: center;
            overflow: auto;
        }
        .dark-mode .project-card-front, .dark-mode .project-card-back {
            background-color: #4b5563;
        }
        .social-icon {
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        .social-icon:hover {
            filter: brightness(0.7);
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="fixed top-0 w-full bg-gray-800 text-white shadow-md z-10">
        <nav class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-bold">AI Engineer</div>
            <ul class="flex space-x-6">
                <li><a href="#home" class="hover:text-blue-400" title="Home"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="#about" class="hover:text-blue-400" title="About"><i class="fa-regular fa-address-card"></i> About</a></li>
                <li><a href="#skills" class="hover:text-blue-400" title="Skills"><i class="fas fa-cogs"></i> Skills</a></li>
                <li><a href="#projects" class="hover:text-blue-400" title="Projects"><i class="fas fa-project-diagram"></i> Projects</a></li>
                <li><a href="#social" class="hover:text-blue-400" title="Social Media"><i class="fas fa-share-alt"></i> Social</a></li>
                <li><a href="#blog" class="hover:text-blue-400" title="Blog"><i class="fas fa-blog"></i> Blog</a></li>
                <li><a href="#contact" class="hover:text-blue-400" title="Contact"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
            <button id="darkModeToggle" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Dark Mode</button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-800 to-black text-white relative">
        <div id="particles-js"></div>
        <div class="text-center z-10">
            <h1 class="text-5xl font-bold mb-4">
                Hi, I’m Beni – <span id="typewriter" class="border-r-2 border-white inline-block"></span>
            </h1>
            <p class="text-xl mb-6 fade-in">Building intelligent solutions for the future.</p>
            <a href="#projects" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-lg fade-in pulse">View My Work</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
            <div class="md:w-1/3 mb-6 md:mb-0">
                <img src="https://via.placeholder.com/300x300" alt="Profile Photo" class="rounded-full w-64 h-64 mx-auto slide-in-left">
            </div>
            <div class="md:w-2/3 md:pl-10">
                <h2 class="text-3xl font-bold mb-4 slide-in-left">About Me</h2>
                <p class="text-lg text-gray-600 mb-4 slide-in-left">
                    I’m an AI Engineer with over 5 years of experience in machine learning, NLP, and computer vision. I’m passionate about solving real-world problems using AI and have worked on projects ranging from sentiment analysis to autonomous systems.
                </p>
                <p class="text-lg text-gray-600 mb-4 slide-in-left">
                    Want to learn more about my qualifications? <a href="assets/cv.pdf" download class="text-blue-500 hover:underline font-semibold">Download My CV</a>
                </p>
                <div class="flex space-x-4 slide-in-left">
                    <a href="#" class="bounce">
                        <img src="https://via.placeholder.com/24?text=LinkedIn" alt="LinkedIn" class="w-6 h-6 social-icon">
                    </a>
                    <a href="#" class="bounce">
                        <img src="https://via.placeholder.com/24?text=GitHub" alt="GitHub" class="w-6 h-6 social-icon">
                    </a>
                    <a href="#" class="bounce">
                        <img src="https://via.placeholder.com/24?text=Email" alt="Email" class="w-6 h-6 social-icon">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10 fade-in">Skills</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                $skills = [
                    ['name' => 'Python', 'percentage' => 90, 'cert' => 'View Python Certification', 'certLink' => '#'],
                    ['name' => 'TensorFlow', 'percentage' => 85, 'cert' => 'View TensorFlow Developer Certificate', 'certLink' => '#'],
                    ['name' => 'NLP', 'percentage' => 80, 'cert' => 'View NLP Specialization', 'certLink' => '#'],
                    ['name' => 'Web Development', 'percentage' => 75, 'cert' => 'View Web Development Bootcamp Certificate', 'certLink' => '#'],
                    ['name' => 'JavaScript', 'percentage' => 70, 'cert' => 'No certificate available', 'certLink' => null],
                    ['name' => 'React', 'percentage' => 65, 'cert' => 'No certificate available', 'certLink' => null],
                    ['name' => 'Cloud Computing', 'percentage' => 60, 'cert' => 'View AWS Certified Solutions Architect Certificate', 'certLink' => '#'],
                ];
                foreach ($skills as $skill) {
                    echo '
                    <div class="flex flex-col items-center fade-in">
                        <div class="circle-progress">
                            <svg width="120" height="120">
                                <circle class="circle-bg" cx="60" cy="60" r="50"></circle>
                                <circle class="circle-fg" cx="60" cy="60" r="50" stroke-dasharray="0 314" stroke-dashoffset="0" data-percentage="' . $skill['percentage'] . '"></circle>
                            </svg>
                            <div class="circle-text">' . $skill['percentage'] . '%</div>
                        </div>
                        <p class="mt-2 text-center font-semibold">' . $skill['name'] . '</p>';
                    if ($skill['certLink']) {
                        echo '<a href="' . $skill['certLink'] . '" class="text-blue-500 hover:underline text-sm mt-1">' . $skill['cert'] . '</a>';
                    } else {
                        echo '<p class="text-gray-600 text-sm mt-1 italic">' . $skill['cert'] . '</p>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10 fade-in">Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                $projects = [
                    [
                        'title' => 'Sentiment Analysis Model',
                        'image' => 'https://via.placeholder.com/300x150',
                        'description' => 'Developed an NLP model to analyze sentiment with 92% accuracy using BERT and Python. Deployed via Flask for real-time analysis.',
                        'github' => '#'
                    ],
                    [
                        'title' => 'Image Recognition System',
                        'image' => 'https://via.placeholder.com/300x150',
                        'description' => 'Built a CNN-based system for image classification with 95% accuracy using TensorFlow. Hosted on AWS for scalability.',
                        'github' => '#'
                    ],
                    [
                        'title' => 'Chatbot with GPT-3',
                        'image' => 'https://via.placeholder.com/300x150',
                        'description' => 'Created an intelligent chatbot for customer support using GPT-3 and Flask, improving response time by 40%.',
                        'github' => '#'
                    ],
                    [
                        'title' => 'Facial Recognition App',
                        'image' => 'https://via.placeholder.com/300x150',
                        'description' => 'Developed a facial recognition app with real-time detection using OpenCV and Python, achieving 98% accuracy.',
                        'github' => '#'
                    ],
                    [
                        'title' => 'Predictive Maintenance System',
                        'image' => 'https://via.placeholder.com/300x150',
                        'description' => 'Built a system to predict machine failures using time-series data and LSTM models, reducing downtime by 30%.',
                        'github' => '#'
                    ],
                ];
                foreach ($projects as $project) {
                    echo '
                    <div class="project-card fade-in">
                        <div class="project-card-inner">
                            <div class="project-card-front">
                                <img src="' . $project['image'] . '" alt="' . $project['title'] . '" class="w-full h-40 object-cover rounded-t-lg">
                                <h3 class="text-xl font-semibold mt-2">' . $project['title'] . '</h3>
                            </div>
                            <div class="project-card-back">
                                <p class="text-gray-600 text-sm">' . $project['description'] . '</p>
                                <a href="' . $project['github'] . '" class="text-blue-500 hover:underline text-sm mt-2">View on GitHub</a>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Social Section -->
    <section id="social" class="py-32 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10 fade-in">Connect With Me</h2>
            <div class="flex justify-center space-x-6">
                <a href="#" class="bounce fade-in">
                    <img src="https://via.placeholder.com/32?text=LinkedIn" alt="LinkedIn" class="w-8 h-8 social-icon">
                </a>
                <a href="#" class="bounce fade-in">
                    <img src="https://via.placeholder.com/32?text=GitHub" alt="GitHub" class="w-8 h-8 social-icon">
                </a>
                <a href="#" class="bounce fade-in">
                    <img src="https://via.placeholder.com/32?text=Twitter" alt="Twitter" class="w-8 h-8 social-icon">
                </a>
                <a href="#" class="bounce fade-in">
                    <img src="https://via.placeholder.com/32?text=Instagram" alt="Instagram" class="w-8 h-8 social-icon">
                </a>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10 fade-in">Blog</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md animate-card slide-in-left">
                    <h3 class="text-xl font-semibold mb-2">How I Built a Chatbot with GPT-3</h3>
                    <p class="text-gray-600 mb-4">A step-by-step guide on building an intelligent chatbot using GPT-3 and Flask...</p>
                    <a href="#" class="text-blue-500 hover:underline">Read More</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md animate-card slide-in-right">
                    <h3 class="text-xl font-semibold mb-2">Introduction to NLP with BERT</h3>
                    <p class="text-gray-600 mb-4">An overview of NLP and how to use BERT for text classification tasks...</p>
                    <a href="#" class="text-blue-500 hover:underline">Read More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-10 fade-in">Contact Me</h2>
            <div class="max-w-lg mx-auto bg-gray-100 p-6 rounded-lg shadow-md fade-in">
                <form method="POST" action="#contact">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium">Name</label>
                            <input type="text" id="name" name="name" class="w-full p-2 border rounded-lg input-focus" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" id="email" name="email" class="w-full p-2 border rounded-lg input-focus" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium">Message</label>
                            <textarea id="message" name="message" class="w-full p-2 border rounded-lg input-focus" rows="4"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg pulse">Send Message</button>
                    </div>
                </form>
                <?php if ($form_message): ?>
                    <p class="text-center text-sm mt-2"><?php echo htmlspecialchars($form_message); ?></p>
                <?php endif; ?>
            </div>
            <div class="flex justify-center space-x-4 mt-6 fade-in">
                <a href="#" class="bounce">
                    <img src="https://via.placeholder.com/24?text=LinkedIn" alt="LinkedIn" class="w-6 h-6 social-icon">
                </a>
                <a href="#" class="bounce">
                    <img src="https://via.placeholder.com/24?text=GitHub" alt="GitHub" class="w-6 h-6 social-icon">
                </a>
                <a href="#" class="bounce">
                    <img src="https://via.placeholder.com/24?text=Email" alt="Email" class="w-6 h-6 social-icon">
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-6 text-center">
            <p class="footer-hover">© 2025 Alex. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Particles.js for Hero Section
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#00A1E4' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: '#00A1E4', opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' }, resize: true },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });

        // Typewriter Effect for Hero Section
        const typewriter = document.getElementById('typewriter');
        const phrases = ['AI Engineer & Innovator', 'Machine Learning Expert', 'NLP Specialist'];
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentPhrase = phrases[phraseIndex];
            if (isDeleting) {
                typewriter.textContent = currentPhrase.substring(0, charIndex--);
                if (charIndex < 0) {
                    isDeleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    setTimeout(type, 500);
                } else {
                    setTimeout(type, 50);
                }
            } else {
                typewriter.textContent = currentPhrase.substring(0, charIndex++);
                if (charIndex > currentPhrase.length) {
                    isDeleting = true;
                    setTimeout(type, 1500);
                } else {
                    setTimeout(type, 100);
                }
            }
        }
        type();

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            darkModeToggle.textContent = document.body.classList.contains('dark-mode') ? 'Light Mode' : 'Dark Mode';
        });

        // Intersection Observer for Animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    if (entry.target.classList.contains('circle-progress')) {
                        const circle = entry.target.querySelector('.circle-fg');
                        const percentage = circle.getAttribute('data-percentage');
                        const circumference = 314; // 2 * π * 50 (radius)
                        const dashArray = (percentage / 100) * circumference;
                        circle.style.strokeDasharray = `${dashArray} ${circumference}`;
                    }
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .circle-progress').forEach(element => {
            observer.observe(element);
        });
    </script>
</body>
</html>