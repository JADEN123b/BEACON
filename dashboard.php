<?php
// Dashboard page with authentication
require_once 'includes/auth_check.php';
require_once 'config/mysqli_db.php';
require_once 'config/config.php';

// Get configuration
$config = require 'config/config.php';

// Get real incident statistics from database
$database = new Database();
$conn = $database->getConnection();

// Initialize variables
$total_incidents = 0;
$resolved_incidents = 0;
$active_incidents = 0;
$community_alerts = 0;
$recent_incidents = [];

try {
    // Get total incidents
    $result = $conn->query("SELECT COUNT(*) as total FROM incidents");
    if ($result) {
        $row = $result->fetch_assoc();
        $total_incidents = $row['total'];
    }
    
    // Get resolved incidents
    $result = $conn->query("SELECT COUNT(*) as total FROM incidents WHERE status = 'resolved'");
    if ($result) {
        $row = $result->fetch_assoc();
        $resolved_incidents = $row['total'];
    }
    
    // Get active incidents (reported + verified)
    $result = $conn->query("SELECT COUNT(*) as total FROM incidents WHERE status IN ('reported', 'verified')");
    if ($result) {
        $row = $result->fetch_assoc();
        $active_incidents = $row['total'];
    }
    
    // Get community alerts (verified incidents in last 24 hours)
    $result = $conn->query("SELECT COUNT(*) as total FROM incidents WHERE status = 'verified' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    if ($result) {
        $row = $result->fetch_assoc();
        $community_alerts = $row['total'];
    }
    
    // Get recent incidents with user information
    $result = $conn->query("SELECT i.*, u.fullname FROM incidents i LEFT JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC LIMIT 5");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recent_incidents[] = $row;
        }
    }
    
} catch (Exception $e) {
    // Use sample data if database fails
    $total_incidents = 24;
    $resolved_incidents = 18;
    $active_incidents = 8;
    $community_alerts = 5;
    $recent_incidents = [
        ['title' => 'Road Accident', 'description' => 'Traffic collision at main intersection', 'incident_type' => 'Accident', 'status' => 'reported', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'fullname' => 'John Doe'],
        ['title' => 'Fire Incident', 'description' => 'Building fire reported downtown', 'incident_type' => 'Fire', 'status' => 'verified', 'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours')), 'fullname' => 'Jane Smith'],
        ['title' => 'Theft Report', 'description' => 'Bicycle stolen from parking area', 'incident_type' => 'Theft', 'status' => 'resolved', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'fullname' => 'Mike Johnson']
    ];
}
?>
<!DOCTYPE html>y
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BEACON</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <img src="assets/logo.png" alt="BEACON Logo">
                </div>
                <ul class="nav-menu">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="#incidents">Incidents</a></li>
                    <li><a href="#alerts">Alerts</a></li>
                </ul>
                <button class="nav-btn" onclick="logout()">Logout</button>
            </div>
        </nav>
    </header>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="assets/logo.png" alt="BEACON Logo">
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#report"><i class="fas fa-plus-circle"></i> Report Incident</a></li>
                <li><a href="#incidents"><i class="fas fa-list"></i> My Incidents</a></li>
                <li><a href="#map"><i class="fas fa-map"></i> Incident Map</a></li>
                <li><a href="#alerts"><i class="fas fa-bell"></i> Alerts</a></li>
                <li><a href="#profile"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="#settings"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="welcome-message">
                    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
                    <p>Here's what's happening in your community today.</p>
                </div>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['fullname'], 0, 1)); ?></div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon incidents">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_incidents; ?></h3>
                        <p>Total Incidents</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon resolved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $resolved_incidents; ?></h3>
                        <p>Resolved</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $active_incidents; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon alerts">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $community_alerts; ?></h3>
                        <p>Active Alerts</p>
                    </div>
                </div>
            </div>

            <!-- Google Maps Container -->
            <div class="map-container">
                <h3>Live Incident Map</h3>
                <div class="map-controls">
                    <button class="action-btn" onclick="getCurrentLocation()">
                        <i class="fas fa-location-arrow"></i> My Location
                    </button>
                </div>
                <div id="incident-map"></div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <button class="action-btn" onclick="reportIncident()">
                        <i class="fas fa-plus"></i> Report New Incident
                    </button>
                    <button class="action-btn secondary" onclick="viewMap()">
                        <i class="fas fa-map-marked-alt"></i> View Full Map
                    </button>
                    <button class="action-btn secondary" onclick="viewAlerts()">
                        <i class="fas fa-bell"></i> View Alerts
                    </button>
                </div>
            </div>

            <!-- Recent Incidents -->
            <div class="recent-incidents">
                <h3>Recent Incidents in Your Area</h3>
                <ul class="incident-list">
                    <?php foreach ($recent_incidents as $incident): ?>
                    <li class="incident-item">
                        <div class="incident-info">
                            <h4><?php echo htmlspecialchars($incident['title']); ?></h4>
                            <p>
                                Reported by <?php echo htmlspecialchars($incident['fullname']); ?> • 
                                <?php 
                                $time_ago = time() - strtotime($incident['created_at']);
                                if ($time_ago < 3600) {
                                    echo floor($time_ago / 60) . " minutes ago";
                                } elseif ($time_ago < 86400) {
                                    echo floor($time_ago / 3600) . " hours ago";
                                } else {
                                    echo floor($time_ago / 86400) . " days ago";
                                }
                                ?>
                            </p>
                        </div>
                        <span class="incident-status status-<?php echo $incident['status']; ?>">
                            <?php echo htmlspecialchars(ucfirst($incident['status'])); ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </main>
    </div>

    <!-- Google Maps API -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $config['google_maps_api_key']; ?>&callback=initMap&loading=async">
    </script>

    <script>
        // Global variables for map and markers
        let map;
        let userMarker;

        function logout() {
            if (confirm('Abeg no go, we still get beta features to test!')) {
                window.location.href = 'includes/logout.php';
            }
        }

        function reportIncident() {
            alert('Incident reporting feature coming soon!');
        }

        function viewMap() {
            alert('Full incident map feature coming soon!');
        }

        function viewAlerts() {
            alert('Alerts feature coming soon!');
        }

        // Get current user location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        // Center map on user location
                        map.setCenter(userLocation);
                        map.setZoom(15);
                        
                        // Add or update user marker
                        if (userMarker) {
                            userMarker.setPosition(userLocation);
                        } else {
                            const pinElement = new google.maps.marker.PinElement({
                                glyph: '●',
                                glyphColor: '#ffffff',
                                background: '#3b82f6',
                                borderColor: '#ffffff',
                                scale: 1.5
                            });
                            
                            userMarker = new google.maps.marker.AdvancedMarkerElement({
                                position: userLocation,
                                map: map,
                                title: 'Your Location',
                                content: pinElement.element
                            });
                        }
                        
                        // Show info window
                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="padding: 10px;">
                                    <h4 style="margin: 0 0 5px 0; color: #0d1b3e;">Your Location</h4>
                                    <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                        Lat: ${position.coords.latitude.toFixed(6)}<br>
                                        Lng: ${position.coords.longitude.toFixed(6)}
                                    </p>
                                </div>
                            `
                        });
                        infoWindow.open(map, userMarker);
                        
                        console.log('Location found:', userLocation);
                    },
                    function(error) {
                        let errorMessage = 'Unable to get your location.';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied. Please enable location access.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out.';
                                break;
                        }
                        alert(errorMessage);
                        console.error('Geolocation error:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        // Initialize Google Maps
        function initMap() {
            // Default center (Cameroon coordinates)
            const cameroon = { lat: 3.8480, lng: 11.5021 };
            
            map = new google.maps.Map(document.getElementById('incident-map'), {
                zoom: 13,
                center: cameroon,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "geometry.fill",
                        "stylers": [{"weight": "2.00"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#9c9c9c"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text",
                        "stylers": [{"visibility": "on"}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{"color": "#f2f2f2"}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{"saturation": -100}, {"lightness": 45}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#eeeeee"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#7b7b7b"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.stroke",
                        "stylers": [{"color": "#ffffff"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]
                    }
                ]
            });

            // Add sample incident markers
            const incidents = [
                { lat: 3.8480, lng: 11.5021, title: "Road Accident", status: "reported" },
                { lat: 3.8580, lng: 11.5121, title: "Fire Incident", status: "verified" },
                { lat: 3.8380, lng: 11.4921, title: "Theft Report", status: "resolved" }
            ];

            // Define custom marker colors based on status
            const getMarkerColor = (status) => {
                return status === 'resolved' ? '#10b981' : 
                       status === 'verified' ? '#3b82f6' : '#f59e0b';
            };

            incidents.forEach(incident => {
                // Create AdvancedMarkerElement with PinElement for custom colored markers
                const pinElement = new google.maps.marker.PinElement({
                    glyph: '',
                    glyphColor: '#ffffff',
                    background: getMarkerColor(incident.status),
                    borderColor: '#ffffff',
                    scale: 1.5
                });

                const marker = new google.maps.marker.AdvancedMarkerElement({
                    position: { lat: incident.lat, lng: incident.lng },
                    map: map,
                    title: incident.title,
                    content: pinElement.element
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px;">
                            <h4 style="margin: 0 0 5px 0; color: #0d1b3e;">${incident.title}</h4>
                            <p style="margin: 0; color: #6b7280; font-size: 14px;">Status: ${incident.status}</p>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            });
        }

        // Auto-refresh dashboard every 30 seconds
        setInterval(function() {
            console.log('Refreshing dashboard data...');
            // You can add AJAX call here to refresh data
        }, 30000);
    </script>
</body>
</html>
