<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
        .google-signin-container {
            margin: 2rem 0;
            display: flex;
            justify-content: center;
        }
        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 8px;
            display: none;
            font-size: 0.9rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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
        
        <div id="alert" class="alert"></div>
        
        <div class="google-signin-container">
            <div id="g_id_onload"
                 data-client_id="{{ config('google-onetap.client_id') }}"
                 data-context="{{ config('google-onetap.one_tap.context', 'signin') }}"
                 data-ux_mode="{{ config('google-onetap.one_tap.ux_mode', 'popup') }}"
                 data-callback="handleCredentialResponse"
                 data-auto_prompt="{{ config('google-onetap.one_tap.auto_prompt', 'false') ? 'true' : 'false' }}">
            </div>
            
            <div class="g_id_signin"
                 data-type="{{ config('google-onetap.one_tap.button.type', 'standard') }}"
                 data-shape="{{ config('google-onetap.one_tap.button.shape', 'rectangular') }}"
                 data-theme="{{ config('google-onetap.one_tap.button.theme', 'outline') }}"
                 data-text="{{ config('google-onetap.one_tap.button.text', 'signin_with') }}"
                 data-size="{{ config('google-onetap.one_tap.button.size', 'large') }}"
                 data-logo_alignment="{{ config('google-onetap.one_tap.button.logo_alignment', 'left') }}">
            </div>
        </div>
        
        <p class="footer-text">
            By signing in, you agree to our Terms of Service and Privacy Policy.
        </p>
        
        <div class="powered-by">
            Powered by Google One Tap Login Package
        </div>
    </div>

    <script>
        // Set up CSRF token for all AJAX requests
        window.Laravel = {
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Handle the credential response from Google One Tap
        function handleCredentialResponse(response) {
            const alertDiv = document.getElementById('alert');
            
            // Show loading state
            showAlert('Processing login...', 'info');
            
            // Send the credential to your backend
            fetch('{{ route(config("google-onetap.route_names.callback", "google-onetap.callback")) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Login successful! Redirecting...', 'success');
                    // Redirect to dashboard or intended page
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ config("google-onetap.redirects.after_login", "/dashboard") }}';
                    }, 1000);
                } else {
                    showAlert(data.error || 'Login failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'error');
            });
        }

        function showAlert(message, type) {
            const alertDiv = document.getElementById('alert');
            alertDiv.textContent = message;
            alertDiv.className = 'alert alert-' + type;
            alertDiv.style.display = 'block';
            
            if (type === 'success') {
                setTimeout(() => {
                    alertDiv.style.display = 'none';
                }, 3000);
            }
        }

        // Initialize Google One Tap when the page loads
        window.onload = function() {
            console.log('Google One Tap initialized');
        };
    </script>
</body>
</html>
