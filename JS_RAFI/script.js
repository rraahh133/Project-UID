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
                let targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
    
                if (sectionHeight < viewportHeight) {
                    targetPosition -= (viewportHeight - sectionHeight) / 2;
                }
    
                const duration = 800; 
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

const scrollContainer = document.getElementById("scrollContainer");
const cards = document.querySelectorAll("#scrollContainer > div");
let cardWidth = cards[0].offsetWidth;

function updateScrollPosition() {
    const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
    if (scrollContainer.scrollLeft > maxScroll) {
        scrollContainer.scrollLeft = 0;
    } else if (scrollContainer.scrollLeft < 0) {
        scrollContainer.scrollLeft = maxScroll;
    }
}

scrollContainer.addEventListener('wheel', (e) => {
    if (e.deltaY < 0) {
        scrollContainer.scrollLeft -= cardWidth;
    } else {
        scrollContainer.scrollLeft += cardWidth;
    }

    e.preventDefault();

    updateScrollPosition();
});

let touchStartX = 0;
let touchEndX = 0;

scrollContainer.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

scrollContainer.addEventListener('touchmove', (e) => {
    touchEndX = e.changedTouches[0].screenX;
});

scrollContainer.addEventListener('touchend', () => {
    if (touchEndX < touchStartX) {
        scrollContainer.scrollLeft += cardWidth; 
    } else if (touchEndX > touchStartX) {
        scrollContainer.scrollLeft -= cardWidth;
    }

    touchStartX = 0;
    touchEndX = 0;

    updateScrollPosition();
});

window.addEventListener('resize', () => {
    cardWidth = cards[0].offsetWidth;
});

function toggleDropdown(event) {
    // Prevent default behavior if the clicked element is not a link
    if (event.target.tagName !== "A") {
        event.preventDefault();
    }

    // Get the parent dropdown container
    const dropdown = event.currentTarget;

    // Find the dropdown content within the clicked dropdown
    const dropdownContent = dropdown.querySelector('.dropdown-content, .mobile-dropdown-content');

    // Toggle the visibility of the dropdown content
    if (dropdownContent) {
        dropdownContent.classList.toggle('show');
    }
}

function toggleMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('open');
}






function toggleMenu() {
    const menu = document.getElementById("mobile-menu");
    menu.classList.toggle("open");
}
