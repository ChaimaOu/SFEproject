<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$error = '';
$debug = ''; // For debugging

// Process login form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'youl_db';
    
    // Get form data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        try {
            // Connect to database
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            
            // Check connection
            if ($conn->connect_error) {
                throw new Exception("Database connection failed: " . $conn->connect_error);
            }
            
            // Escape special characters to prevent SQL injection
            $email = $conn->real_escape_string($email);
            
            // Check user credentials
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($sql);
            
            $debug = "Query executed: $sql";
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $debug .= " | User found: " . $user['email'];
                
                // Check password - direct comparison
                if ($password === $user['password']) {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['authenticated'] = true;
                    
                    $debug .= " | Password matched";
                    
                    // Redirect to dashboard
                    header("Location: ../dashboard/dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password";
                    $debug .= " | Password mismatch. Entered: $password, DB: " . $user['password'];
                }
            } else {
                $error = "Email not found";
                $debug .= " | No user found with email: $email";
            }
            
            $conn->close();
            
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
            $debug .= " | Exception: " . $e->getMessage();
        }
    }
}

// Clear session if not logged in properly
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>YOOL - Sign In</title>
<link rel="stylesheet" href="styles.css">
<style>
.debug-info {
    padding: 10px;
    margin-top: 10px;
    font-size: 12px;;
    color: red;
    white-space: pre-wrap;
    word-wrap: break-word;
}
.error-message {
  color: white;
  font-weight: bold;
  text-align: center;
}
</style>
<script src="https://accounts.google.com/gsi/client" async defer></script>

</head>
<body>
<main class="container">
  <!-- Decorative elements (visible on all screens) -->
  <div class="decorative-elements">
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
  </div>

  <!-- Display error message if any -->
  <?php if (!empty($error)): ?>
  <div class="message error-message">
    <p>Email Or Password Wrong</p>
    <?php if (!empty($debug)): ?>
    <div class="debug-info">

    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Mobile Layout -->
  <div class="mobile-container">
    <!-- Mobile Logo -->
    <div class="logo-mobile">
      <img src="yoolLogo.png" alt="YOOL Logo" class="logo">
      <!-- Added tagline for mobile -->
      <div class="tagline-mobile">
        <p>Your gateway to infinite possibilities</p>
      </div>
    </div>

    <!-- Mobile Form (will be hidden on desktop) -->
    <div class="form-container-mobile">
      <h1 class="title">SIGN IN</h1>
      <p class="subtitle">Sign in with email address</p>

      <form id="signInFormMobile" class="form" method="post" action="">
        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2" />
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
          </div>
          <input type="email" id="email-mobile" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <input type="password" id="password-mobile" name="password" placeholder="Password" required>
        </div>

        <button type="submit" id="submitButtonMobile" class="submit-button">Sign in</button>
      </form>

      <div class="social-login">
        <p class="social-text">Or continue with</p>
        <div class="social-buttons">
          <button class="social-button google">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#4285F4">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Google
          </button>
          <button class="social-button facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
            Facebook
          </button>
        </div>
      </div>

      <div class="terms">
        <p>By registering you with our <span class="terms-highlight">terms and conditions</span></p>
      </div>
    </div>
  </div>

  <!-- Desktop Layout (will be hidden on mobile) -->
  <div class="desktop-container">
    <!-- Desktop Logo -->
    <div class="logo-desktop">
      <img src="yoolLogo.png" alt="YOOL Logo" class="logo">
      <!-- Added tagline -->
      <div class="tagline">
        <h2>Welcome to YOOL</h2>
        <p>Your gateway to infinite possibilities</p>
      </div>
    </div>

    <!-- Desktop Form -->
    <div class="form-container">
      <h1 class="title">SIGN IN</h1>
      <p class="subtitle">Sign in with email address</p>

      <form id="signInForm" class="form" method="post" action="">
        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2" />
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
          </div>
          <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
          <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" id="submitButton" class="submit-button">Sign in</button>
      </form>

      <div class="social-login">
        <p class="social-text">Or continue with</p>
        <div class="social-buttons">
        <button class="social-button google" onclick="loginWithGoogle()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#4285F4">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Google
          </button>
          <button class="social-button facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
            Facebook
          </button>
        </div>
      </div>

      <div class="terms">
        <p>By registering you with our <span class="terms-highlight">terms and conditions</span></p>
      </div>
    </div>
  </div>
</main>
<script>
  // Initialise le client
  let client;

  window.onload = function () {
    client = google.accounts.id.initialize({
      client_id: 'TA_CLIENT_ID',
      callback: handleCredentialResponse
    });

    // Ne pas afficher le bouton auto, on le contrôle manuellement
  };

  // Fonction déclenchée après connexion réussie
  function handleCredentialResponse(response) {
    const jwt = response.credential;

    fetch('google-login-handler.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ credential: jwt })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        window.location.href = "../dashboard/dashboard.php";
      } else {
        alert("Erreur de connexion Google.");
      }
    })
    .catch(error => console.error('Erreur:', error));
  }

  // Fonction à appeler quand le bouton est cliqué
  function loginWithGoogle() {
    google.accounts.id.prompt(); // Affiche la fenêtre popup
  }
</script>


</body>
</html>
