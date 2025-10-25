document.addEventListener('DOMContentLoaded', function() {
    // เลือกปุ่มเมนูและส่วนลิงก์
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    // ตรวจสอบว่าองค์ประกอบทั้งสองมีอยู่หรือไม่ ก่อนเพิ่ม Event Listener
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            // สลับคลาส 'open' เพื่อแสดงหรือซ่อนเมนู
            navLinks.classList.toggle('open');
            
            // เปลี่ยนไอคอนจากแฮมเบอร์เกอร์ (fas-bars) เป็น X (fas-times)
            const icon = menuToggle.querySelector('i');
            if (navLinks.classList.contains('open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // เพิ่มเอฟเฟกต์เมื่อเลื่อนหน้าเว็บ
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(28, 42, 56, 0.95)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.2)';
        } else {
            navbar.style.background = 'rgba(28, 42, 56, 0.7)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        }
        
        // เอฟเฟกต์พารัลแลกซ์สำหรับหยดน้ำ
        const drops = document.querySelectorAll('.drop');
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        drops.forEach((drop, index) => {
            const speed = (index + 1) * 0.2;
            drop.style.transform = `translateY(${rate * speed}px)`;
        });
    });
    
    // เพิ่มเอฟเฟกต์เมื่อโหลดหน้าเว็บเสร็จ
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 100);
    
    // เพิ่มเอฟเฟกต์ให้กับ feature items เมื่อโหลด
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.2}s`;
        item.style.animation = 'fadeInUp 0.8s ease-out forwards';
        item.style.opacity = '0';
    });
    
    // เพิ่ม Intersection Observer สำหรับ animation เมื่อ scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                entry.target.style.opacity = '1';
            }
        });
    }, observerOptions);
    
    // Observe feature items
    featureItems.forEach(item => {
        observer.observe(item);
    });
    
    // Observe content cards
    const contentCards = document.querySelectorAll('.content-card');
    contentCards.forEach(card => {
        observer.observe(card);
    });
});