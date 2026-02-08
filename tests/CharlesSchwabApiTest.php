<?php

namespace MichaelDrennen\SchwabAPI\Tests;

use GuzzleHttp\Client;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

/**
 * Basic smoke tests for the Schwab API
 * For comprehensive integration tests, see tests/Integration/CharlesSchwabApiIntegrationTest.php
 */
class CharlesSchwabApiTest extends TestCase {

    /**
     * @test
     */
    public function testConstructorWithValidParametersCreatesInstance(): void {
        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $this->assertInstanceOf(SchwabAPI::class, $api);
    }

    /**
     * @test
     */
    public function testGetAuthorizeUrlReturnsCorrectFormat(): void {
        $api = new SchwabAPI(
            apiKey: 'my_key',
            apiSecret: 'my_secret',
            apiCallbackUrl: 'https://example.com/callback',
            authenticationCode: 'test_code'
        );

        $url = $api->getAuthorizeUrl();

        $this->assertStringStartsWith('https://api.schwabapi.com/v1/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=my_key', $url);
        $this->assertStringContainsString('redirect_uri=https://example.com/callback', $url);
    }

    /**
     * @test
     */
    public function testMaskSensitiveDataInToString(): void {
        $api = new SchwabAPI(
            apiKey: 'very_secret_api_key_1234567890',
            apiSecret: 'very_secret_api_secret_0987654321',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $stringOutput = (string) $api;

        // Should contain masked indicators
        $this->assertStringContainsString('****', $stringOutput);

        // Should NOT contain full secrets
        $this->assertStringNotContainsString('very_secret_api_key_1234567890', $stringOutput);
        $this->assertStringNotContainsString('very_secret_api_secret_0987654321', $stringOutput);
    }
}
