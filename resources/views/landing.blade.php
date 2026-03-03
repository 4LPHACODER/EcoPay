<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPay - Smart Bottle Recycling Rewards</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; overflow-x: hidden; }
        
        /* Animated gradient background */
        .hero-bg {
            background: linear-gradient(135deg, #0a0f1a 0%, #111827 25%, #0f172a 50%, #134e4a 75%, #0a0f1a 100%);
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradientBG {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
        
        /* Glow effects */
        .glow-green { box-shadow: 0 0 60px rgba(34, 197, 94, 0.4), 0 0 100px rgba(34, 197, 94, 0.2); }
        .glow-teal { box-shadow: 0 0 60px rgba(20, 184, 166, 0.4), 0 0 100px rgba(20, 184, 166, 0.2); }
        
        /* Glass morphism */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Text gradients */
        .text-gradient {
            background: linear-gradient(135deg, #22c55e 0%, #14b8a6 50%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Carousel */
        .carousel-container { position: relative; overflow: hidden; }
        .carousel-track { 
            display: flex; 
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .carousel-slide { 
            min-width: 100%; 
            padding: 120px 20px 80px;
        }
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }
        .carousel-nav:hover { background: rgba(255, 255, 255, 0.2); transform: translateY(-50%) scale(1.1); }
        .carousel-prev { left: 20px; }
        .carousel-next { right: 20px; }
        
        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 40px;
        }
        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .carousel-dot.active { 
            background: #22c55e; 
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
            transform: scale(1.2);
        }
        .carousel-dot:hover:not(.active) { background: rgba(255, 255, 255, 0.4); }
        
        /* Animations */
        .fade-in { opacity: 0; transform: translateY(30px); animation: fadeInUp 0.8s ease forwards; }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.4);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.15); transform: translateY(-2px); }
        
        /* Feature cards hover */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }
        
        /* Stats */
        .stat-number {
            background: linear-gradient(135deg, #22c55e 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Section padding */
        .section-padding { padding: 100px 0; }
        
        /* Nav */
        .nav-glass {
            background: rgba(10, 15, 26, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="hero-bg text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 nav-glass">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('EcoPay.png') }}" alt="EcoPay" class="h-12 w-auto">
                    <span class="text-2xl font-bold text-gradient">EcoPay</span>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium">Log In</a>
                        <a href="{{ route('register') }}" class="btn-primary">
                            Get Started
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <div class="carousel-container relative">
        <div class="carousel-track" id="carouselTrack">
            <!-- Slide 1 -->
            <div class="carousel-slide">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="fade-in">
                            <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                                Turn Recycling Into
                                <span class="text-gradient">Rewards</span>
                            </h1>
                            <p class="text-xl text-gray-400 mt-8 max-w-xl leading-relaxed">
                                EcoPay automatically detects plastic and metal bottles, tracks your eco-impact, and rewards you with coins you can actually use.
                            </p>
                            <div class="flex flex-wrap gap-4 mt-10">
                                <a href="{{ route('register') }}" class="btn-primary">
                                    Start Recycling Free
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                                <a href="{{ route('login') }}" class="btn-secondary">Log In</a>
                            </div>
                        </div>
                        <div class="flex justify-center fade-in delay-1">
                            <div class="relative float-animation">
                                <div class="w-80 h-80 rounded-full glow-green flex items-center justify-center">
                                    <img src="{{ asset('EcoPay.png') }}" alt="EcoPay" class="w-64 h-64 object-contain">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="fade-in">
                            <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                                Smart
                                <span class="text-gradient">Detection</span>
                            </h1>
                            <p class="text-xl text-gray-400 mt-8 max-w-xl leading-relaxed">
                                Our AI-powered system automatically distinguishes between plastic and metal bottles, ensuring accurate tracking and fair rewards.
                            </p>
                            <div class="flex gap-8 mt-10">
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-gradient">99%</div>
                                    <div class="text-gray-400 mt-2">Accuracy</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-gradient"><2s</div>
                                    <div class="text-gray-400 mt-2">Detection Time</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center fade-in delay-1">
                            <div class="glass-card rounded-3xl p-10">
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="glass rounded-2xl p-6 text-center">
                                        <div class="text-5xl mb-3">🧴</div>
                                        <div class="text-green-400 font-bold text-lg">Plastic</div>
                                        <div class="text-gray-500 text-sm mt-1">Auto-detected</div>
                                    </div>
                                    <div class="glass rounded-2xl p-6 text-center">
                                        <div class="text-5xl mb-3">🥫</div>
                                        <div class="text-gray-300 font-bold text-lg">Metal</div>
                                        <div class="text-gray-500 text-sm mt-1">Auto-detected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="fade-in">
                            <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                                Real-Time
                                <span class="text-gradient">Dashboard</span>
                            </h1>
                            <p class="text-xl text-gray-400 mt-8 max-w-xl leading-relaxed">
                                Monitor your recycling activity with live updates. Track bottles collected, coins earned, and view your complete activity history.
                            </p>
                            <div class="flex gap-4 mt-10">
                                <a href="{{ route('register') }}" class="btn-primary">
                                    Try It Now
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </div>
                        <div class="flex justify-center fade-in delay-1">
                            <div class="glass-card rounded-3xl p-8 w-full max-w-md">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center p-4 glass rounded-2xl">
                                        <span class="text-gray-400">Overall Bottles</span>
                                        <span class="text-2xl font-bold text-green-400">1,247</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 glass rounded-2xl">
                                        <span class="text-gray-400">Coins Earned</span>
                                        <span class="text-2xl font-bold text-yellow-400">$124.70</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 glass rounded-2xl">
                                        <span class="text-gray-400">CO₂ Saved</span>
                                        <span class="text-2xl font-bold text-teal-400">45kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Navigation -->
        <button class="carousel-nav carousel-prev" onclick="moveSlide(-1)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button class="carousel-nav carousel-next" onclick="moveSlide(1)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Dots -->
        <div class="carousel-dots" id="carouselDots"></div>
    </div>

    <!-- Features Section -->
    <section class="section-padding">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">Why Choose EcoPay?</h2>
                <p class="text-xl text-gray-400">Everything you need to make a real environmental impact</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card glass-card rounded-3xl p-10 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-green-500/20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Earn Coins</h3>
                    <p class="text-gray-400 leading-relaxed">Get rewarded with coins for every bottle you recycle. Redeem them for exclusive perks and discounts.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card glass-card rounded-3xl p-10 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-purple-500/20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Smart Detection</h3>
                    <p class="text-gray-400 leading-relaxed">Advanced AI automatically identifies plastic vs metal bottles with 99% accuracy in seconds.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card glass-card rounded-3xl p-10 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-teal-500/20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Live Analytics</h3>
                    <p class="text-gray-400 leading-relaxed">Watch your impact grow in real-time. View detailed stats, trends, and environmental contributions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-padding glass">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl lg:text-6xl font-bold stat-number">50K+</div>
                    <div class="text-gray-400 mt-3 text-lg">Bottles Recycled</div>
                </div>
                <div>
                    <div class="text-5xl lg:text-6xl font-bold stat-number">10K+</div>
                    <div class="text-gray-400 mt-3 text-lg">Active Users</div>
                </div>
                <div>
                    <div class="text-5xl lg:text-6xl font-bold stat-number">25T</div>
                    <div class="text-gray-400 mt-3 text-lg">CO₂ Saved</div>
                </div>
                <div>
                    <div class="text-5xl lg:text-6xl font-bold stat-number">$50K+</div>
                    <div class="text-gray-400 mt-3 text-lg">Rewards Given</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section-padding">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">How It Works</h2>
                <p class="text-xl text-gray-400">Start recycling in three simple steps</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-green-400">1</div>
                    <h3 class="text-2xl font-bold mb-4">Create Account</h3>
                    <p class="text-gray-400">Sign up for free and get your personal EcoPay dashboard.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-purple-500/20 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-purple-400">2</div>
                    <h3 class="text-2xl font-bold mb-4">Scan Bottles</h3>
                    <p class="text-gray-400">Place your bottles in the recycling bin. Our system detects and counts them automatically.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-teal-500/20 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-teal-400">3</div>
                    <h3 class="text-2xl font-bold mb-4">Earn Rewards</h3>
                    <p class="text-gray-400">Watch your coins grow and redeem them for exciting rewards!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section-padding glass">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">What Users Say</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full bg-green-500/30 flex items-center justify-center font-bold text-xl">S</div>
                        <div class="ml-4">
                            <div class="font-bold text-lg">Sarah M.</div>
                            <div class="text-gray-500 text-sm">Eco Enthusiast</div>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed">"EcoPay makes recycling so rewarding! I've earned over $50 in coins just by doing what I already do."</p>
                </div>
                <div class="glass-card rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full bg-purple-500/30 flex items-center justify-center font-bold text-xl">J</div>
                        <div class="ml-4">
                            <div class="font-bold text-lg">James K.</div>
                            <div class="text-gray-500 text-sm">Campus Student</div>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed">"The automatic detection is amazing. Just drop bottles and everything is tracked. Love the real-time updates!"</p>
                </div>
                <div class="glass-card rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-full bg-teal-500/30 flex items-center justify-center font-bold text-xl">M</div>
                        <div class="ml-4">
                            <div class="font-bold text-lg">Maria L.</div>
                            <div class="text-gray-500 text-sm">Office Manager</div>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed">"We installed EcoPay at our office. Recycling has increased 300%! Everyone loves the friendly competition."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-600/10 to-teal-600/10"></div>
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative">
            <h2 class="text-4xl lg:text-6xl font-bold mb-8">Ready to Make a Difference?</h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">Join thousands of eco-warriors who are already earning rewards while saving the planet.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary">
                    Get Started Free
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('login') }}" class="btn-secondary">Log In</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 glass border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-3 mb-6 md:mb-0">
                    <img src="{{ asset('EcoPay.png') }}" alt="EcoPay" class="h-10 w-auto">
                    <span class="text-xl font-bold text-gradient">EcoPay</span>
                </div>
                <div class="flex gap-8 text-gray-400">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
                <div class="mt-6 md:mt-0 text-gray-500">
                    © 2024 EcoPay. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const slides = document.querySelectorAll('.carousel-slide');
        const dotsContainer = document.getElementById('carouselDots');
        let autoplayInterval;

        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot' + (index === 0 ? ' active' : '');
            dot.onclick = () => goToSlide(index);
            dotsContainer.appendChild(dot);
        });

        const dots = dotsContainer.querySelectorAll('.carousel-dot');

        function updateCarousel() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoplay();
        }

        function moveSlide(direction) {
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            updateCarousel();
            resetAutoplay();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel();
        }

        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            startAutoplay();
        }

        // Pause on hover
        const carousel = document.querySelector('.carousel-container');
        carousel.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
        carousel.addEventListener('mouseleave', startAutoplay);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') moveSlide(-1);
            if (e.key === 'ArrowRight') moveSlide(1);
        });

        // Touch swipe
        let touchStartX = 0;
        let touchEndX = 0;
        carousel.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX);
        carousel.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) moveSlide(1);
            if (touchEndX - touchStartX > 50) moveSlide(-1);
        });

        // Initialize
        startAutoplay();
    </script>
</body>
</html>
