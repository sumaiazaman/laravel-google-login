<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            margin: 2rem;
        }
        .logo {
            margin-bottom: 2rem;
        }
        .logo h1 {
            color: #333;
            font-size: 2rem;
            margin: 0;
            font-weight: 600;
        }
        .title {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.5;
        }

        .footer-text {
            margin-top: 2rem;
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        .powered-by {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.8rem;
        }


    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>{{ config('app.name', 'Laravel') }}</h1>
        </div>

        <h2 class="title">Welcome Back!</h2>
        <p class="subtitle">Sign in with your Google account to continue</p>

        <!-- Google One Tap Component -->
        <x-google-onetap-button />

        <p class="footer-text">
            By signing in, you agree to our Terms of Service and Privacy Policy.
        </p>

        <div class="powered-by">
            Powered by Google One Tap Login Package
        </div>
    </div>


</body>
</html>
