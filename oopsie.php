<?php
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! - MKWTAS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-container {
            text-align: center;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        h1 {
            color: #e74c3c;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        .mario-reference {
            font-style: italic;
            color: #777;
            margin-top: 2rem;
        }

        .home-link {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .home-link:hover {
            background-color: #27ae60;
        }

        img {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <img src="assets/img/logo.png" alt="logo" />
        <h1>Oops! Something went wrong</h1>
        <p>Looks like we hit a banana peel! Our technical Lakitu is working on getting things back on track.</p>
        <p>Please try again later.</p>
        <p class="mario-reference">"It's-a me, Error-io!"</p>
    </div>
</body>

</html>