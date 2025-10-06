{{-- Google One Tap Button Component --}}
<div class="google-onetap-wrapper">
    {{-- Load Google Sign-In JavaScript --}}
    @if($clientId)
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    @endif

    {{-- Google One Tap Configuration --}}
    @if($clientId)
    <div id="g_id_onload"
         data-client_id="{{ $clientId }}"
         data-context="{{ $config['context'] ?? 'signin' }}"
         data-ux_mode="{{ $config['ux_mode'] ?? 'popup' }}"
         data-callback="handleGoogleOneTapResponse"
         data-auto_prompt="{{ $autoPrompt ? 'true' : 'false' }}"
         data-cancel_on_tap_outside="{{ ($config['cancel_on_tap_outside'] ?? false) ? 'true' : 'false' }}"
         data-moment_callback="onGoogleOneTapMoment">
    </div>
    @else
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 1rem; border-radius: 4px; margin: 1rem 0;">
        <strong>⚠️ Google Client ID not configured</strong>
        <p style="margin: 0.5rem 0 0;">Add <code>GOOGLE_CLIENT_ID</code> to your .env file to enable Google One Tap authentication.</p>
    </div>
    @endif

    {{-- Fallback Sign-In Button --}}
    @if($clientId)
    <div class="g_id_signin"
         data-type="{{ $buttonType }}"
         data-shape="{{ $config['button']['shape'] ?? 'rectangular' }}"
         data-theme="{{ $buttonTheme }}"
         data-text="{{ $config['button']['text'] ?? 'signin_with' }}"
         data-size="{{ $buttonSize }}"
         data-logo_alignment="{{ $config['button']['logo_alignment'] ?? 'left' }}">
    </div>
    @endif

    {{-- Status Messages --}}
    <div id="google-onetap-status" class="google-onetap-status" style="display: none;">
        <div id="google-onetap-message"></div>
    </div>

    @if($clientId)
    <script>
        // CSRF Token for Laravel
        window.Laravel = window.Laravel || {};
        window.Laravel.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        /**
         * Handle the credential response from Google One Tap
         */
        function handleGoogleOneTapResponse(response) {
            showGoogleOneTapStatus('Processing login...', 'info');
            
            fetch('{{ $callbackRoute }}', {
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
                    showGoogleOneTapStatus('Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                } else {
                    showGoogleOneTapStatus(data.error || 'Login failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Google One Tap Error:', error);
                showGoogleOneTapStatus('An error occurred. Please try again.', 'error');
            });
        }

        /**
         * Handle Google One Tap moments (display, dismiss, etc.)
         */
        function onGoogleOneTapMoment(moment) {
            console.log('Google One Tap moment:', moment);
            
            switch (moment.getMomentType()) {
                case 'display':
                    console.log('Google One Tap modal displayed');
                    hideGoogleOneTapStatus();
                    break;
                case 'skipped':
                    console.log('Google One Tap skipped:', moment.getSkippedReason());
                    showGoogleOneTapStatus('Google One Tap was skipped. Please use the sign-in button.', 'info');
                    break;
                case 'dismissed':
                    console.log('Google One Tap dismissed:', moment.getDismissedReason());
                    showGoogleOneTapStatus('You can sign in using the button below.', 'info');
                    break;
            }
        }

        /**
         * Show status message
         */
        function showGoogleOneTapStatus(message, type = 'info') {
            const statusDiv = document.getElementById('google-onetap-status');
            const messageDiv = document.getElementById('google-onetap-message');
            
            messageDiv.textContent = message;
            statusDiv.className = `google-onetap-status google-onetap-${type}`;
            statusDiv.style.display = 'block';
            
            if (type === 'success') {
                setTimeout(() => {
                    hideGoogleOneTapStatus();
                }, 3000);
            }
        }

        /**
         * Hide status message
         */
        function hideGoogleOneTapStatus() {
            const statusDiv = document.getElementById('google-onetap-status');
            statusDiv.style.display = 'none';
        }
    </script>
    @endif

    <style>
        .google-onetap-wrapper {
            display: inline-block;
        }
        
        .google-onetap-status {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        
        .google-onetap-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .google-onetap-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        
        .google-onetap-error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }
    </style>
</div>
