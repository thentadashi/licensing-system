<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WCC Licensing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Use Vite to load your local assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* PROFESSIONAL COLOR SCHEME FOR AVIATION INSTITUTION */
        :root {
            /* Primary colors - Aviation-inspired professional palette */
            --primary-blue: #1e3a8a;      /* Deep professional blue */
            --primary-navy: #262e70;      /* Your existing primary */
            --accent-sky: #0ea5e9;        /* Aviation sky blue */
            --accent-gold: #f59e0b;       /* CAAP gold accent */
            
            /* Secondary colors */
            --secondary-gray: #475569;     /* Professional gray */
            --light-gray: #f1f5f9;        /* Light background */
            --dark-slate: #1e293b;        /* Dark text */
            
            /* Status colors */
            --success: #10b981;           /* Green for approved */
            --warning: #f59e0b;           /* Orange for pending */
            --danger: #ef4444;            /* Red for rejected */
            
            /* Gradients */
            --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #262e70 50%, #0ea5e9 100%);
            --hero-gradient: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(38, 46, 112, 0.95) 100%);
            
            /* Spacing and effects */
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            line-height: 1.6;
            color: var(--dark-slate);
            overflow-x: hidden;
        }

        /* NAVIGATION BAR - Professional and clean */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            justify-content: space-around;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-navy) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--secondary-gray) !important;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: var(--accent-sky);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* HERO SECTION - Aviation-themed with professional appeal */
        .hero {
            min-height: 100vh;
            background: var(--hero-gradient);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        /* Create animated background pattern with CSS only */
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 70%, rgba(255,255,255,0.05) 1px, transparent 1px),
                radial-gradient(circle at 40% 80%, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 100px 100px, 150px 150px, 200px 200px;
            animation: drift 60s linear infinite;
            opacity: 0.6;
        }

        @keyframes drift {
            0% { transform: translate(0, 0); }
            25% { transform: translate(-20px, -20px); }
            50% { transform: translate(20px, -40px); }
            75% { transform: translate(-10px, -60px); }
            100% { transform: translate(0, 0); }
        }

        /* Aviation-themed bottom wave */
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .hero-description {
            font-size: 1.1rem;
            opacity: 0.85;
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        /* LOGO STYLING - Professional presentation */
        .hero-logo {
            max-width: 100px;
            height: auto;
        }

        /* BUTTON STYLING - Modern and accessible */
        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            padding: 15px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            padding: 13px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-navy);
            transform: translateY(-2px);
            border-color: white;
        }

        /* FEATURES SECTION - Showcase portal capabilities */
        .features-section {
            padding: 100px 0;
            background: var(--light-gray);
        }

        .section-title {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-slate);
            margin-bottom: 20px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--secondary-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            border-top: 4px solid var(--accent-sky);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark-slate);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: var(--secondary-gray);
            line-height: 1.7;
        }

        /* STATS SECTION - Show credibility */
        .stats-section {
            padding: 80px 0;
            background: var(--primary-navy);
            color: white;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--accent-sky);
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* CTA SECTION - Strong call to action */
        .cta-section {
            padding: 100px 0;
            background: var(--primary-gradient);
            color: white;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-light-custom {
            background: white;
            color: var(--primary-navy);
            border: none;
            padding: 15px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-light-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            color: var(--primary-navy);
        }

        /* FOOTER - Professional and informative */
        .footer {
            background: var(--dark-slate);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: var(--accent-sky);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: block;
            padding: 5px 0;
        }

        .footer-link:hover {
            color: var(--accent-sky);
            padding-left: 5px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            margin: 0 5px;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--accent-sky);
            transform: translateY(-2px);
        }

/* RESPONSIVE DESIGN */

