<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .certificate { padding: 50px; border: 5px solid #EEE; }
        h1 { font-size: 24px; }
        p { font-size: 18px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This is to certify that <strong>{{ $applicantName }}</strong> has successfully completed the event titled <strong>{{ $title }}</strong> on {{ $date }}.</p>
    </div>
</body>
</html>
