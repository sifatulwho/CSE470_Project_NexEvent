<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket - {{ $registration->event->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .ticket-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .ticket-content {
            padding: 30px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .section-content {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .section-subtitle {
            font-size: 14px;
            color: #666;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .attendee-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .attendee-info .section-content {
            font-size: 16px;
            font-weight: 500;
        }

        .ticket-id-box {
            background: #f0f4ff;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .ticket-id-box code {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .qr-code {
            text-align: center;
            margin: 30px 0;
        }

        .qr-code img {
            max-width: 200px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .important-notice {
            background: #fffacd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #333;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .ticket-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>🎫 Digital Event Ticket</h1>
            <p>{{ $registration->event->title }}</p>
        </div>

        <div class="ticket-content">
            <!-- Event Information -->
            <div class="grid">
                <div class="section">
                    <div class="section-title">Event Date</div>
                    <div class="section-content">{{ $registration->event->start_date->format('M d, Y') }}</div>
                    <div class="section-subtitle">{{ $registration->event->start_date->format('H:i') }} - {{ $registration->event->end_date->format('H:i') }}</div>
                </div>
                <div class="section">
                    <div class="section-title">Location</div>
                    <div class="section-content">{{ $registration->event->location }}</div>
                </div>
            </div>

            <!-- Attendee Information -->
            <div class="attendee-info">
                <div class="section-title">Attendee Information</div>
                <div class="section-content">{{ $registration->attendee->name }}</div>
                <div class="section-subtitle">{{ $registration->attendee->email }}</div>
            </div>

            <!-- Ticket ID -->
            <div class="ticket-id-box">
                <div class="section-title">Unique Ticket ID</div>
                <code>{{ $ticket->ticket_id }}</code>
            </div>

            <!-- QR Code -->
            @if($ticket->qr_code)
                <div class="qr-code">
                    <div class="section-title">QR Code</div>
                    <img src="{{ $ticket->qr_code }}" alt="QR Code">
                </div>
            @endif

            <!-- Registration Details -->
            <div class="grid">
                <div class="section">
                    <div class="section-title">Registration Date</div>
                    <div class="section-content">{{ $registration->registered_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="section">
                    <div class="section-title">Ticket Status</div>
                    <div class="section-content">{{ $ticket->is_used ? '✓ Used' : '✓ Valid' }}</div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="important-notice">
                <strong>⚠️ Important:</strong> Please save or print this ticket. You will need to present this ticket at the event entrance. Show the QR code or Ticket ID to the venue staff.
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
                <p>Please keep this ticket safe and do not share it with others.</p>
            </div>
        </div>
    </div>
</body>
</html>
