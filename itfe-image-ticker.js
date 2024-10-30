document.addEventListener("DOMContentLoaded", function() {
    function setupMarquee(container, direction, speed, imageWidth, spacing) {
        const content = container.querySelector('.itfe-image-ticker-content');
        if (!content) return;
        
        // Set image width and spacing
        content.querySelectorAll('img').forEach(img => {
            img.style.width = `${imageWidth}px`;
            img.style.marginRight = `${spacing}px`;
        });
        
        // Clone content for seamless scrolling
        const originalContent = content.innerHTML;
        content.innerHTML += originalContent; // Duplicate content for seamless scrolling
        const contentWidth = content.scrollWidth / 2; // Calculate the width of the duplicated content
        let currentScroll = direction === 'left-to-right' ? -contentWidth : 0; // Start position based on direction
        const scrollAmount = 1; // Scroll amount per frame
        
        // Adjust the interval based on the speed setting
        const interval = Math.max(1, 31 - speed); // Ensure interval is at least 1ms, invert speed range
        
        function scroll() {
            if (direction === 'left-to-right') {
                currentScroll += scrollAmount;
                if (currentScroll >= 0) {
                    currentScroll = -contentWidth; // Reset position for continuous loop
                }
            } else if (direction === 'right-to-left') {
                currentScroll -= scrollAmount;
                if (currentScroll <= -contentWidth) {
                    currentScroll = 0; // Reset position for continuous loop
                }
            }
            content.style.transform = `translateX(${currentScroll}px)`;
            setTimeout(scroll, interval);
        }
        scroll();
    }

    document.querySelectorAll(".itfe-image-ticker-container").forEach(container => {
        // Extract direction from the class
        const direction = container.classList.contains('itfe-image-ticker-left-to-right') ? 'left-to-right' : 'right-to-left';
        const speed = parseInt(container.getAttribute('data-speed'), 10) || 20; // Default to 20 if speed is not set
        const imageWidth = parseInt(container.getAttribute('data-image-width'), 10) || 100; // Default to 100px if image width is not set
        const spacing = parseInt(container.getAttribute('data-spacing'), 10) || 30; // Default to 30px if spacing is not set
        setupMarquee(container, direction, speed, imageWidth, spacing);
    });
});