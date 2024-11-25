    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="dashboard.php" class="p-2"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="eligibility.php" class="p-2"><i class="fas fa-chart-bar"></i> Eligibility</a></li>
            <li><a href="profile.php" class="p-2"><i class="fas fa-user"></i> Profile</a></li>
            <li role="separator" class="dropdown-divider"></li>
            <li><a href="logout.php" class="p-2"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="toggle-btn" id="toggleBtn">
            <i class="fas fa-bars"></i>
        </div>
        <div class="profile dropdown">
            <img src="https://via.placeholder.com/40" alt="Profile">
            <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" style="text-decoration: none;"> Admin </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>