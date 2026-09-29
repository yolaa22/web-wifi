// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Initialize all accordions closed except first one
    document.querySelectorAll('.accordion-content').forEach(content => {
        content.style.maxHeight = '0';
    });
    
    // Open first accordion by default
    const firstAccordion = document.querySelector('.accordion-button');
    if(firstAccordion) {
        firstAccordion.classList.add('active');
        const firstContent = firstAccordion.nextElementSibling;
        firstContent.style.maxHeight = firstContent.scrollHeight + 'px';
    }
    
    // Add fade-in animation to sections
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observe sections for animations
    document.querySelectorAll('.contact-area, .faq-area').forEach(section => {
        observer.observe(section);
    });
});

// Table search functionality enhancement
function initializeTableSearch() {
    const searchInputs = document.querySelectorAll('.search-input');
    
    searchInputs.forEach(input => {
        // Clear button
        const clearButton = document.createElement('span');
        clearButton.innerHTML = '&times;';
        clearButton.style.cssText = `
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 20px;
            display: none;
        `;
        
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(clearButton);
        
        input.addEventListener('input', function() {
            if(this.value.length > 0) {
                clearButton.style.display = 'block';
            } else {
                clearButton.style.display = 'none';
            }
        });
        
        clearButton.addEventListener('click', function() {
            input.value = '';
            input.dispatchEvent(new Event('keyup'));
            clearButton.style.display = 'none';
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeTableSearch);