// Dashboard JavaScript for BEACON platform

// Check if user is logged in
function checkAuth() {
    // This would normally check session/cookie
    // For now, we'll simulate user data
    const userData = {
        fullname: localStorage.getItem('fullname') || 'John Doe',
        email: localStorage.getItem('email') || 'john@example.com'
    };
    
    // Update user info in dashboard
    document.getElementById('userName').textContent = userData.fullname;
    document.getElementById('userEmail').textContent = userData.email;
    
    // Update avatar with first letter of name
    const firstLetter = userData.fullname.charAt(0).toUpperCase();
    document.getElementById('userAvatar').textContent = firstLetter;
}

// Load dashboard statistics
function loadStatistics() {
    // Simulate loading statistics (in real app, this would fetch from API)
    const stats = {
        totalIncidents: 24,
        resolvedIncidents: 18,
        pendingIncidents: 6,
        activeAlerts: 3
    };
    
    // Update statistics with animation
    animateNumber('totalIncidents', stats.totalIncidents);
    animateNumber('resolvedIncidents', stats.resolvedIncidents);
    animateNumber('pendingIncidents', stats.pendingIncidents);
    animateNumber('activeAlerts', stats.activeAlerts);
}

// Animate number counting
function animateNumber(elementId, targetNumber) {
    const element = document.getElementById(elementId);
    const duration = 1000; // 1 second
    const step = targetNumber / (duration / 16); // 60fps
    let currentNumber = 0;
    
    const timer = setInterval(() => {
        currentNumber += step;
        if (currentNumber >= targetNumber) {
            currentNumber = targetNumber;
            clearInterval(timer);
        }
        element.textContent = Math.floor(currentNumber);
    }, 16);
}

// Load recent incidents
function loadRecentIncidents() {
    // In real app, this would fetch from API
    const incidents = [
        {
            title: 'Road Accident',
            time: '2 hours ago',
            distance: '1.5 km away',
            status: 'reported'
        },
        {
            title: 'Fire Incident',
            time: '5 hours ago',
            distance: '3.2 km away',
            status: 'verified'
        },
        {
            title: 'Theft Report',
            time: '1 day ago',
            distance: '0.8 km away',
            status: 'resolved'
        }
    ];
    
    // Update incident list (already in HTML, but could be dynamic)
    updateIncidentList(incidents);
}

// Update incident list
function updateIncidentList(incidents) {
    const listElement = document.getElementById('recentIncidentsList');
    
    // Clear existing list
    listElement.innerHTML = '';
    
    // Add incidents to list
    incidents.forEach(incident => {
        const li = document.createElement('li');
        li.className = 'incident-item';
        
        li.innerHTML = `
            <div class="incident-info">
                <h4>${incident.title}</h4>
                <p>Reported ${incident.time} • ${incident.distance}</p>
            </div>
            <span class="incident-status status-${incident.status}">${incident.status.charAt(0).toUpperCase() + incident.status.slice(1)}</span>
        `;
        
        listElement.appendChild(li);
    });
}

// Quick action functions
function reportIncident() {
    // Redirect to incident reporting page
    window.location.href = 'report-incident.html';
}

function viewMap() {
    // Scroll to map section or redirect to map page
    window.location.href = '#map';
}

function viewAlerts() {
    // Redirect to alerts page
    window.location.href = '#alerts';
}

// Logout function
function logout() {
    // Clear session/local storage
    localStorage.removeItem('fullname');
    localStorage.removeItem('email');
    sessionStorage.clear();
    
    // Clear cookies
    document.cookie.split(";").forEach(function(c) { 
        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
    });
    
    // Redirect to login page
    window.location.href = 'login.html';
}

// Sidebar navigation
function initSidebar() {
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all links
            sidebarLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Handle navigation based on href
            const href = this.getAttribute('href');
            
            if (href === '#report') {
                reportIncident();
            } else if (href === '#map') {
                viewMap();
            } else if (href === '#alerts') {
                viewAlerts();
            } else if (href === '#profile') {
                // Show profile modal or redirect
                showProfileModal();
            } else if (href === '#settings') {
                // Show settings modal or redirect
                showSettingsModal();
            }
        });
    });
}

// Show profile modal (placeholder)
function showProfileModal() {
    alert('Profile page would open here');
}

// Show settings modal (placeholder)
function showSettingsModal() {
    alert('Settings page would open here');
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    checkAuth();
    loadStatistics();
    loadRecentIncidents();
    initSidebar();
    
    // Simulate real-time updates
    setInterval(() => {
        // In real app, this would fetch new data from server
        console.log('Checking for updates...');
    }, 30000); // Check every 30 seconds
});

// Handle window resize for responsive sidebar
window.addEventListener('resize', function() {
    if (window.innerWidth <= 768) {
        // Mobile view adjustments
        document.querySelector('.sidebar').style.position = 'relative';
        document.querySelector('.sidebar').style.width = '100%';
    } else {
        // Desktop view adjustments
        document.querySelector('.sidebar').style.position = 'sticky';
        document.querySelector('.sidebar').style.width = '250px';
    }
});

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    switch(type) {
        case 'success':
            notification.style.background = '#27ae60';
            break;
        case 'error':
            notification.style.background = '#e74c3c';
            break;
        case 'warning':
            notification.style.background = '#f39c12';
            break;
        default:
            notification.style.background = '#3498db';
    }
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Hide and remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
