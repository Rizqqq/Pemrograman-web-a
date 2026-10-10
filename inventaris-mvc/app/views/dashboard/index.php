<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($title) ?> - <?= e(APP_NAME) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1e60a1;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .kotak {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        h1 { color: #2c3e50; }
        p { color: #555; }
        .status {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="kotak">
        <h1><?= e($title) ?></h1>
        <p><?= e($pesan) ?></p>
        <span class="status">✓ Step 1-3 Selesai (Tanpa Database)</span>
    </div>
</body>
</html>