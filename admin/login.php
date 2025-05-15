<?php
// Initialize session
session_start();

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Redirect to admin dashboard
    header("Location: index.php");
    exit;
}

// Include database connection
require_once '../includes/config.php';

// Initialize variables
$error = '';
$login = '';

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    
    // Validate input
    if (empty($login) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Prepare SQL query to check user credentials
        $stmt = $conn->prepare("SELECT login, password, access_level FROM accounts WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Verify credentials
            if ($password === $row['password']) { // Simple comparison as per existing schema
                // Check if user has admin access
                if ($row['access_level'] == 1) {
                    // Set session variables
                    $_SESSION['admin_username'] = $row['login'];
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_access_level'] = $row['access_level'];
                    
                    // Check if there's a redirect URL stored in session
                    if(isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                        $redirect_url = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header("Location: " . $redirect_url);
                    } else {
                        // Redirect to admin dashboard
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    $error = "You don't have administrator privileges.";
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - L1J Remastered Database</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="/l1jdb-new/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Login Section -->
    <section class="hero-section">
        <div class="hero-background">
            <picture>
                <source srcset="../assets/img/placeholders/hero-background.png" type="image/png">
                <source srcset="../assets/img/placeholders/hero-background.webp" type="image/webp"> 
                <img src="../images/hero-background.gif" alt="L1J Remastered Game World" class="hero-bg-img">
            </picture>
        </div>
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header text-center">
                            <h4 class="mb-0">Admin Login</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="mb-3">
                                    <label for="login" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-accent">Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/l1jdb-new/index.php" class="text-light">Back to Main Site</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>