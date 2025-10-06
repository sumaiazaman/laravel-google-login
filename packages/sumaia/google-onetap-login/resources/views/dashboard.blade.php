<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .header h1 {
            margin: 0;
            color: #1a202c;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        .user-name {
            font-weight: 500;
            color: #2d3748;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }
        .welcome-card h2 {
            margin: 0 0 1rem 0;
            color: #1a202c;
            font-size: 1.75rem;
            font-weight: 600;
        }
        .welcome-card p {
            margin: 0;
            color: #4a5568;
            font-size: 1.1rem;
        }
        .logout-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .user-details {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .user-details h3 {
            margin: 0 0 1.5rem 0;
            color: #1a202c;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f7fafc;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }
        .detail-value {
            color: #4a5568;
            font-size: 0.95rem;
        }
        .badge {
            background: #48bb78;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge.unverified {
            background: #ed8936;
        }
        .package-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 2rem;
            text-align: center;
        }
        .package-info h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }
        .package-info p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
        <div class="user-info">
            @if(auth()->user()->avatar ?? auth()->user()->{config('google-onetap.user.fields.avatar', 'avatar')})
                <img src="{{ auth()->user()->avatar ?? auth()->user()->{config('google-onetap.user.fields.avatar', 'avatar')} }}" alt="Avatar" class="avatar">
            @endif
            <span class="user-name">{{ auth()->user()->name ?? auth()->user()->{config('google-onetap.user.fields.name', 'name')} }}</span>
            <form action="{{ route(config('google-onetap.route_names.logout', 'google-onetap.logout')) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome, {{ auth()->user()->name ?? auth()->user()->{config('google-onetap.user.fields.name', 'name')} }}! 🎉</h2>
            <p>You have successfully logged in using Google One Tap authentication.</p>
        </div>

        <div class="user-details">
            <h3>Your Profile Information</h3>
            
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ auth()->user()->name ?? auth()->user()->{config('google-onetap.user.fields.name', 'name')} }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ auth()->user()->email ?? auth()->user()->{config('google-onetap.user.fields.email', 'email')} }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Google ID:</span>
                <span class="detail-value">{{ (auth()->user()->google_id ?? auth()->user()->{config('google-onetap.user.fields.google_id', 'google_id')}) ?? 'Not linked' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email Verified:</span>
                <span class="detail-value">
                    @if(auth()->user()->email_verified_at ?? auth()->user()->{config('google-onetap.user.fields.email_verified_at', 'email_verified_at')})
                        <span class="badge">Verified</span>
                    @else
                        <span class="badge unverified">Not Verified</span>
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Member Since:</span>
                <span class="detail-value">{{ auth()->user()->created_at->format('F j, Y') }}</span>
            </div>
        </div>

        <div class="package-info">
            <h4>🚀 Powered by Google One Tap Login Package</h4>
            <p>This dashboard is provided by the startise/google-onetap-login package. You can customize this view by publishing the package views.</p>
        </div>
    </div>
</body>
</html>
