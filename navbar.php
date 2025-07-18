<div style="background-color: #0c0c0c; color: white; display: flex; align-items: center; justify-content: space-between; padding: 10px 20px;">
    <!-- Left side: Logo and navigation links -->
    <div style="display: flex; align-items: center;gap: 40px; flex-grow: 1;">
        <div style="color: cyan; font-size: 22px; font-weight: bold; margin: 0 40px 0 0;">
            <span style="color: #00f2ff;">●</span>Interview Prep
        </div>
        <!-- <div style="display: flex; align-items: center; gap: 40px; margin-left: 40px;"> -->
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="question_generation.php" class="nav-link">Question Generation</a>
        <a href="speech_practice.php" class="nav-link">Speech Recognition</a>
        <a href="resume_index.php" class="nav-link">Resume Analysis</a>
    </div>
    <!-- Right side: Only Profile link -->
    <div>
        <a href="profile.php" class="nav-link">Profile</a>
    </div>
</div>
<style>
    .nav-link {
        color: white;
        margin-right: 20px;
        text-decoration: none;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        transition: background-color 0.3s, color 0.3s;
    }
    .nav-link:hover,
    .nav-link.active {
        background: #00bfff;
        color: black;
    }
    .nav-icon-btn {
        font-size: 24px;
        text-decoration: none;
        color: cyan;
        margin-right: 20px;
        transition: color 0.3s;
    }
    .nav-icon-btn:hover {
        color: #00bfff;
    }
    .logout {
        font-weight: bold;
        margin-left: 20px;
    }
</style>
