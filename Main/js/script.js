document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.getElementsByClassName('order-button');
    
    Array.from(buttons).forEach(button => {
        button.addEventListener('click', function() {
            alert('Pesanan Anda telah diterima!');
        });
    });

    const accordions = document.querySelectorAll('.accordions__item');
    accordions.forEach(el => {
        el.addEventListener('click', (e) =>{
            const self = e.currentTarget;
            const control = self.querySelector('.accordions__control');
            const content = self.querySelector('.accordions__content');
    
            self.classList.toggle('open');
    
            if(self.classList.contains('open')){
                control.setAttribute('aria-expanded', true);
                content.setAttribute('aria-hidden', false);
                content.style.maxHeight = content.scrollHeight + 'px';
            }else{
                control.setAttribute('aria-expanded', false);
                content.setAttribute('aria-hidden', true);
                content.style.maxHeight = null;
            }
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
    
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
    
            if (targetElement) {
                const sectionHeight = targetElement.offsetHeight;
                const viewportHeight = window.innerHeight;
    
                // Adjust for smaller screens: scroll to the top if section fits the viewport
                let targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
    
                // Center only if the section is smaller than the viewport height
                if (sectionHeight < viewportHeight) {
                    targetPosition -= (viewportHeight - sectionHeight) / 2;
                }
    
                const duration = 800; // Duration for smooth scrolling
                let startPosition = window.pageYOffset;
                let startTime = null;
    
                function animationScroll(currentTime) {
                    if (!startTime) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const run = ease(timeElapsed, startPosition, targetPosition - startPosition, duration);
                    window.scrollTo(0, run);
                    if (timeElapsed < duration) requestAnimationFrame(animationScroll);
                }
    
                function ease(t, b, c, d) {
                    t /= d / 2;
                    if (t < 1) return c / 2 * t * t + b;
                    t--;
                    return -c / 2 * (t * (t - 2) - 1) + b;
                }
    
                requestAnimationFrame(animationScroll);
            }
        });
    });
    
    
    
    
    
    
});


function toggleMenu() {
    const menu = document.getElementById("mobile-menu");
    menu.classList.toggle("open"); // Toggle the 'open' class for the animation
}
