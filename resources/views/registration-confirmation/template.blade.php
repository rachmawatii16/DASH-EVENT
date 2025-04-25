<!DOCTYPE html>
<html>
<head>
    <title>Event Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
        }
        table {
            width: 100%;
            margin-bottom: 30px;
        }
        td {
            padding: 8px;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Event Registration Confirmation</h2>
    </div>

    <div class="content">
        <table>
            <tr>
                <td class="label">Event Name</td>
                <td>: {{ $event->title }}</td>
            </tr>
            <tr>
                <td class="label">Participant</td>
                <td>: {{ $applicant->name }}</td>
            </tr>
            <tr>
                <td class="label">Event Date</td>
                <td>: {{ $event->date }}</td>
            </tr>
            <tr>
                <td class="label">Time</td>
                <td>: {{ $event->time }}</td>
            </tr>
            <tr>
                <td class="label">Location</td>
                <td>: {{ $event->location }}</td>
            </tr>
            <tr>
                <td class="label">Registration Date</td>
                <td>: {{ now()->format('d F Y') }}</td>
            </tr>
        </table>

        <p>This is to formally confirm that the above-named participant has successfully registered for the event</p>
    </div>

</body>
</html> 