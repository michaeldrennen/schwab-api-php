<?php

namespace MichaelDrennen\SchwabAPI\Tests\Helpers;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Browser;
use HeadlessChromium\Page;

/**
 * Automates the Schwab OAuth flow to get a fresh authorization code
 */
class OAuthAutomation {

    private string $chromePath;
    private string $apiKey;
    private string $callbackUrl;
    private string $username;
    private string $password;

    public function __construct(
        string $chromePath,
        string $apiKey,
        string $callbackUrl,
        string $username,
        string $password
    ) {
        $this->chromePath = $chromePath;
        $this->apiKey = $apiKey;
        $this->callbackUrl = $callbackUrl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Automate the OAuth flow and extract the authorization code
     *
     * @return array ['code' => string, 'session' => string]
     * @throws \Exception
     */
    public function getAuthorizationCode(): array {
        // Build the authorization URL
        $authorizeUrl = 'https://api.schwabapi.com/v1/oauth/authorize'
            . '?client_id=' . urlencode($this->apiKey)
            . '&redirect_uri=' . urlencode($this->callbackUrl);

        $browserFactory = new BrowserFactory($this->chromePath);

        // Start headless Chrome
        $browser = $browserFactory->createBrowser([
            'headless' => true, // Set to false for debugging
            'noSandbox' => true,
            'windowSize' => [1920, 1080],
        ]);

        try {
            $page = $browser->createPage();
            $page->navigate($authorizeUrl)->waitForNavigation();

            // Wait for the login page to load
            sleep(2);

            // Fill in username
            $page->evaluate("document.querySelector('input[name=\"loginId\"]').value = '{$this->username}';");

            // Fill in password
            $page->evaluate("document.querySelector('input[name=\"password\"]').value = '{$this->password}';");

            // Submit the form
            $page->evaluate("document.querySelector('button[type=\"submit\"]').click();");

            // Wait for redirect to callback URL
            sleep(3);

            // Get the current URL which should contain the code
            $currentUrl = $page->evaluate('window.location.href')->getReturnValue();

            // Parse the callback URL to extract code and session
            $parsedUrl = parse_url($currentUrl);
            if (!isset($parsedUrl['query'])) {
                throw new \Exception('Failed to get authorization code. Current URL: ' . $currentUrl);
            }

            parse_str($parsedUrl['query'], $queryParams);

            if (!isset($queryParams['code'])) {
                throw new \Exception('Authorization code not found in callback URL');
            }

            return [
                'code' => $queryParams['code'],
                'session' => $queryParams['session'] ?? '',
            ];

        } finally {
            $browser->close();
        }
    }

    /**
     * Simple method to check if automation is available
     *
     * @return bool
     */
    public static function isAvailable(string $chromePath): bool {
        return file_exists($chromePath) && is_executable($chromePath);
    }
}
