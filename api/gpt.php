<?php
$api_url = 'http://196.200.148.23:3000/gpt';
$previous_chat = '';
$session_id = bin2hex(random_bytes(16));
if (isset($_POST['chat'])) {
    $prompt = $_POST['chat'];
    $previous_chat = $_POST['previous_chat'] . "\nUser: " . $prompt;
    if ($prompt != '') {
        $payload = json_encode([
            'prompt' => $prompt,
            'sessionId' => $session_id
        ]);
        $curl = curl_init($api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $api_response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($api_response);
        $response_output = $response->output;
        $instruction_pos = strpos($response_output, "### Instruction:");
        if ($instruction_pos !== false) {
            $response_output = substr($response_output, 0, $instruction_pos);
            $response_output = trim($response_output);
        }
        $previous_chat .= "\nGPT: " . $response_output;
    }
}
if (isset($_POST['clear'])) {
    $previous_chat = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chat with neuroGPT</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Chat with neuroGPT</h1>
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="chat" placeholder="Enter your message" />
            </div>
            <input type="hidden" name="previous_chat" value="<?php echo htmlspecialchars($previous_chat); ?>" />
            <button type="submit" class="btn btn-primary mr-2" name="send">Send</button>
            <button type="submit" class="btn btn-secondary" name="clear">Clear</button>
        </form>
        <pre class="mt-4">Session ID: <?php echo $session_id; ?></pre>
        <div class="card mt-4">
            <div class="card-body">
                <?php
                $chat_lines = explode("\n", $previous_chat);
                foreach ($chat_lines as $line) {
                    if (strpos($line, 'User:') !== false) {
                        $message = substr($line, strpos($line, 'User:') + 6);
                        echo '<div class="row"><div class="col-md-6 offset-md-6"><div class="card mb-2"><div class="card-body text-right">' . $message . '</div></div></div></div>';
                    } elseif (strpos($line, 'GPT:') !== false) {
                        $message = substr($line, strpos($line, 'GPT:') + 5);
                        echo '<div class="row"><div class="col-md-6"><div class="card mb-2"><div class="card-body">' . $message . '</div></div></div></div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php if (isset($response)) { ?>
            <pre class="mt-4">Time taken: <?php echo $response->timeTaken; ?>ms</pre>
        <?php } ?>
    </div>
</body>

</html>
