<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$config = $root . '/db/config.php';
$lock = __DIR__ . '/.installed';

function escape_html(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function install_error(string $message): never { throw new RuntimeException($message); }

if (file_exists($config) || file_exists($lock)) {
    http_response_code(403);
    exit('BuscoPhylo is already installed. Keep db/config.php and install/.installed private.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $host = trim((string) ($_POST['host'] ?? ''));
        $database = trim((string) ($_POST['database'] ?? ''));
        $dbUser = trim((string) ($_POST['db_user'] ?? ''));
        $dbPass = (string) ($_POST['db_pass'] ?? '');
        $email = trim((string) ($_POST['email'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if ($host === '' || $database === '' || $dbUser === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) install_error('Complete all database fields and enter a valid administrator email.');
        if (!preg_match('/^[A-Za-z0-9_]+$/', $database)) install_error('Database name may contain only letters, numbers, and underscores.');
        if (!preg_match('/^[A-Za-z0-9_.-]{3,64}$/', $username)) install_error('Administrator username must be 3–64 letters, numbers, dots, dashes, or underscores.');
        if (strlen($password) < 12) install_error('Administrator password must be at least 12 characters.');

        $pdo = new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $tables = [
            "CREATE TABLE IF NOT EXISTS tbl_users (id VARCHAR(64) PRIMARY KEY, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(254) NOT NULL UNIQUE, username VARCHAR(64) NOT NULL UNIQUE, status TINYINT NOT NULL DEFAULT 1, security VARCHAR(255) NOT NULL, reg_date VARCHAR(32) NOT NULL, image VARCHAR(255) NOT NULL DEFAULT 'user.svg', role VARCHAR(32) NOT NULL DEFAULT 'user', badge VARCHAR(64) NOT NULL DEFAULT '') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_sessions (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user VARCHAR(64) NOT NULL, sessi_id VARCHAR(128) NOT NULL UNIQUE, ip_address VARCHAR(64) NOT NULL, INDEX(user)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_web_info (app_name VARCHAR(128) NOT NULL, app_icon VARCHAR(255) NOT NULL, app_logo VARCHAR(255) NOT NULL, app_email VARCHAR(254) NOT NULL, app_phone VARCHAR(64) NOT NULL, app_timezone VARCHAR(64) NOT NULL, app_top_text TEXT NOT NULL, app_keywords TEXT NOT NULL, app_description TEXT NOT NULL, app_auth_background VARCHAR(255) NOT NULL, app_currency VARCHAR(16) NOT NULL, app_iso VARCHAR(8) NOT NULL, app_guest TINYINT NOT NULL DEFAULT 1, app_item_detail TINYINT NOT NULL DEFAULT 1, app_custom_script TEXT NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_smtp (smtp_server VARCHAR(255) NOT NULL DEFAULT '', smtp_username VARCHAR(255) NOT NULL DEFAULT '', smtp_password TEXT NOT NULL, smtp_encryption VARCHAR(32) NOT NULL DEFAULT 'tls', smtp_port SMALLINT NOT NULL DEFAULT 587) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_maintenance (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, email VARCHAR(254) NOT NULL UNIQUE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_about (title VARCHAR(255) NOT NULL DEFAULT 'About BuscoPhylo', content LONGTEXT NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_news (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, ping VARCHAR(32) NOT NULL DEFAULT '') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS tbl_items (c_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, item_id VARCHAR(64) NOT NULL UNIQUE, work_dir VARCHAR(255) NOT NULL, input_data LONGTEXT NOT NULL, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, user_id VARCHAR(64) NOT NULL DEFAULT '', lineage VARCHAR(128) NOT NULL DEFAULT '', run_time VARCHAR(32) NOT NULL DEFAULT 'Waiting list', mode VARCHAR(32) NOT NULL DEFAULT 'genome', outgroup VARCHAR(255) NOT NULL DEFAULT '', email VARCHAR(254) NOT NULL DEFAULT '', rates TINYINT NOT NULL DEFAULT 0, INDEX(run_time)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];
        foreach ($tables as $sql) $pdo->exec($sql);
        if ((int) $pdo->query('SELECT COUNT(*) FROM tbl_web_info')->fetchColumn() === 0) {
            $pdo->prepare('INSERT INTO tbl_web_info VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')->execute(['BuscoPhylo', 'logo.png', 'logo.png', $email, '', 'UTC', '', 'BUSCO phylogenomics', 'BUSCO-based phylogenomic analysis', '', '', '', 1, 1, '']);
        }
        $pdo->prepare('INSERT INTO tbl_users (id,first_name,last_name,email,username,security,reg_date,image,role,badge) VALUES (?,?,?,?,?,?,?,?,?,?)')->execute([bin2hex(random_bytes(16)), 'Site', 'Administrator', $email, $username, password_hash($password, PASSWORD_DEFAULT), date('d-m-Y'), 'user.svg', 'admin', '']);
        $contents = "<?php\n" . "define('DBHost', " . var_export($host, true) . ");\n" . "define('DBUser', " . var_export($dbUser, true) . ");\n" . "define('DBPass', " . var_export($dbPass, true) . ");\n" . "define('DBName', " . var_export($database, true) . ");\n" . "define('DBCharset', 'utf8mb4');\n" . "define('DBCollation', 'utf8mb4_unicode_ci');\n" . "define('DBPrefix', '');\n";
        if (file_put_contents($config, $contents, LOCK_EX) === false || file_put_contents($lock, date(DATE_ATOM), LOCK_EX) === false) install_error('Database initialized but PHP cannot write db/config.php or install/.installed. Check directory permissions and retry.');
        header('Location: ../login');
        exit;
    } catch (Throwable $exception) { $error = $exception->getMessage(); }
}
?><!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Install BuscoPhylo</title><style>body{font:16px system-ui;max-width:680px;margin:3rem auto;padding:0 1rem}label{display:block;margin-top:1rem;font-weight:600}input{box-sizing:border-box;width:100%;padding:.65rem;margin-top:.25rem}.error{background:#fee2e2;padding:1rem;color:#991b1b}button{margin-top:1.5rem;padding:.75rem 1rem}</style><h1>Install BuscoPhylo</h1><p>This one-time installer creates database tables, local configuration, and the administrator account.</p><?php if ($error): ?><p class="error"><?= escape_html($error) ?></p><?php endif; ?><form method="post"><label>Database host<input name="host" value="<?= escape_html($_POST['host'] ?? '127.0.0.1') ?>" required></label><label>Database name<input name="database" required></label><label>Database user<input name="db_user" required></label><label>Database password<input name="db_pass" type="password"></label><label>Administrator email<input name="email" type="email" required></label><label>Administrator username<input name="username" required></label><label>Administrator password (12+ characters)<input name="password" type="password" minlength="12" required></label><button>Install BuscoPhylo</button></form></html>
