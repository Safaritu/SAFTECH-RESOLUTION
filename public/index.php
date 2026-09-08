<?php
require_once __DIR__ . '/../includes/db.php';

$liveDemos = $pdo->query("SELECT * FROM projects WHERE category='live_demo' ORDER BY sort_order")->fetchAll();
$services = $pdo->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order")->fetchAll();
$enterpriseSolutions = $pdo->query("SELECT * FROM projects WHERE category='enterprise' ORDER BY sort_order")->fetchAll();
$portfolioProjects = $pdo->query("SELECT * FROM projects WHERE category='portfolio' ORDER BY sort_order")->fetchAll();

$formSuccess = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quote_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $projectInterest = trim($_POST['project_interest'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $formError = 'Please fill in your name, email, and project details.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO quote_requests (name, email, project_interest, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $projectInterest, $message]);
        $formSuccess = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>SAFTECH RESOLUTIONS | Enterprise Software Engineering</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Microsoft Clarity Analytics -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "w0qytpwn7s");
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #020617; 
            color: #f8fafc; 
            scroll-behavior: smooth; 
        }
        
        .gradient-text { 
            background: linear-gradient(90deg, #38bdf8, #818cf8); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text;
        }
        
        .glass { 
            background: rgba(15, 23, 42, 0.7); 
            -webkit-backdrop-filter: blur(12px); 
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }
        
        .btn-primary { 
            background: linear-gradient(90deg, #0ea5e9, #6366f1); 
            transition: all 0.3s ease; 
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 20px 30px -10px rgba(14, 165, 233, 0.4); 
        }
        
        .card-hover:hover { 
            border-color: #38bdf8; 
            background: rgba(30, 41, 59, 0.6); 
            transform: translateY(-4px);
        }
        
        section { 
            padding: 80px 0; 
        }
        
        @media (max-width: 768px) {
            section { 
                padding: 60px 0; 
            }
        }
        
        .code-dots {
            display: flex;
            gap: 8px;
            padding: 16px;
            background: #1a1f2e;
            border-bottom: 1px solid #2d3748;
        }
        
        .code-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        
        pre {
            margin: 0;
            padding: 20px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #e2e8f0;
        }
        
        .keyword { color: #ff79c6; }
        .property { color: #8be9fd; }
        .string { color: #f1fa8c; }
        .comment { color: #6272a4; }
        .function { color: #50fa7b; }
        
        .project-image {
            height: 240px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .floating-badge {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .hero-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        
        .glow-text {
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
        }

        .demo-card {
            transition: all 0.3s ease;
        }
        
        .demo-card:hover {
            transform: translateY(-8px) scale(1.02);
        }
        
        .demo-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 6px 12px;
            background: rgba(34, 197, 94, 0.9);
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: white;
            z-index: 10;
        }

        .live-demo-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .live-demo-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }

        .enterprise-card {
            background: rgba(15, 23, 42, 0.6);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .enterprise-card:hover {
            transform: translateY(-8px);
            border-color: #38bdf8;
            box-shadow: 0 20px 30px -10px rgba(56, 189, 248, 0.3);
        }

        .card-image {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(2, 6, 23, 0.6);
        }
        .card-image i {
            background: rgba(0,0,0,0.3);
            padding: 1rem;
            border-radius: 50%;
            backdrop-filter: blur(4px);
            font-size: 2.5rem;
        }

        .card-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-category {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #38bdf8;
            margin-bottom: 8px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 16px;
            flex: 1;
        }

        .card-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }

        .card-tag {
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 600;
            color: #38bdf8;
        }

        .demo-btn {
            width: 100%;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: auto;
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.5);
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 260px;
            max-width: 75%;
            height: 100vh;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(12px);
            z-index: 1000;
            padding: 90px 20px;
            transition: right 0.3s ease;
            border-left: 1px solid rgba(56, 189, 248, 0.3);
        }

        .mobile-menu.active {
            right: 0;
        }
        
        .mobile-menu a {
            font-size: 0.95rem;
            padding: 12px 0;
            display: block;
            border-bottom: 1px solid rgba(71, 85, 105, 0.3);
        }

        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0,0,0,0.6);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            z-index: 1001;
        }

        .hamburger span {
            width: 24px;
            height: 2px;
            background: white;
            margin: 4px 0;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        @media (max-width: 768px) {
            .desktop-nav {
                display: none !important;
            }
            .hamburger {
                display: flex;
            }
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #1e293b;
            border-radius: 30px;
            padding: 35px;
            max-width: 450px;
            width: 90%;
            text-align: center;
            border: 1px solid #38bdf8;
        }

        .modal-icon {
            font-size: 3.5rem;
            color: #38bdf8;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: white;
        }

        .modal-text {
            color: #94a3b8;
            margin-bottom: 25px;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white;
        }

        .modal-btn-secondary {
            background: #334155;
            color: white;
        }

        .footer-link {
            transition: color 0.2s;
        }
        .footer-link:hover {
            color: #38bdf8;
        }
        .social-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            transition: all 0.2s;
        }
        .social-icon:hover {
            background: rgba(56,189,248,0.2);
            color: #38bdf8;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            h1 { font-size: 2.5rem !important; }
            h2 { font-size: 1.5rem !important; }
            .hero-text { font-size: 0.9rem !important; }
            .btn-primary { padding: 12px 20px !important; font-size: 0.9rem !important; }
            .stats-numbers { font-size: 1.8rem !important; }
        }
        
        .profile-img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            object-position: top 15% center;
            border-radius: 20px;
        }
        
        .hero-profile-img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            object-position: top 20% center;
            border-radius: 24px;
        }
        
        @media (max-width: 768px) {
            .profile-img {
                height: 280px;
                object-position: top 10% center;
            }
            .hero-profile-img {
                height: 320px;
                object-position: top 15% center;
            }
            .floating-badge {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .floating-badge {
                display: block;
            }
        }
    </style>
</head>
<body class="leading-relaxed antialiased">

    <!-- Quote Modal -->
    <div class="modal" id="quoteModal">
        <div class="modal-content">
            <div class="modal-icon">✨</div>
            <h2 class="modal-title">Interested in this solution?</h2>
            <p class="modal-text" id="modalProjectName">This enterprise solution is ready for deployment.</p>
            <p class="modal-text">Contact us directly for a personalized consultation and quote.</p>
            <div class="modal-buttons">
                <a href="#quote" class="modal-btn modal-btn-primary" onclick="closeModalAndScrollToQuote()">Get a Quote</a>
                <button class="modal-btn modal-btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="flex flex-col gap-1">
            <a href="#demos" onclick="toggleMobileMenu()"><i class="fas fa-play-circle mr-3 text-sky-400"></i>Live Sites</a>
            <a href="#services" onclick="toggleMobileMenu()"><i class="fas fa-laptop-code mr-3 text-sky-400"></i>Services</a>
            <a href="#enterprise" onclick="toggleMobileMenu()"><i class="fas fa-industry mr-3 text-sky-400"></i>Enterprise</a>
            <a href="#portfolio" onclick="toggleMobileMenu()"><i class="fas fa-folder-open mr-3 text-sky-400"></i>Portfolio</a>
            <a href="#networking" onclick="toggleMobileMenu()"><i class="fas fa-network-wired mr-3 text-sky-400"></i>Networking</a>
            <a href="#contact" onclick="toggleMobileMenu()"><i class="fas fa-envelope mr-3 text-sky-400"></i>Contact</a>
            <a href="#quote" class="mt-4 px-4 py-2 btn-primary rounded-full text-center" onclick="toggleMobileMenu()">Get a Quote</a>
        </div>
    </div>
    <div class="menu-overlay" id="menuOverlay" onclick="toggleMobileMenu()"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-[100] glass border-b border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="logo.png" alt="SAFTECH" class="h-12 md:h-14 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span class="text-xl md:text-2xl font-extrabold tracking-tighter uppercase hidden">SAFTECH <span class="text-sky-400">RESOLUTIONS</span></span>
                <span class="text-lg md:text-2xl font-extrabold tracking-tighter uppercase">SAFTECH <span class="text-sky-400">RESOLUTIONS</span></span>
            </div>
            
            <div class="desktop-nav space-x-6 hidden md:flex text-sm font-semibold items-center">
                <a href="#demos" class="hover:text-sky-400 transition">Live Sites</a>
                <a href="#services" class="hover:text-sky-400 transition">Services</a>
                <a href="#enterprise" class="hover:text-sky-400 transition">Enterprise</a>
                <a href="#portfolio" class="hover:text-sky-400 transition">Portfolio</a>
                <a href="#networking" class="hover:text-sky-400 transition">Networking</a>
                <a href="#contact" class="hover:text-sky-400 transition">Contact</a>
                <a href="#quote" class="px-5 py-2 btn-primary rounded-full text-white text-xs uppercase tracking-wider font-bold">Get a Quote</a>
            </div>

            <div class="hamburger md:hidden" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-28 md:pt-36 pb-16 md:pb-20 px-4 relative overflow-hidden">
        <div class="absolute top-20 right-0 -z-10 w-[400px] h-[400px] bg-sky-500/20 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 -z-10 w-[300px] h-[300px] bg-purple-500/20 blur-[100px] rounded-full"></div>
        
        <div class="max-w-7xl mx-auto">
            <div class="inline-block px-4 py-1.5 rounded-full bg-sky-500/20 border border-sky-500/30 text-sky-400 text-xs font-bold mb-6 tracking-widest uppercase hero-badge">
                <i class="fas fa-star mr-1"></i> # SAFTECH RESOLUTIONS <i class="fas fa-star ml-1"></i>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold mb-4 leading-[1.15] glow-text">
                        INNOVATING <span class="gradient-text">RETAIL</span> & WEB
                    </h1>
                    <div class="h-1 w-20 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mb-6"></div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4 leading-tight">
                        Enterprise Solutions & <span class="text-sky-400">Industrial Architectures</span>
                    </h2>
                    <p class="text-slate-300 text-base md:text-lg mb-6 max-w-xl hero-text">
                        Founded by <span class="text-white font-bold">ALEX SAFARI</span>, Saftech Resolutions specializes in Enterprise Software, POS systems, custom web development, and expert technical support. <span class="text-sky-400">Several businesses transformed globally.</span>
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#demos" class="inline-flex items-center gap-2 px-6 md:px-8 py-3 md:py-4 btn-primary font-bold rounded-xl text-sm md:text-lg group">
                            <i class="fas fa-play-circle text-xl"></i> Try Live Demos
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="https://wa.me/254794963537" target="_blank" class="inline-flex items-center gap-2 px-6 md:px-8 py-3 md:py-4 glass border border-slate-700 hover:border-green-400 font-bold rounded-xl text-sm md:text-lg group">
                            <i class="fab fa-whatsapp text-green-400 text-xl"></i> Chat on WhatsApp
                        </a>
                    </div>
                    <div class="flex items-center gap-4 mt-8">
                        <div class="flex -space-x-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-slate-400">Trusted by 9+ companies</span>
                    </div>
                </div>
                
                <div class="relative mt-8 md:mt-0">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-sky-500/20">
                        <img src="alex.png" alt="Alex Safari - Founder" class="hero-profile-img w-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                    </div>
                    <div class="absolute -bottom-8 -left-8 w-56 md:w-64 glass rounded-xl overflow-hidden shadow-2xl border border-sky-500/30 floating-badge">
                        <div class="code-dots bg-slate-900/90 py-2 px-3">
                            <div class="code-dot dot-red"></div>
                            <div class="code-dot dot-yellow"></div>
                            <div class="code-dot dot-green"></div>
                            <span class="ml-auto text-xs text-slate-400">20+ Solutions</span>
                        </div>
                        <pre class="text-xs p-3 bg-slate-900/90"><span class="comment">// SAFTECH Enterprise</span>
<span class="keyword">const</span> <span class="property">solutions</span> = {
  finance: <span class="string">"3"</span>,
  manufacturing: <span class="string">"3"</span>, 
  logistics: <span class="string">"3"</span>,
  hr: <span class="string">"3"</span>,
  specialized: <span class="string">"3"</span>,
  coming: <span class="string">"6"</span>
};</pre>
                    </div>
                    <div class="absolute -top-4 -right-4 glass px-4 py-2 rounded-full border border-sky-500/20 bg-slate-900/90">
                        <span class="text-xs font-bold">✨ Founder & Lead Engineer</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <div class="max-w-7xl mx-auto px-4 -mt-8 mb-16 md:mb-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 glass p-5 md:p-8 rounded-2xl border border-slate-800/50">
            <div class="text-center"><h3 class="text-2xl md:text-3xl font-extrabold text-white stats-numbers">7+</h3><p class="text-slate-400 text-xs mt-1">Projects Delivered</p></div>
            <div class="text-center"><h3 class="text-2xl md:text-3xl font-extrabold text-white stats-numbers">100%</h3><p class="text-slate-400 text-xs mt-1">Client Satisfaction</p></div>
            <div class="text-center"><h3 class="text-2xl md:text-3xl font-extrabold text-white stats-numbers">24/7</h3><p class="text-slate-400 text-xs mt-1">Tech Support</p></div>
            <div class="text-center"><h3 class="text-2xl md:text-3xl font-extrabold text-white stats-numbers"><a href="#enterprise" class="hover:text-sky-400">20+</a></h3><p class="text-slate-400 text-xs mt-1">Solutions</p></div>
        </div>
    </div>

    <!-- LIVE DEMOS SECTION -->
    <section id="demos" class="bg-slate-900/20 py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-10 text-center">
    <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Try Before You Buy</span>
    <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-3">Live Interactive Sites <span class="text-emerald-400 text-base ml-2"><i class="fas fa-check-circle"></i> Currently In Use</span></h2>
    <p class="text-slate-400 text-sm max-w-2xl mx-auto">Click any site to request a personalized walkthrough.</p>
    <div class="h-1 w-16 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mx-auto mt-3"></div>
</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <?php foreach ($liveDemos as $demo): ?>
                <div class="demo-card"><div class="glass rounded-2xl overflow-hidden border border-<?= htmlspecialchars($demo['color_theme']) ?>-500/30 h-full"><div class="relative h-40 overflow-hidden"><img src="<?= htmlspecialchars($demo['image_path']) ?>" class="w-full h-full object-cover"><div class="demo-badge"><i class="fas fa-circle text-green-400 text-xs mr-1"></i>LIVE</div></div><div class="p-4"><h3 class="text-lg font-bold mb-1"><?= htmlspecialchars($demo['title']) ?></h3><p class="text-slate-400 text-xs mb-3"><?= htmlspecialchars($demo['description']) ?></p><button onclick="showQuoteModal('<?= htmlspecialchars(addslashes($demo['title'])) ?> Demo')" class="live-demo-link text-xs py-2 px-3 w-full justify-center"><i class="fas fa-rocket"></i> Request Demo</button></div></div></div>
                <?php endforeach; ?>
            </div>
            <div class="mt-10 glass p-5 rounded-2xl border border-slate-800">
                <div class="grid grid-cols-3 md:grid-cols-6 gap-3 text-center">
                    <div><div class="text-xl md:text-2xl font-bold text-sky-400">7+</div><div class="text-xs text-slate-400">In Use Live Systems</div></div>
                    <div><div class="text-xl md:text-2xl font-bold text-purple-400">120+</div><div class="text-xs text-slate-400">Modules</div></div>
                    <div><div class="text-xl md:text-2xl font-bold text-emerald-400">Free</div><div class="text-xs text-slate-400">To Test</div></div>
                    <div><div class="text-xl md:text-2xl font-bold text-pink-400">2hr</div><div class="text-xs text-slate-400">Response</div></div>
                    <div><div class="text-xl md:text-2xl font-bold text-orange-400">100%</div><div class="text-xs text-slate-400">Satisfaction</div></div>
                    <div><div class="text-xl md:text-2xl font-bold text-indigo-400">24/7</div><div class="text-xs text-slate-400">Support</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Core Capabilities</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">What We Do</h2>
                <div class="h-1 w-16 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($services as $svc): ?>
                <div class="glass p-6 rounded-2xl border border-slate-800 card-hover"><i class="fas <?= htmlspecialchars($svc['icon']) ?> text-<?= htmlspecialchars($svc['color_theme']) ?>-400 text-3xl mb-3"></i><h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($svc['title']) ?></h3><p class="text-slate-400 text-sm"><?= htmlspecialchars($svc['description']) ?></p></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ENTERPRISE SOLUTIONS - Expanded with more cards -->
    <section id="enterprise" class="py-12 md:py-16 bg-slate-900/20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Enterprise Solutions</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Industrial Architectures</h2>
                <p class="text-slate-400 text-sm max-w-2xl mx-auto">Scalable systems for real-world industries. 20+ solutions deployed across various sectors.</p>
                <div class="h-1 w-16 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($enterpriseSolutions as $sol): ?>
                <?php $tagList = $sol['tags'] ? explode(',', $sol['tags']) : []; ?>
                <div class="enterprise-card"><div class="card-image" style="background-image: url('<?= htmlspecialchars($sol['image_path']) ?>');"><i class="fas <?= htmlspecialchars($sol['icon']) ?>"></i></div><div class="card-content"><div class="card-category"><?= htmlspecialchars($sol['category_label']) ?></div><div class="card-title"><?= htmlspecialchars($sol['title']) ?></div><div class="card-description"><?= htmlspecialchars($sol['description']) ?></div><div class="card-tags"><?php foreach ($tagList as $tag): ?><span class="card-tag"><?= htmlspecialchars(trim($tag)) ?></span><?php endforeach; ?></div><button class="demo-btn" onclick="showQuoteModal('<?= htmlspecialchars(addslashes($sol['title'])) ?>')"><i class="fas fa-rocket"></i> Request Demo</button></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Networking Section -->
    <section id="networking" class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Infrastructure</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Networking Solutions</h2>
                <div class="h-1 w-16 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass p-6 rounded-2xl border border-slate-800"><i class="fas fa-globe text-purple-400 text-3xl mb-3"></i><h3 class="text-xl font-bold mb-2">ISP Solutions</h3><p class="text-slate-400 text-sm">PPPoE, RADIUS, bandwidth management, billing.</p></div>
                <div class="glass p-6 rounded-2xl border border-slate-800"><i class="fas fa-network-wired text-blue-400 text-3xl mb-3"></i><h3 class="text-xl font-bold mb-2">Enterprise Networking</h3><p class="text-slate-400 text-sm">VPN, firewalls, load balancing, monitoring.</p></div>
                <div class="glass p-6 rounded-2xl border border-slate-800"><i class="fas fa-cloud-upload-alt text-orange-400 text-3xl mb-3"></i><h3 class="text-xl font-bold mb-2">Cloud Infrastructure</h3><p class="text-slate-400 text-sm">AWS, Docker, Kubernetes, CI/CD.</p></div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-12 md:py-16 bg-slate-900/20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Our Work</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Featured Projects</h2>
                <div class="h-1 w-16 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($portfolioProjects as $proj): ?>
                <div class="glass rounded-2xl overflow-hidden border border-<?= htmlspecialchars($proj['color_theme']) ?>-500/30"><div class="h-48 overflow-hidden"><img src="<?= htmlspecialchars($proj['image_path']) ?>" class="w-full h-full object-cover"></div><div class="p-4"><h3 class="text-lg font-bold"><?= htmlspecialchars($proj['title']) ?></h3><p class="text-slate-400 text-xs"><?= htmlspecialchars($proj['description']) ?></p><button onclick="showQuoteModal('<?= htmlspecialchars(addslashes($proj['title'])) ?>')" class="text-<?= htmlspecialchars($proj['color_theme']) ?>-400 text-sm font-bold mt-2 bg-transparent border-0">Request Demo &rarr;</button></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Quote Section -->
    <section id="quote" class="py-12 md:py-16">
        <div class="max-w-4xl mx-auto px-4">
            <div class="glass p-6 md:p-10 rounded-3xl border border-sky-500/20">
                <div class="text-center mb-8">
                    <span class="text-sky-400 text-xs font-bold uppercase tracking-widest">Start Your Journey</span>
                    <h2 class="text-2xl md:text-4xl font-bold mt-2">Launch Your Project</h2>
                    <p class="text-slate-400 text-sm">We respond within 2 hours.</p>
                </div>
                <form method="POST" action="#quote">
                    <?php if ($formSuccess): ?>
                        <div class="mb-4 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-sm">
                            Thanks! Your request has been received — we'll respond within 2 hours.
                        </div>
                    <?php elseif ($formError): ?>
                        <div class="mb-4 p-4 rounded-xl bg-red-500/20 border border-red-500/40 text-red-300 text-sm">
                            <?= htmlspecialchars($formError) ?>
                        </div>
                    <?php endif; ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div><input type="text" name="name" placeholder="Your Name" class="w-full bg-slate-950/50 border border-slate-800 p-3 rounded-xl text-sm" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></div>
                        <div><input type="email" name="email" placeholder="Email" class="w-full bg-slate-950/50 border border-slate-800 p-3 rounded-xl text-sm" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
                        <div class="md:col-span-2"><textarea name="message" placeholder="Project details" class="w-full bg-slate-950/50 border border-slate-800 p-3 rounded-xl h-28 text-sm"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea></div>
                    </div>
                    <input type="hidden" name="project_interest" id="projectInterestField" value="">
                    <input type="hidden" name="quote_submit" value="1">
                    <button type="submit" class="w-full mt-6 py-3 btn-primary rounded-xl font-bold">SEND REQUEST</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="glass p-6 rounded-2xl border border-slate-800 text-center md:text-left">
                    <img src="alex.png" alt="Alex Safari - Founder" class="profile-img w-full object-cover rounded-xl mb-4" onerror="this.src='https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <h3 class="text-2xl font-bold mb-2">ALEX SAFARI</h3>
                    <p class="text-sky-400 text-sm mb-3">Founder & Lead Software Engineer</p>
                    <p class="text-slate-400 text-sm">3+ years experience in enterprise software, system architecture, and technical leadership. Passionate about solving complex business problems through innovative technology solutions.</p>
                </div>
                <div class="glass p-6 rounded-2xl border border-slate-800">
                    <h3 class="text-2xl font-bold mb-5">Quick Contact</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-slate-800/30 rounded-xl"><i class="fas fa-phone-alt text-sky-400 text-lg w-6"></i><div><p class="text-xs text-slate-400">Call Us</p><p class="font-bold text-sm">0794 963537 / 0707 441702</p></div></div>
                        <div class="flex items-center gap-3 p-3 bg-slate-800/30 rounded-xl"><i class="fab fa-whatsapp text-green-400 text-lg w-6"></i><div><p class="text-xs text-slate-400">WhatsApp</p><p class="font-bold text-sm">0794 963537</p></div></div>
                        <div class="flex items-center gap-3 p-3 bg-slate-800/30 rounded-xl"><i class="fas fa-envelope text-sky-400 text-lg w-6"></i><div><p class="text-xs text-slate-400">Email</p><p class="font-bold text-sm break-all">saftechresolutions@gmail.com</p></div></div>
                        <div class="flex items-center gap-3 p-3 bg-slate-800/30 rounded-xl"><i class="fas fa-map-marker-alt text-purple-400 text-lg w-6"></i><div><p class="text-xs text-slate-400">Location</p><p class="font-bold text-sm">Nairobi, Kenya (Remote Worldwide)</p></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pt-12 pb-6 border-t border-slate-800 bg-gradient-to-b from-slate-950 to-slate-900">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <span class="text-xl font-extrabold tracking-tighter uppercase">SAFTECH <span class="text-sky-400">RESOLUTIONS</span></span>
                    <p class="text-slate-400 text-xs leading-relaxed mt-3 max-w-md">Expert technical resolutions for modern business challenges. Enterprise software, networking, and cloud solutions.</p>
                    <div class="flex gap-2 mt-4">
                        <a href="https://www.facebook.com/profile.php?id=61557647096028" target="_blank" class="social-icon"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="https://www.instagram.com/alexsafaritu?igsh=amR4dTd4NWZoOXZm" target="_blank" class="social-icon"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="https://www.tiktok.com/@safarialex64gmail.co" target="_blank" class="social-icon"><i class="fab fa-tiktok text-sm"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in text-sm"></i></a>
                    </div>
                </div>
                <div><h4 class="text-sm font-bold mb-3">Quick Links</h4><ul class="space-y-1 text-slate-400 text-xs"><li><a href="#demos" class="footer-link">Live Demos</a></li><li><a href="#services" class="footer-link">Services</a></li><li><a href="#enterprise" class="footer-link">Enterprise</a></li><li><a href="#contact" class="footer-link">Contact</a></li></ul></div>
                <div><h4 class="text-sm font-bold mb-3">Support</h4><ul class="space-y-1 text-slate-400 text-xs"><li><a href="#quote" class="footer-link">Get a Quote</a></li><li class="mt-2"><i class="fas fa-map-marker-alt text-sky-400 mr-1"></i> Nairobi, Kenya</li></ul></div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-5 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <span>&copy; 2026 SAFTECH RESOLUTIONS. All rights reserved. Directed by Alex Safari.</span>
                <span class="mt-2 md:mt-0"><i class="fas fa-bolt text-yellow-500 mr-1"></i> Response &lt; 2h</span>
            </div>
        </div>
    </footer>

    <script>
        const modal = document.getElementById('quoteModal');
        const modalProjectName = document.getElementById('modalProjectName');

        function showQuoteModal(projectName) {
            modalProjectName.textContent = `You're interested in ${projectName}.`;
            document.getElementById('projectInterestField').value = projectName;
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }
        
        function closeModalAndScrollToQuote() {
            closeModal();
            const quoteSection = document.getElementById('quote');
            if (quoteSection) {
                quoteSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        window.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('active');
            document.getElementById('menuOverlay').classList.toggle('active');
            document.getElementById('hamburger').classList.toggle('active');
        }

        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', toggleMobileMenu);
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
