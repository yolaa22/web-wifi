<?php
// cek-database.php
require_once 'config/database.php';

echo "<h2>Checking Database Connection...</h2>";

// Cek koneksi
if ($pdo) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Cek tabel admin_users
echo "<h3>Checking admin_users table...</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin_users'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "✅ admin_users table exists<br>";
        
        // Cek data admin
        $stmt = $pdo->query("SELECT * FROM admin_users");
        $users = $stmt->fetchAll();
        
        echo "Found " . count($users) . " admin user(s)<br><br>";
        
        foreach ($users as $user) {
            echo "ID: " . $user['id'] . "<br>";
            echo "Username: " . $user['username'] . "<br>";
            echo "Full Name: " . $user['full_name'] . "<br>";
            echo "Email: " . $user['email'] . "<br>";
            echo "Password Hash: " . substr($user['password'], 0, 20) . "...<br>";
            echo "Created: " . $user['created_at'] . "<br>";
            echo "Last Login: " . $user['last_login'] . "<br>";
            echo "<hr>";
        }
    } else {
        echo "❌ admin_users table does not exist<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<br><h3>Testing password verification...</h3>";
echo "Test password: 'admin123'<br>";

$testPassword = 'admin123';
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

echo "Hashed version: " . $hashedPassword . "<br>";
echo "Verification test: " . (password_verify($testPassword, $hashedPassword) ? '✅ WORKS' : '❌ FAILS') . "<br>";

// Check hash info
echo "<br>Hash info: <br>";
print_r(password_get_info($hashedPassword));
?>

<h2>Reset Admin Password</h2>
<form method="POST">
    <input type="hidden" name="reset" value="1">
    <button type="submit">Reset Password to 'admin123'</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $hashed = password_hash('admin123', PASSWORD_DEFAULT);
    
    try {
        // Cek apakah admin sudah ada
        $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if ($admin) {
            // Update existing admin
            $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
            $stmt->execute([$hashed]);
            echo "<div style='color: green;'>✅ Password updated successfully!</div>";
        } else {
            // Insert new admin
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name, email) VALUES ('admin', ?, 'Administrator', 'admin@wifinet.co.id')");
            $stmt->execute([$hashed]);
            echo "<div style='color: green;'>✅ Admin user created successfully!</div>";
        }
        
        echo "<div>New Hash: " . $hashed . "</div>";
        
    } catch (Exception $e) {
        echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
    }
}
?>