/* Extra small devices (phones, <576px) */
@media (max-width: 575.98px) {
    .hero {
        padding: 40px 15px;
        text-align: center;
    }

    .hero h1 {
        font-size: 2rem;
        line-height: 1.3;
    }

    .hero-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .btn-primary-custom,
    .btn-outline-custom {
        width: 100%;
        font-size: 0.9rem;
    }

    .features-section {
        padding: 40px 0;
    }
}

        /* Small devices (landscape phones, ≥576px and <768px) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .hero {
                padding: 50px 20px;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .features-section {
                padding: 50px 0;
            }
        }

        /* Medium devices (tablets, ≥768px and <992px) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .hero {
                padding: 60px 30px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .features-section {
                padding: 60px 0;
            }
        }

        /* Large devices (desktops, ≥992px and <1200px) */
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .hero h1 {
                font-size: 3rem;
            }

            .features-section {
                padding: 80px 0;
            }
        }

        /* Extra large devices (large desktops, ≥1200px) */
        @media (min-width: 1200px) {
            .hero h1 {
                font-size: 3.5rem;
            }

            .features-section {
                padding: 100px 0;
            }
        }


        /* ACCESSIBILITY IMPROVEMENTS */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* FOCUS STYLES */
        .btn-primary-custom:focus,
        .btn-outline-custom:focus,
        .btn-light-custom:focus {
            outline: 3px solid var(--accent-sky);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="hero-logo-container">
                    <img src="{{ asset('build/assets/images/logo.png') }}" alt="WCC Logo" class="hero-logo">
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">                             
                <div class="hero-badge">
                    <i class="fas fa-certificate me-2"></i>
                    CAAP Accredited Institution
                </div>
                <h1>WCC Licensing Portal</h1>
                <p class="hero-subtitle">Professional Aviation License Management</p>
                <p class="hero-description">
                    Streamline your aviation license applications, renewals, and compliance tracking 
                    through our secure, CAAP-compliant digital platform designed for aviation professionals.
                </p>
                
                <div class="hero-buttons d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('login') }}" class="btn-primary-custom">
                        <i class="fas fa-sign-in-alt"></i>
                        Access Portal
                    </a>
                    <a href="{{ route('register') }}" class="btn-outline-custom">
                        <i class="fas fa-user-plus"></i>
                        Register Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Comprehensive License Management</h2>
                <p>Everything you need to manage your aviation licenses efficiently and professionally</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Application Processing</h3>
                        <p>Submit and track license applications with real-time status updates and automated notifications for all aviation certification requirements.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Secure Document Storage</h3>
                        <p>Safely store and manage all your aviation documents, certificates, and compliance records with enterprise-grade security.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Renewal Reminders</h3>
                        <p>Never miss renewal deadlines with automated notifications and comprehensive tracking of license expiration dates.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Progress Tracking</h3>
                        <p>Monitor your licensing journey with detailed progress reports and milestone tracking for all certification requirements.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Multi-User Support</h3>
                        <p>Comprehensive user management for students, instructors, and administrators with role-based access controls.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile Responsive</h3>
                        <p>Access your licensing information anywhere with our fully responsive design optimized for all devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <div class="stat-label">Active Students</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">1,200+</span>
                        <div class="stat-label">Licenses Processed</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">System Availability</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Join hundreds of aviation professionals who trust WCC Licensing Portal for their certification needs.</p>
            <a href="{{ route('register') }}" class="btn-light-custom">
                <i class="fas fa-rocket"></i>
                Create Your Account Today
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>WCC Licensing Portal</h5>
                    <p class="mb-3">Professional aviation license management system designed for CAAP-accredited institutions and aviation professionals.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <a href="#" class="footer-link">Home</a>
                    <a href="#" class="footer-link">Features</a>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Contact</a>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Services</h5>
                    <a href="#" class="footer-link">License Applications</a>
                    <a href="#" class="footer-link">Renewals</a>
                    <a href="#" class="footer-link">Document Management</a>
                    <a href="#" class="footer-link">Compliance Tracking</a>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Contact Info</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Your Institution Address</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+63 XXX XXX XXXX</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>info@wcc.edu.ph</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} WCC Licensing Portal. All rights reserved. | CAAP Accredited Institution</p>
            </div>
        </div>
    </footer>

    {{-- Custom JavaScript for enhanced interactions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Navbar background change on scroll
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                }
            });

            // Animation on scroll for feature cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all feature cards
            document.querySelectorAll('.feature-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>