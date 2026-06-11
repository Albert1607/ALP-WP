
window.addEventListener('scroll', function() {
    var navbar = document.querySelector('.navbar');
    if (navbar) {
        if (window.scrollY > 30) {
            navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    }
});


document.querySelectorAll('a[href^="#"]').forEach(function(link) {
    link.addEventListener('click', function(e) {
        var href = this.getAttribute('href');
        if (href && href !== '#' && href.startsWith('#')) {
            try {
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            } catch (err) {
                console.error("Invalid selector: " + href);
            }
        }
    });
});

// Global toggle function for sidebar
window.lfToggleMenu = function() {
    console.log("lfToggleMenu clicked");
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('lf-sidebar-overlay');
    console.log("sidebar:", sidebar);
    console.log("overlay:", overlay);
    if (sidebar && overlay) {
        const computedStyle = window.getComputedStyle(sidebar);
        console.log("--- Sidebar Computed Styles ---");
        console.log("position:", computedStyle.position);
        console.log("left:", computedStyle.left);
        console.log("width:", computedStyle.width);
        console.log("height:", computedStyle.height);
        console.log("display:", computedStyle.display);
        console.log("z-index:", computedStyle.zIndex);
        console.log("visibility:", computedStyle.visibility);
        console.log("opacity:", computedStyle.opacity);
        console.log("--------------------------------");

        const isOpen = sidebar.style.left === "0px";
        console.log("isOpen:", isOpen, "current left:", sidebar.style.left);
        if (isOpen) {
            sidebar.style.left = "-240px";
            overlay.style.display = "none";
            console.log("Set left to -240px, display to none");
        } else {
            // Force display: block to override any display: none rules in browser cache
            sidebar.style.display = "block";
            // Force a browser reflow to make the left sliding transition work
            sidebar.offsetHeight;
            sidebar.style.left = "0px";
            overlay.style.display = "block";
            console.log("Set display to block, left to 0px, overlay display to block");
        }
    } else {
        console.warn("Sidebar or overlay not found!");
    }
};

// Auto-inject Mobile responsive toggles and overlay for Dashboard sidebar navigation
function initSidebar() {
    const dashboardEl = document.querySelector('.dashboard');
    if (dashboardEl) {
        const mainContent = document.querySelector('.main-content');
        const sidebar = document.querySelector('.sidebar');
        
        if (mainContent && sidebar && !document.getElementById('lf-mobile-menu-btn')) {
            // Create mobile header bar
            const mobileHeader = document.createElement('div');
            mobileHeader.className = 'mobile-header';
            mobileHeader.style.cssText = "display: flex; align-items: center; justify-content: space-between; background: #1f2937 !important; color: #fff !important; padding: 12px 16px; margin-bottom: 16px; border-radius: 8px;";
            mobileHeader.innerHTML = `
                <button class="menu-toggle" onclick="window.lfToggleMenu()" style="background: #16a34a; color: #fff; border: none; padding: 8px 14px; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 13px;"> Menu</button>
                <span class="mobile-logo" style="font-weight: bold; font-size: 15px; color: #fff; display: flex; align-items: center; gap: 6px;"> LibraFlow</span>
            `;
            
            // Prepend mobile header to main-content
            mainContent.insertBefore(mobileHeader, mainContent.firstChild);
            
            // Create overlay element
            if (!document.getElementById('lf-sidebar-overlay')) {
                const overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                overlay.id = 'lf-sidebar-overlay';
                overlay.setAttribute('onclick', 'window.lfToggleMenu()');
                dashboardEl.appendChild(overlay);
            }
        }
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSidebar);
} else {
    initSidebar();
}

