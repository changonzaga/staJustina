<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STA. JUSTINA HIGH SCHOOL</title>
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }
        body{
            font-family:'Arial',sans-serif;
            line-height:1.6;
            color:#000;
            background:#FFF;
        }

        /* ───────── HEADER ───────── */
        .header{
            background:rgba(85,0,0,.08);
            color:#FFF;
            padding:15px 0;
            position:fixed;
            top:0;
            left:0;
            right:0;
            z-index:1000;
            backdrop-filter:blur(10px);
            transition:opacity .3s ease;
        }
        .nav-container{
            max-width:1200px;
            margin:0 auto;
            padding:0 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .logo{
            display:flex;
            align-items:center;
            gap:15px;
        }
        .logo-image{
            width:50px;
            height:50px;
            background:#FFF url('path/to/your/logo.png') center/cover no-repeat;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#550000;
            font-size:12px;
            border:2px solid #FFF;
        }
        .logo-text h1{
            font-size:18px;
            font-weight:bold;
            color:#FFF;
        }
        .logo-text p{
            font-size:12px;
            opacity:.9;
            color:#FFF;
        }
        .nav-menu{
            display:flex;
            list-style:none;
            gap:30px;
        }
        .nav-menu li a{
            color:#FFF;
            text-decoration:none;
            font-weight:500;
            font-size:14px;
            transition:opacity .3s;
        }
        .nav-menu li a:hover{opacity:.8;}

        /* ───────── HERO ───────── */
        .hero{
            height:100vh;
            width:100vw;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            color:#FFF;
            position:relative;
            overflow:hidden;
            background:
                linear-gradient(rgba(0,0,0,.4),rgba(0,0,0,.4)),
                url('backend/src/images/landing-bg.png') center/cover no-repeat;
            background-color:#550000;
        }
        .hero-content{
            max-width:800px;
            padding:0 20px;
            position:relative;
            z-index:10;
        }
        .hero h1{
            font-size:4rem;
            margin-bottom:20px;
            text-shadow:2px 2px 4px rgba(0,0,0,.8);
            font-weight:bold;
        }
        .hero p{
            font-size:1.1rem;
            margin:0 auto 30px;
            max-width:600px;
            opacity:.95;
        }
        .cta-button{
            background:rgba(85,0,0,.75);
            color:#FFF;
            padding:15px 40px;
            border:2px solid rgba(85,0,0,.75);
            border-radius:156px;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
            text-transform:uppercase;
            transition:.3s;
            text-decoration:none;
        }
        .cta-button:hover{
            background:#FFF;
            color:#550000;
            transform:translateY(-2px);
            border:2px solid #550000;
        }

        /* ───────── MAIN CONTENT ───────── */
        .main-content{
            background:#FFF;
            padding-top:100px;
            position:relative;
            z-index:100;
        }

        /* ───────── ANNOUNCEMENTS ───────── */
        .announcements{padding:80px 0 60px;}
        .container{
            max-width:1200px;
            margin:0 auto;
            padding:0 20px;
        }
        .section-title{
            text-align:center;
            font-size:2.5rem;
            margin-bottom:50px;
            font-weight:bold;
        }
        
        /* Hero Slider */
        .announcement-hero{
            position:relative;
            height:400px;
            border-radius:15px;
            overflow:hidden;
            margin-bottom:60px;
            background:#f8f9fa;
        }
        .hero-slider{
            position:relative;
            width:100%;
            height:100%;
        }
        .hero-slide{
            position:absolute;
            width:100%;
            height:100%;
            opacity:0;
            transition:opacity .5s ease;
        }
        .hero-slide.active{opacity:1;}
        .hero-slide img{
            width:100%;
            height:100%;
            object-fit:cover;
        }
        .hero-overlay{
            position:absolute;
            bottom:0;
            left:0;
            right:0;
            background:linear-gradient(transparent,rgba(0,0,0,.7));
            color:#FFF;
            padding:40px;
        }
        .hero-overlay h3{font-size:1.8rem;margin-bottom:10px;}
        .hero-overlay p{font-size:1rem;opacity:.9;}
        
        /* Navigation arrows */
        .hero-nav{
            position:absolute;
            top:50%;
            transform:translateY(-50%);
            background:rgba(255,255,255,.9);
            border:none;
            width:50px;
            height:50px;
            border-radius:50%;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:1.5rem;
            color:#550000;
            transition:.3s;
            z-index:10;
        }
        .hero-nav:hover{background:#FFF;transform:translateY(-50%) scale(1.1);}
        .hero-prev{left:20px;}
        .hero-next{right:20px;}
        
        /* Main content area */
        .announcement-main{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:50px;
            align-items:start;
        }
        
        /* Left side with images - Figma prototype layout */
        .announcement-left{
            position:relative;
            display:grid;
            grid-template-columns:2fr 1fr;
            grid-template-rows:auto auto;
            gap:20px;
            height:400px;
        }
        
        /* Large main image */
        .announcement-image-large{
            grid-column:1;
            grid-row:1/3;
            border-radius:30px;
            overflow:hidden;
            position:relative;
        }
        .announcement-image-large img{
            width:100%;
            height:100%;
            object-fit:cover;
        }
        
        /* Small image on the right */
        .announcement-image-small{
            grid-column:2;
            grid-row:2;
            border-radius:20px;
            overflow:hidden;
            position:relative;
        }
        .announcement-image-small img{
            width:100%;
            height:100%;
            object-fit:cover;
        }
        
        /* Text overlay box */
        .announcement-overlay-box{
            grid-column:2;
            grid-row:1;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
        }
        .announcement-featured{
            background:#550000;
            color:#FFF;
            padding:20px 25px;
            border-radius:15px; 
            text-align:left;
            width:100%;
            box-shadow:0 4px 15px rgba(85,0,0,0.3);
        }
        .announcement-featured h3{
            font-size:1rem;
            margin-bottom:0;
            line-height:1.3;
            font-weight:600;
        }
        
        /* Right side with announcements */
        .announcement-right{
            padding-left:20px;
        }
        .announcement-right h2{
            font-size:2rem;
            margin-bottom:15px;
            color:#000;
        }
        .announcement-right > p{
            font-size:1rem;
            color:#666;
            margin-bottom:40px;
            line-height:1.6;
        }
        .announcement-list{
            display:flex;
            flex-direction:column;
            gap:25px;
        }
        .announcement-item{
            display:flex;
            gap:20px;
            align-items:flex-start;
        }
        .announcement-icon{
            width:40px;
            height:40px;
            background:#550000;
            border-radius:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#FFF;
            font-size:1.1rem;
            flex-shrink:0;
        }
        .announcement-content h4{
            color:#000;
            margin-bottom:8px;
            font-size:1.1rem;
            font-weight:600;
        }
        .announcement-content p{
            font-size:.9rem;
            line-height:1.5;
            color:#666;
        }

        /* ───────── SERVICES ───────── */
        .services{padding:60px 0;background:#f8f9fa;}
        .services-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:30px;
            margin-top:40px;
        }
        .service-card{
            background:#FFF;
            text-align:center;
            padding:30px 20px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,.1);
            transition:.3s;
            cursor:pointer;
            border:1px solid #f0f0f0;
        }
        .service-card:hover{
            transform:translateY(-5px);
            box-shadow:0 10px 25px rgba(85,0,0,.2);
            border-color:#550000;
        }
        .service-icon{
            width:60px;
            height:60px;
            background:#550000;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:0 auto 20px;
            color:#FFF;
            font-size:1.5rem;
        }
        .service-card h4{margin-bottom:10px;font-size:.9rem;line-height:1.3;}

        /* ───────── ICON STYLES ───────── */
        .icon-svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .service-icon .icon-svg {
            width: 32px;
            height: 32px;
        }
        .announcement-icon .icon-svg {
            width: 20px;
            height: 20px;
        }

        /* ───────── FOOTER ───────── */
        .footer{
            background:#000;
            color:#FFF;
            padding:40px 0;
            text-align:center;
        }

        /* ───────── RESPONSIVE ───────── */
        @media(max-width:768px){
            .nav-menu{display:none;}
            .hero h1{font-size:2.5rem;}
            .announcement-main{grid-template-columns:1fr;}
            .announcement-hero{height:300px;}
            .hero-overlay{padding:20px;}
            .hero-overlay h3{font-size:1.4rem;}
            .announcement-right{padding-left:0;margin-top:30px;}
            .announcement-left{
                grid-template-columns:1fr;
                grid-template-rows:auto auto auto;
                height:auto;
                gap:15px;
            }
            .announcement-image-large{
                grid-column:1;
                grid-row:1;
                height:250px;
                border-radius:20px;
            }
            .announcement-overlay-box{
                grid-column:1;
                grid-row:2;
            }
            .announcement-image-small{
                grid-column:1;
                grid-row:3;
                height:150px;
                border-radius:15px;
            }
            .services-grid{grid-template-columns:repeat(2,1fr);}
        }
        @media(max-width:480px){
            .services-grid{grid-template-columns:1fr;}
            .announcement-hero{height:250px;}
            .announcement-left{height:auto;}
            .announcement-image-large{height:200px;border-radius:15px;}
            .announcement-image-small{height:120px;border-radius:10px;}
            .announcement-featured{padding:15px 20px;}
        }
    </style>
</head>
<body>
    <!-- ───────── HEADER ───────── -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <div class="logo-image">LOGO</div>
                <div class="logo-text">
                    <h1>STA. JUSTINA HIGH SCHOOL</h1>
                    <p>Nurturing Excellence</p>
                </div>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="<?= site_url('login') ?>">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- ───────── HERO ───────── -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>STA. JUSTINA HIGH SCHOOL</h1>
            <p>Nurturing minds, Building futures &mdash; A comprehensive educational experience based on academic excellence and character development</p>
            <a href="#services" class="cta-button">Get Started</a>
        </div>
    </section>

    <!-- ───────── MAIN CONTENT ───────── -->
    <div class="main-content">
        <!-- Announcements -->
        <section class="announcements" id="announcements">
            <div class="container">
                <h2 class="section-title">Announcements</h2>
                
                <!-- Hero Slider -->
                <div class="announcement-hero">
                    <div class="hero-slider">
                        <div class="hero-slide active">
                            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Classroom scene">
                            <div class="hero-overlay">
                                <h3>Welcome to Our Learning Community</h3>
                                <p>Discover excellence in education through our comprehensive academic programs</p>
                            </div>
                        </div>
                        <div class="hero-slide">
                            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9d1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Students studying">
                            <div class="hero-overlay">
                                <h3>Academic Excellence</h3>
                                <p>Our students achieve outstanding results through dedicated learning</p>
                            </div>
                        </div>
                        <div class="hero-slide">
                            <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="School activities">
                            <div class="hero-overlay">
                                <h3>Extra-Curricular Activities</h3>
                                <p>Building character and skills beyond the classroom</p>
                            </div>
                        </div>
                    </div>
                    <button class="hero-nav hero-prev" onclick="changeSlide(-1)">‹</button>
                    <button class="hero-nav hero-next" onclick="changeSlide(1)">›</button>
                </div>
                
                <!-- Main Content -->
                <div class="announcement-main">
                    <div class="announcement-left">
                        <!-- Large main image -->
                        <div class="announcement-image-large">
                            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Library study area">
                        </div>
                        
                        <!-- Text overlay box -->
                        <div class="announcement-overlay-box">
                            <div class="announcement-featured">
                                <h3>📚 Lorem ipsum dolor sit amet.</h3>
                            </div>
                        </div>
                        
                        <!-- Small image on the right -->
                        <div class="announcement-image-small">
                            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Students collaborating">
                        </div>
                    </div>
                    
                    <div class="announcement-right">
                        <h2>Latest Updates & News</h2>
                        <p>Stay informed about the latest happenings, events, and important announcements from Sta. Justina High School. Our commitment to academic excellence continues with these updates.</p>
                        
                        <div class="announcement-list">
                            <div class="announcement-item">
                                <div class="announcement-icon">
                                    <svg class="icon-svg" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </div>
                                <div class="announcement-content">
                                    <h4>New Student Enrollment Now Open</h4>
                                    <p>We are now accepting applications for the upcoming school year. Visit our admissions office or apply online through our registration portal.</p>
                                </div>
                            </div>
                            
                            <div class="announcement-item">
                                <div class="announcement-icon">
                                    <svg class="icon-svg" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </div>
                                <div class="announcement-content">
                                    <h4>Quarterly Examination Schedule Released</h4>
                                    <p>The examination schedule for the third quarter has been posted. Please check your student portal for specific dates and room assignments.</p>
                                </div>
                            </div>
                            
                            <div class="announcement-item">
                                <div class="announcement-icon">
                                    <svg class="icon-svg" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10,9 9,9 8,9"/>
                                    </svg>
                                </div>
                                <div class="announcement-content">
                                    <h4>Academic Records System Update</h4>
                                    <p>Our online grading system will undergo maintenance this weekend. All records will be updated and accessible by Monday morning.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="services" id="services">
            <div class="container">
                <h2 class="section-title">Online Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <svg class="icon-svg" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <h4>Enrolling Student &<br>Online Registration</h4>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg class="icon-svg" viewBox="0 0 24 24">
                                <path d="M3 3v5h5"/>
                                <path d="M21 21v-5h-5"/>
                                <path d="M21 3L9 15l-6-6"/>
                            </svg>
                        </div>
                        <h4>Assessment<br>Periodical Tracking</h4>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg class="icon-svg" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                        </div>
                        <h4>Scholastic Ease &<br>Record Management</h4>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg class="icon-svg" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <h4>Exam Tracking<br>& Schedule</h4>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ───────── FOOTER ───────── -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Sta. Justina High School. All rights reserved.</p>
        </div>
    </footer>

    <!-- ───────── SCRIPTS ───────── -->
    <script>
        /* Smooth scroll for internal links */
        document.querySelectorAll('a[href^="#"]').forEach(anchor=>{
            anchor.addEventListener('click',e=>{
                e.preventDefault();
                const target=document.querySelector(anchor.getAttribute('href'));
                target && target.scrollIntoView({behavior:'smooth',block:'start'});
            });
        });

        /* Parallax + fading header */
        window.addEventListener('scroll',()=>{
            const scrolled=window.pageYOffset||document.documentElement.scrollTop;

            /* parallax hero */
            const hero=document.querySelector('.hero');
            hero && (hero.style.transform=`translateY(${scrolled*0.5}px)`);

            /* header fade */
            const header=document.querySelector('.header');
            if(header){
                const MAX_SCROLL=200;
                const MIN_OPACITY=0;
                let opacity=1 - scrolled/MAX_SCROLL;
                opacity=Math.max(opacity,MIN_OPACITY);
                header.style.opacity=opacity;
            }
        });

        /* Reveal on scroll (cards & announcements) */
        const observer=new IntersectionObserver(entries=>{
            entries.forEach(entry=>{
                if(entry.isIntersecting){
                    entry.target.style.opacity='1';
                    entry.target.style.transform='translateY(0)';
                }
            });
        },{threshold:0.1,rootMargin:'0px 0px -50px 0px'});

        document.querySelectorAll('.announcement-item,.service-card,.announcement-image-large,.announcement-image-small').forEach(el=>{
            el.style.opacity='0';
            el.style.transform='translateY(30px)';
            el.style.transition='opacity .6s ease,transform .6s ease';
            observer.observe(el);
        });

        /* Hero slider functionality */
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const totalSlides = slides.length;

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            currentSlide = (n + totalSlides) % totalSlides;
            slides[currentSlide].classList.add('active');
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        /* Auto-advance slides */
        setInterval(() => {
            changeSlide(1);
        }, 5000);
    </script>
</body>
</html>