<!doctype html>
<html>
<head>
    <title>Buscophylo Under Maintenance</title>
    <link rel="shortcut icon" type="image/png" href="https://buscophylo.inra.org.ma/icon/logo.png">
   <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            font-size: 18px;
        }
        .container {
            width: 800px;
            margin: 0 auto;
            padding: 50px;
        }
        .logo {
            margin-bottom: 30px;
            text-align: center;
        }
        .logo img {
            max-width: 50%;
            height: auto;
        }
        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        p {
            text-align: justify;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .email-form {
            margin-top: 30px;
            text-align: center;
        }
        .email-form input[type="email"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
        }
        .email-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            padding: 10px;
        }
        .error {
            color: red;
            background-color: #FFCCCC;
        }
        .success {
            color: green;
            background-color: #CCFFCC;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://buscophylo.inra.org.ma/img/logo-1.png" alt="Buscophylo Logo">
        </div>
        <h1>Buscophylo Website Under Maintenance</h1>
        <p>Dear Users,</p>
        <p>We apologize for any inconvenience caused. We are currently performing some maintenance on our website to improve your user experience. We will be back shortly.</p>
        <p>If you have any questions or concerns, please send an email to <a href="mailto:slimane.khayi@inra.ma">slimane.khayi@inra.ma</a>.</p>
        <p>Thank you for your patience.</p>
        <p>Sincerely,</p>
        <p>The Buscophylo Team</p>

        <div class="email-form">

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = [
                        "type" => "error",
                        "text" => "Invalid email address",
                    ];
                } else {
                    $servername = "localhost";
                    $username = "root";
                    // Set this outside source control when maintenance access is needed.
                    $password = getenv('BUSCOPHYLO_MAINTENANCE_PASSWORD') ?: '';
                    $dbname = "busco_laravel";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $stmt = $conn->prepare("SELECT * FROM tbl_maintenance WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $message = [
                            "type" => "error",
                            "text" => "Email already exists",
                        ];
                    } else {
                        $stmt = $conn->prepare("INSERT INTO tbl_maintenance (email) VALUES (?)");
                        $stmt->bind_param("s", $email);

                        if ($stmt->execute()) {
                            $message = [
                                "type" => "success",
                                "text" => "Email stored successfully",
                            ];
                        } else {
                            $message = [
                                "type" => "error",
                                "text" => "Error storing email: " . $stmt->error,
                            ];
                        }
                    }

                    $stmt->close();
                    $conn->close();
                }
            }
            ?>
            
            <?php if ($_SERVER["REQUEST_METHOD"] != "POST" || isset($message)): ?>
                <form method="POST">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <input type="submit" value="Notify Me">
                </form>
            <?php endif; ?>

            <?php if (isset($message)): ?>
                <p class="message <?php echo $message["type"]; ?>"><?php echo $message["text"]; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
