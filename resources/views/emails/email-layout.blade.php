<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notifikasi Sistem')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 25px;
            color: #333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }
        .content-block {
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box ul {
            list-style: none;
            padding: 0;
        }
        .info-box li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-box li:last-child {
            border-bottom: none;
        }
        .info-box strong {
            color: #667eea;
            display: inline-block;
            width: 150px;
        }
        .alert-box {
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .alert-danger {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        .alert-success {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }
        .alert-warning {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            color: #e65100;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .status-approved {
            background-color: #4caf50;
            color: white;
        }
        .status-rejected {
            background-color: #f44336;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-table th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .data-table tr:hover {
            background-color: #f8f9fa;
        }
        .highlight-text {
            color: #667eea;
            font-weight: 600;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>@yield('header-title', 'Sistem Inventory')</h1>
            <p>@yield('header-subtitle', 'Notifikasi Sistem')</p>
        </div>
        
        <div class="email-body">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
            <p>Mohon tidak membalas email ini.</p>
            <p style="margin-top: 10px; color: #999;">© {{ date('Y') }} Sistem Inventory. All rights reserved.</p>
        </div>
    </div>
</body>
</html>