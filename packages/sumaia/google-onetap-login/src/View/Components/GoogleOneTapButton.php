<?php

namespace Sumaia\GoogleOneTapLogin\View\Components;

use Illuminate\View\Component;
use Sumaia\GoogleOneTapLogin\GoogleOneTap;

class GoogleOneTapButton extends Component
{
    public string $clientId;
    public array $config;
    public string $callbackRoute;
    public bool $autoPrompt;
    public string $buttonType;
    public string $buttonTheme;
    public string $buttonSize;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $clientId = null,
        bool $autoPrompt = true,
        string $buttonType = 'standard',
        string $buttonTheme = 'outline',
        string $buttonSize = 'large'
    ) {
        $googleOneTap = new GoogleOneTap();
        
        $this->clientId = $clientId ?? $googleOneTap->getClientId();
        $this->config = $googleOneTap->getOneTapConfig();
        $this->callbackRoute = route(config('google-onetap.route_names.callback', 'google-onetap.callback'));
        $this->autoPrompt = $autoPrompt;
        $this->buttonType = $buttonType;
        $this->buttonTheme = $buttonTheme;
        $this->buttonSize = $buttonSize;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('google-onetap::components.google-onetap-button');
    }
}
