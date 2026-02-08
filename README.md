# Schwab API PHP Client

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-blue)](https://www.php.net/)

A comprehensive PHP library for interacting with the Charles Schwab Trader API. This library provides a clean, object-oriented interface to access account information, manage orders, retrieve transactions, and more.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Authentication Flow](#authentication-flow)
- [API Reference](#api-reference)
  - [Account Operations](#account-operations)
  - [Order Management](#order-management)
  - [Transaction History](#transaction-history)
  - [Market Data](#market-data)
  - [User Preferences](#user-preferences)
- [Error Handling](#error-handling)
- [Security](#security)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Features

- ✅ **Complete API Coverage** - All Schwab Trader API endpoints implemented
- ✅ **Type-Safe** - Full PHP 8.2+ type declarations
- ✅ **Modern PHP** - Uses traits, strict types, and best practices
- ✅ **OAuth 2.0** - Built-in authentication and token management
- ✅ **Comprehensive Error Handling** - Detailed exceptions with context
- ✅ **Well Documented** - Extensive PHPDoc comments throughout
- ✅ **Secure** - Credential masking and secure defaults
- ✅ **Tested** - PHPUnit test suite included

### Supported Operations

- **Accounts**: Get account numbers, balances, positions
- **Orders**: Place, modify, cancel, preview orders
- **Transactions**: Retrieve transaction history
- **Market Data**: Quotes, price history, option chains, market hours
- **Trading**: Equity and options trading support
- **User Preferences**: Access user settings

## Requirements

- PHP 8.2 or higher
- Composer
- A Schwab Developer account with API credentials

## Installation

Install via Composer:

```bash
composer require michaeldrennen/schwab-api-php
```

## Configuration

### 1. Get API Credentials

1. Visit [Schwab Developer Portal](https://developer.schwab.com/)
2. Create an application and note your:
   - **API Key** (Client ID)
   - **API Secret** (Client Secret)
   - **Callback URL** (Redirect URI)

### 2. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` or `phpunit.xml` with your credentials:

```env
SCHWAB_API_KEY=your_api_key_here
SCHWAB_API_SECRET=your_api_secret_here
SCHWAB_CALLBACK_URI=https://your-domain.com/callback
```

## Quick Start

```php
<?php

require 'vendor/autoload.php';

use MichaelDrennen\SchwabAPI\SchwabAPI;

// Initialize the API client
$api = new SchwabAPI(
    apiKey: $_ENV['SCHWAB_API_KEY'],
    apiSecret: $_ENV['SCHWAB_API_SECRET'],
    apiCallbackUrl: $_ENV['SCHWAB_CALLBACK_URI'],
    authenticationCode: $authCode,  // From OAuth callback
    accessToken: null,
    refreshToken: null,
    debug: false
);

// Get account numbers
$accountNumbers = $api->accountNumbers();

// Get account details with positions
$accounts = $api->accounts(positions: true);

// Place a buy order
$responseCode = $api->placeBuyOrder(
    hashValueOfAccountNumber: $encryptedAccountNumber,
    symbol: 'AAPL',
    quantity: 10
);
```

## Authentication Flow

The Schwab API uses OAuth 2.0 authentication. Here's the complete flow:

### Step 1: Redirect User to Authorization

```php
$api = new SchwabAPI(
    apiKey: $apiKey,
    apiSecret: $apiSecret,
    apiCallbackUrl: $callbackUrl,
    authenticationCode: 'temporary', // Placeholder
    debug: false
);

// Redirect user to this URL
$authorizeUrl = $api->getAuthorizeUrl();
header("Location: $authorizeUrl");
```

### Step 2: Handle Callback

After user authorization, Schwab redirects to your callback URL with a `code` parameter:

```php
// Your callback URL: https://your-domain.com/callback?code=ABC123...&session=XYZ789...

$code = $_GET['code'];
$session = $_GET['session'];

$api = new SchwabAPI(
    apiKey: $apiKey,
    apiSecret: $apiSecret,
    apiCallbackUrl: $callbackUrl,
    authenticationCode: $code,
    debug: false
);

// Exchange code for tokens
$api->requestToken();

// Store these tokens securely
$accessToken = $api->getAccessToken();
$refreshToken = $api->getRefreshToken();
$expiresIn = $api->getExpiresIn(); // Seconds until expiration (typically 1800)
```

### Step 3: Use Access Token

```php
$api = new SchwabAPI(
    apiKey: $apiKey,
    apiSecret: $apiSecret,
    apiCallbackUrl: $callbackUrl,
    authenticationCode: null,
    accessToken: $storedAccessToken,
    refreshToken: $storedRefreshToken,
    debug: false
);

// Make API calls
$accounts = $api->accounts();
```

### Step 4: Refresh Token

Access tokens expire after 30 minutes. Refresh them before expiration:

```php
// Refresh the access token
$api->requestToken(doRefreshToken: true);

// Get new tokens
$newAccessToken = $api->getAccessToken();
$newRefreshToken = $api->getRefreshToken();
```

## API Reference

### Account Operations

#### Get Account Numbers

Returns a list of account numbers with their encrypted hash values:

```php
$accountNumbers = $api->accountNumbers();
// Returns: [['accountNumber' => '12345', 'hashValue' => 'ABC123...']]
```

#### Get All Accounts

```php
// Get accounts with balances only
$accounts = $api->accounts();

// Get accounts with positions
$accounts = $api->accounts(positions: true);
```

#### Get Specific Account

```php
$account = $api->account(
    hashValueOfAccountNumber: $encryptedAccountHash,
    fields: ['positions']
);
```

#### Get Account by Number (Helper)

```php
// Get indexed by account number
$accountsByNumber = $api->accountsByNumber();

// Get specific account by number
$account = $api->accountByNumber(accountNumber: 12345678);
```

#### Get Long Equity Positions

```php
$positions = $api->getLongEquityPositions(
    hashValueOfAccountNumber: $encryptedAccountHash
);
```

### Order Management

#### Get All Orders

```php
use Carbon\Carbon;

// Get all orders across all accounts
$orders = $api->orders(
    fromTime: Carbon::now()->subDays(30),
    toTime: Carbon::now(),
    maxResults: 100,
    status: 'FILLED'
);
```

Valid status values:
- `AWAITING_PARENT_ORDER`, `AWAITING_CONDITION`, `AWAITING_STOP_CONDITION`
- `AWAITING_MANUAL_REVIEW`, `ACCEPTED`, `AWAITING_UR_OUT`
- `PENDING_ACTIVATION`, `QUEUED`, `WORKING`, `REJECTED`
- `PENDING_CANCEL`, `CANCELED`, `PENDING_REPLACE`, `REPLACED`
- `FILLED`, `EXPIRED`, `NEW`, `AWAITING_RELEASE_TIME`
- `PENDING_ACKNOWLEDGEMENT`, `PENDING_RECALL`, `UNKNOWN`

#### Get Orders for Specific Account

```php
$orders = $api->ordersForAccount(
    hashValueOfAccountNumber: $encryptedAccountHash,
    maxResults: 50,
    fromTime: Carbon::now()->subWeek(),
    toTime: Carbon::now(),
    status: 'WORKING'
);
```

#### Get Specific Order

```php
$order = $api->orderForAccount(
    hashValueOfAccountNumber: $encryptedAccountHash,
    orderId: 12345
);
```

#### Place Buy Order

```php
$responseCode = $api->placeBuyOrder(
    hashValueOfAccountNumber: $encryptedAccountHash,
    symbol: 'AAPL',
    quantity: 10
);
// Returns: 201 (Created)
```

#### Place Sell Order

```php
$responseCode = $api->placeSellOrder(
    hashValueOfAccountNumber: $encryptedAccountHash,
    symbol: 'AAPL',
    quantity: 10
);
```

#### Preview Order

Preview an order before placing it to see projected impact:

```php
$preview = $api->previewOrder(
    hashValueOfAccountNumber: $encryptedAccountHash,
    orderPayload: $orderJson
);
// Returns projected balances, commissions, etc.
```

#### Replace Order

Modify an existing order:

```php
$responseCode = $api->replaceOrder(
    hashValueOfAccountNumber: $encryptedAccountHash,
    orderId: 12345,
    orderPayload: $newOrderJson
);
// Returns: 200 (OK)
```

#### Cancel Order

```php
$responseCode = $api->cancelOrder(
    hashValueOfAccountNumber: $encryptedAccountHash,
    orderId: 12345
);
// Returns: 200 (OK)
```

### Transaction History

#### Get Transactions

```php
use Carbon\Carbon;

$transactions = $api->transactions(
    hashValueOfAccountNumber: $encryptedAccountHash,
    startDate: Carbon::now()->subMonths(3),
    endDate: Carbon::now(),
    symbol: 'AAPL',  // Optional: filter by symbol
    types: 'TRADE'   // Optional: filter by transaction type
);
```

#### Get Specific Transaction

```php
$transaction = $api->transaction(
    hashValueOfAccountNumber: $encryptedAccountHash,
    transactionId: 'TXN123456'
);
```

### Market Data

The library also includes market data endpoints (already implemented in your codebase):

- **Quotes**: Real-time and delayed quotes
- **Price History**: Historical price data
- **Option Chains**: Option chain data
- **Option Expiration**: Option expiration dates
- **Market Hours**: Trading hours for different markets
- **Movers**: Top market movers
- **Instruments**: Security instrument lookup

### User Preferences

```php
$preferences = $api->userPreference();
```

## Error Handling

The library provides detailed error handling with custom exceptions:

```php
use MichaelDrennen\SchwabAPI\Exceptions\RequestException;

try {
    $accounts = $api->accounts();
} catch (RequestException $e) {
    // Get error message
    echo $e->getMessage();

    // Get HTTP status code
    echo $e->getCode();

    // Get response body
    echo $e->getResponseBody();

    // Get previous exception
    $previous = $e->getPrevious();
} catch (\InvalidArgumentException $e) {
    // Handle validation errors
    echo "Invalid parameters: " . $e->getMessage();
} catch (\RuntimeException $e) {
    // Handle JSON decode errors
    echo "JSON error: " . $e->getMessage();
}
```

### Common Exceptions

- `RequestException` - API request failures (4xx, 5xx errors)
- `InvalidArgumentException` - Invalid constructor parameters
- `RuntimeException` - JSON decode failures
- `\Exception` - General errors (token refresh, validation)

## Security

### Credential Protection

The library implements several security features:

1. **Credential Masking**: The `__toString()` method masks sensitive data
2. **No Hardcoded Secrets**: Use environment variables
3. **Secure Defaults**: Debug mode disabled by default

### Best Practices

```php
// ❌ DON'T: Log the entire API object
error_log(print_r($api, true)); // Credentials would be masked but still risky

// ✅ DO: Log only what you need
error_log("Account count: " . count($accounts));

// ✅ DO: Store tokens securely
// Use encrypted database storage or secure key management
$encryptedToken = encryptToken($api->getAccessToken());
storeSecurely($encryptedToken);

// ✅ DO: Use HTTPS for callbacks
$callbackUrl = 'https://your-domain.com/callback'; // Never HTTP
```

## Testing

Run the test suite:

```bash
# Copy and configure phpunit.xml with your test credentials
cp phpunit.xml.dist phpunit.xml

# Run tests
./vendor/bin/phpunit
```

**Note**: Some tests require valid API credentials and may interact with the live API.

## Architecture

The library uses a trait-based architecture for organization:

```
SchwabAPI (Main Class)
├── RequestTrait (HTTP methods)
├── AccountRequests (Account operations)
├── OrderRequests (Order management)
├── TransactionRequests (Transaction history)
├── QuotesRequests (Quote data)
├── PriceHistoryRequests (Historical data)
├── OptionChainsRequests (Options data)
├── OptionExpirationChainRequests (Option expiration)
├── MoversRequests (Market movers)
├── MarketHoursRequests (Trading hours)
├── InstrumentsRequests (Instrument lookup)
└── UserPreferenceRequests (User preferences)
```

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Changelog

### Version 2.0.0 (Latest)

- ✅ Complete API coverage for Schwab Trader API
- ✅ Added transaction management
- ✅ Added order preview, replace, and cancel operations
- ✅ Upgraded to Laravel 11 support
- ✅ Enhanced error handling with specific exceptions
- ✅ Added comprehensive parameter validation
- ✅ Improved security with credential masking
- ✅ Modernized code (removed alternative syntax)
- ✅ Added support for PUT and DELETE HTTP methods

## Resources

- [Schwab Developer Portal](https://developer.schwab.com/)
- [Schwab Trader API Documentation](https://developer.schwab.com/products/trader-api--individual)
- [OAuth 2.0 Specification](https://oauth.net/2/)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Disclaimer

This library is not affiliated with, officially maintained by, or endorsed by Charles Schwab & Co., Inc. Use at your own risk. Always test with small amounts in a paper trading account before using in production.

Trading stocks and options involves risk. This library is provided "as-is" without warranty of any kind.

## Support

For issues, questions, or contributions:

- **Issues**: [GitHub Issues](https://github.com/michaeldrennen/schwab-api-php/issues)
- **Email**: michaeldrennen74@gmail.com

---

**Made with ❤️ by Michael Drennen**
