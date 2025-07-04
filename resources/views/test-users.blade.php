<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Users SPA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'accent': '#00fff7',      // Neon cyan
                        'accent2': '#6366f1',     // Neon blue
                        'accent3': '#ffe600',     // Neon yellow
                        'laravel': '#ff2d20',     // Laravel red
                        'vue': '#42b883',         // Vue green
                        'docker': '#2496ed',      // Docker blue
                        'linkedin': '#0a66c2',    // LinkedIn blue
                        'github': '#c9d1d9',      // GitHub gray
                        'tailwind': '#38bdf8',    // Tailwind blue
                        'uiux': '#f59e42',        // UI/UX orange
                        'soft': '#a5b4fc',
                        'soft2': '#67e8f9',
                        'soft3': '#f9a8d4',
                    },
                    boxShadow: {
                        'neon': '0 0 24px 2px #00fff7, 0 0 48px 4px #6366f1',
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            background: #18181b;
        }
        /* Cyberpunk animated background (cyan, blue, yellow) */
        .cyberpunk-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            overflow: hidden;
        }
        .cyberpunk-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.5;
            mix-blend-mode: lighten;
            animation-timing-function: ease-in-out;
        }
        .glow1 {
            width: 700px;
            height: 700px;
            background: radial-gradient(circle at 30% 30%, #00fff7 0%, #18181b 80%);
            left: -250px;
            top: -180px;
            animation: moveGlow1 16s infinite alternate;
        }
        .glow2 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle at 70% 60%, #6366f1 0%, #18181b 80%);
            right: -200px;
            top: 120px;
            animation: moveGlow2 20s infinite alternate;
        }
        .glow3 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle at 50% 50%, #ffe600 0%, #18181b 80%);
            left: 55%;
            bottom: -180px;
            transform: translateX(-50%);
            animation: moveGlow3 18s infinite alternate;
        }
        .glow4 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle at 80% 20%, #6366f1 0%, #18181b 80%);
            left: 60vw;
            top: 60vh;
            animation: moveGlow4 22s infinite alternate;
        }
        @keyframes moveGlow1 {
            0% { left: -250px; top: -180px; }
            50% { left: 120px; top: -100px; }
            100% { left: -100px; top: 80px; }
        }
        @keyframes moveGlow2 {
            0% { right: -200px; top: 120px; }
            40% { right: 80px; top: 250px; }
            100% { right: -80px; top: 0px; }
        }
        @keyframes moveGlow3 {
            0% { bottom: -180px; }
            50% { bottom: 100px; }
            100% { bottom: -80px; }
        }
        @keyframes moveGlow4 {
            0% { left: 60vw; top: 60vh; }
            50% { left: 65vw; top: 55vh; }
            100% { left: 55vw; top: 65vh; }
        }
        .glass {
            background: rgba(24, 24, 27, 0.96);
            box-shadow: 0 8px 32px 0 rgba(0,255,247, 0.10), 0 0 0 2px rgba(99,102,241,0.10);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(0,255,247,0.10);
            position: relative;
            z-index: 1;
        }
        .fancy-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, #00fff7 0%, #6366f1 50%, #ffe600 100%);
            opacity: 0.3;
            margin: 2rem 0;
        }
        a:focus-visible {
            outline: 2px solid #00fff7;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="dark text-gray-100 font-sans antialiased" style="background-color: #18181b;">
    <!-- Cyberpunk Animated Background -->
    <div class="cyberpunk-bg">
        <div class="cyberpunk-glow glow1"></div>
        <div class="cyberpunk-glow glow2"></div>
        <div class="cyberpunk-glow glow3"></div>
        <div class="cyberpunk-glow glow4"></div>
    </div>
    <div class="min-h-screen py-12 px-4 flex flex-col relative z-10">
        <div class="max-w-6xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-10 animate-fade-in-down">
                <div>
                    <h1 class="text-5xl font-extrabold mb-2 drop-shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer leading-tight">
                        Hi, I'm <span class="text-accent2">[Your Name]</span>
                    </h1>
                    <p class="text-2xl text-accent font-medium mt-2">Web Developer & Designer</p>
                </div>
                <div class="relative mt-8 md:mt-0">
                    <img src="https://avatars.githubusercontent.com/u/9919?s=200&v=4" alt="Profile" class="w-28 h-28 rounded-full shadow-2xl border-4 border-accent2 transition-transform duration-300 hover:scale-110 bg-[#232329] object-cover">
                    <span class="absolute -bottom-2 -right-2 bg-accent2 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-pulse">Online</span>
                </div>
            </div>

            <hr class="fancy-divider">

            <!-- About Section -->
            <div class="glass p-10 mb-10 flex flex-col md:flex-row items-center animate-fade-in-up">
                <div class="flex-1">
                    <h2 class="text-3xl font-semibold text-accent2 mb-3">About Me</h2>
                    <p class="text-gray-200 mb-6 text-lg leading-relaxed">
                        <span class="text-accent">🚀</span> I love building modern, accessible web applications with a focus on user experience and performance.<br>
                        <span class="text-accent3">💡</span> Experienced in <span class="font-semibold text-laravel">Laravel</span>, <span class="font-semibold text-vue">Vue.js</span>, <span class="font-semibold text-tailwind">Tailwind CSS</span>, and <span class="font-semibold text-docker">Docker</span>.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-4">
                        <a href="mailto:your.email@example.com" class="flex items-center gap-2 text-accent hover:text-white hover:bg-accent/30 px-4 py-2 rounded-full transition focus-visible:ring-2 focus-visible:ring-accent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 12H8m8 0a4 4 0 1 0-8 0 4 4 0 0 0 8 0z"/></svg>
                            <span>Email</span>
                        </a>
                        <a href="https://github.com/yourusername" target="_blank" class="flex items-center gap-2 text-github hover:text-white hover:bg-github/30 px-4 py-2 rounded-full transition focus-visible:ring-2 focus-visible:ring-github">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.73.5.5 5.73.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56 0-.28-.01-1.02-.02-2-3.2.7-3.88-1.54-3.88-1.54-.53-1.34-1.3-1.7-1.3-1.7-1.06-.72.08-.71.08-.71 1.17.08 1.79 1.2 1.79 1.2 1.04 1.78 2.73 1.27 3.4.97.11-.75.41-1.27.75-1.56-2.56-.29-5.26-1.28-5.26-5.7 0-1.26.45-2.29 1.19-3.1-.12-.29-.52-1.46.11-3.04 0 0 .97-.31 3.18 1.18a11.1 11.1 0 0 1 2.9-.39c.98 0 1.97.13 2.9.39 2.2-1.49 3.17-1.18 3.17-1.18.63 1.58.23 2.75.11 3.04.74.81 1.19 1.84 1.19 3.1 0 4.43-2.7 5.41-5.27 5.7.42.36.8 1.08.8 2.18 0 1.57-.01 2.84-.01 3.23 0 .31.21.67.8.56C20.71 21.39 24 17.08 24 12c0-6.27-5.23-11.5-12-11.5z"/></svg>
                            <span>GitHub</span>
                        </a>
                        <a href="https://linkedin.com/in/yourusername" target="_blank" class="flex items-center gap-2 text-linkedin hover:text-white hover:bg-linkedin/30 px-4 py-2 rounded-full transition focus-visible:ring-2 focus-visible:ring-linkedin">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5v-14c0-2.76-2.24-5-5-5zm-11 19h-3v-9h3v9zm-1.5-10.28c-.97 0-1.75-.79-1.75-1.75s.78-1.75 1.75-1.75 1.75.79 1.75 1.75-.78 1.75-1.75 1.75zm13.5 10.28h-3v-4.5c0-1.08-.02-2.47-1.5-2.47-1.5 0-1.73 1.17-1.73 2.39v4.58h-3v-9h2.88v1.23h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.59v5.72z"/></svg>
                            <span>LinkedIn</span>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="fancy-divider">

            <!-- Projects Section -->
            <h2 class="text-3xl font-semibold text-accent2 mb-6 animate-fade-in-down">Featured Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 fade-in">
                <!-- Project Box 1 -->
                <a href="https://yourproject1.com" target="_blank" class="group glass overflow-hidden hover:shadow-neon transition-shadow duration-300 relative flex flex-col h-full">
                    <img src="https://source.unsplash.com/400x200/?website,code,cyberpunk" alt="Project 1" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-laravel mb-2 group-hover:text-accent transition-colors duration-200">Project One</h3>
                        <p class="text-gray-200 mb-4 flex-1">A modern web app built with Laravel and Vue.js.</p>
                        <div class="flex gap-2 flex-wrap">
                            <span class="inline-block bg-laravel/20 text-laravel px-3 py-1 rounded-full font-semibold shadow text-xs">Laravel</span>
                            <span class="inline-block bg-vue/20 text-vue px-3 py-1 rounded-full font-semibold shadow text-xs">Vue.js</span>
                        </div>
                    </div>
                    <span class="absolute top-3 right-3 bg-accent3 text-xs px-2 py-1 rounded-full shadow text-black font-bold animate-pulse">NEW</span>
                </a>
                <!-- Project Box 2 -->
                <a href="https://yourproject2.com" target="_blank" class="group glass overflow-hidden hover:shadow-neon transition-shadow duration-300 relative flex flex-col h-full">
                    <img src="https://source.unsplash.com/400x200/?app,design,cyberpunk" alt="Project 2" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-tailwind mb-2 group-hover:text-accent2 transition-colors duration-200">Project Two</h3>
                        <p class="text-gray-200 mb-4 flex-1">A creative portfolio site with Tailwind CSS.</p>
                        <div class="flex gap-2 flex-wrap">
                            <span class="inline-block bg-tailwind/20 text-tailwind px-3 py-1 rounded-full font-semibold shadow text-xs">Tailwind CSS</span>
                            <span class="inline-block bg-uiux/20 text-uiux px-3 py-1 rounded-full font-semibold shadow text-xs">UI/UX</span>
                        </div>
                    </div>
                </a>
                <!-- Project Box 3 -->
                <a href="https://yourproject3.com" target="_blank" class="group glass overflow-hidden hover:shadow-neon transition-shadow duration-300 relative flex flex-col h-full">
                    <img src="https://source.unsplash.com/400x200/?docker,cloud,cyberpunk" alt="Project 3" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-docker mb-2 group-hover:text-accent2 transition-colors duration-200">Project Three</h3>
                        <p class="text-gray-200 mb-4 flex-1">A scalable Dockerized API for modern web apps.</p>
                        <div class="flex gap-2 flex-wrap">
                            <span class="inline-block bg-docker/20 text-docker px-3 py-1 rounded-full font-semibold shadow text-xs">Docker</span>
                            <span class="inline-block bg-accent3/20 text-accent3 px-3 py-1 rounded-full font-semibold shadow text-xs">API</span>
                        </div>
                    </div>
                </a>
            </div>

            <hr class="fancy-divider">

            <!-- Skills Section -->
            <h2 class="text-3xl font-semibold text-accent3 mb-6 animate-fade-in-down">Skills</h2>
            <div class="flex flex-wrap gap-4 mb-12 fade-in">
                <span class="bg-laravel/20 text-laravel px-6 py-2 rounded-full font-semibold shadow hover:bg-laravel/40 hover:text-white transition">Laravel</span>
                <span class="bg-vue/20 text-vue px-6 py-2 rounded-full font-semibold shadow hover:bg-vue/40 hover:text-white transition">Vue.js</span>
                <span class="bg-tailwind/20 text-tailwind px-6 py-2 rounded-full font-semibold shadow hover:bg-tailwind/40 hover:text-white transition">Tailwind CSS</span>
                <span class="bg-docker/20 text-docker px-6 py-2 rounded-full font-semibold shadow hover:bg-docker/40 hover:text-white transition">Docker</span>
                <span class="bg-uiux/20 text-uiux px-6 py-2 rounded-full font-semibold shadow hover:bg-uiux/40 hover:text-white transition">UI/UX Design</span>
                <span class="bg-github/20 text-github px-6 py-2 rounded-full font-semibold shadow hover:bg-github/40 hover:text-white transition">Git</span>
            </div>

            <footer class="text-center text-gray-400 text-base mt-12 animate-fade-in-up">
                &copy; {{ date('Y') }} <span class="font-bold text-accent2">[Your Name]</span>. All rights reserved.
            </footer>
        </div>
    </div>
    <!-- Animations -->
    <script>
        // Fade in from above for header/about/footer
        document.querySelectorAll('.animate-fade-in-up').forEach((el, i) => {
            el.style.opacity = 0;
            setTimeout(() => {
                el.style.transition = 'opacity 1s, transform 1s';
                el.style.opacity = 1;
                el.style.transform = 'translateY(0)';
            }, 200 + i * 150);
            el.style.transform = 'translateY(40px)';
        });
        document.querySelectorAll('.animate-fade-in-down').forEach((el, i) => {
            el.style.opacity = 0;
            setTimeout(() => {
                el.style.transition = 'opacity 1s, transform 1s';
                el.style.opacity = 1;
                el.style.transform = 'translateY(0)';
            }, 200 + i * 150);
            el.style.transform = 'translateY(-40px)';
        });
        // Simple fade in for projects and skills
        document.querySelectorAll('.fade-in').forEach((el, i) => {
            el.style.opacity = 0;
            setTimeout(() => {
                el.style.transition = 'opacity 1.2s';
                el.style.opacity = 1;
            }, 400 + i * 200);
        });
    </script>
</body>
</html>