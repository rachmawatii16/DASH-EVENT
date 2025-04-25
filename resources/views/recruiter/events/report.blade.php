<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Report - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .event-details {
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Event Report</h1>
        <h2>{{ $event->title }}</h2>
    </div>

    <div class="event-details section">
        <div class="section-title">Event Details</div>
        <table>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($event->status) }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $event->date }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $event->time }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $event->location }}</td>
            </tr>
            <tr>
                <th>Price</th>
                <td>Rp {{ number_format($event->price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Event Description</div>
        <p>{{ $event->description }}</p>
    </div>

    <div class="section">
        <div class="section-title">Approved Applicants</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->applicants as $index => $applicant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $applicant->name }}</td>
                    <td>{{ $applicant->email }}</td>
                    <td>{{ ucfirst($applicant->pivot->status_pembayaran) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="summary">
        <div class="section-title">Summary</div>
        <table>
            <tr>
                <th>Total Approved Applicants</th>
                <td>{{ $event->applicants->count() }} people</td>
            </tr>
            <tr>
                <th>Total Revenue from Approved Applicants</th>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html> 