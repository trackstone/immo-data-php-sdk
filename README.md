# Immo Data PHP SDK

[![Tests](https://github.com/trackstone/immo-data-php-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/trackstone/immo-data-php-sdk/actions/workflows/tests.yml)

A PHP SDK for the [Immo Data API](https://immo-data.fr) — French real estate data including property valuation, geocoding, geographic boundaries, and market prices.

## Requirements

- PHP 8.1+
- Guzzle 7.5+

## Installation

```bash
composer require trackstone/immo-data-php-sdk
```

## Quick Start

```php
use ImmoData\Sdk\ImmoDataClient;
use ImmoData\Sdk\Enums\RealtyType;
use ImmoData\Sdk\Requests\ValuationRequest;

$client = new ImmoDataClient(apiKey: 'your-api-key');

$request = new ValuationRequest(
    longitude: 2.3488,
    latitude: 48.8534,
    realtyType: RealtyType::Apartment,
    nbRooms: 3,
    livingArea: 65.0,
);

$result = $client->valuation()->estimate($request);

echo $result->mainValuation; // 485000.0
echo $result->confidence;    // 85
```

## Configuration

```php
use ImmoData\Sdk\ImmoDataClient;

// Default (production)
$client = new ImmoDataClient(apiKey: 'your-api-key');

// Custom base URL (staging, etc.)
$client = new ImmoDataClient(
    apiKey: 'your-api-key',
    baseUrl: 'https://staging-api.immo-data.fr',
);

// Custom HTTP client (for testing or custom transport)
$client = new ImmoDataClient(
    apiKey: 'your-api-key',
    httpClient: new YourCustomHttpClient(),
);
```

## Resources

The client exposes four resources:

| Resource | Method | Description |
|----------|--------|-------------|
| `valuation()` | `estimate()` | Property price estimation |
| `geocode()` | `search()` | Location search / autocomplete |
| `geo()` | `region()`, `department()`, `city()`, `district()`, `subdistrict()` | Geographic data and boundaries |
| `market()` | `priceHistory()`, `currentPrice()` | Market price data |

---

### Valuation

Estimate the price of a property based on its characteristics.

```php
use ImmoData\Sdk\Enums\{RealtyType, Condition, Dpe};
use ImmoData\Sdk\Requests\ValuationRequest;

$request = new ValuationRequest(
    longitude: 2.3488,
    latitude: 48.8534,
    realtyType: RealtyType::Apartment,
    nbRooms: 3,
    livingArea: 65.0,
    condition: Condition::Excellent,
    bathrooms: 1,
    constructionYear: 1990,
    dpe: Dpe::C,
    floor: 4,
    level: 6,
    elevator: true,
    cellar: true,
    parking: true,
);

$result = $client->valuation()->estimate($request);

$result->mainValuation;  // float — estimated price
$result->upperValuation; // float — upper bound
$result->lowerValuation; // float — lower bound
$result->confidence;     // int   — confidence score (0-100)
```

**Required parameters:** `longitude`, `latitude`, `realtyType`, `nbRooms`, `livingArea`

**Optional parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `condition` | `?Condition` | Property condition (-1 = to renovate, 0 = standard, 1 = excellent) |
| `landArea` | `?float` | Land area in m² |
| `bathrooms` | `?int` | Number of bathrooms |
| `constructionYear` | `?int` | Year of construction |
| `dpe` | `?Dpe` | Energy performance rating (A-G) |
| `level` | `?int` | Total number of floors in building |
| `floor` | `?int` | Floor the property is on |
| `pool` | `?bool` | Has swimming pool |
| `cellar` | `?bool` | Has cellar |
| `niceView` | `?bool` | Has nice view |
| `parking` | `?bool` | Has parking |
| `elevator` | `?bool` | Has elevator |
| `patio` | `?bool` | Has patio/terrace |

---

### Geocode

Search for locations by name. Returns structured geographic results with bounding boxes and center coordinates.

```php
use ImmoData\Sdk\Enums\GeoLevel;

// Simple search
$results = $client->geocode()->search('Paris');

foreach ($results as $result) {
    echo $result->label;          // "Paris, Ile-de-France"
    echo $result->geoLevel->value; // "city"
    echo $result->inseeCode;      // "75056"
    echo $result->center?->latitude;
    echo $result->center?->longitude;
}

// Filter by geographic level
$results = $client->geocode()->search(
    query: 'Lyon',
    geoLevels: [GeoLevel::City, GeoLevel::District],
    limit: 10,
);
```

**`GeocodeResult` properties:**

| Property | Type | Description |
|----------|------|-------------|
| `geoLevel` | `GeoLevel` | Geographic level (region, department, city, district, street, address) |
| `regionName` | `?string` | Region name |
| `regionCode` | `?string` | Region code |
| `departmentName` | `?string` | Department name |
| `departmentCode` | `?string` | Department code |
| `cityName` | `?string` | City name |
| `inseeCode` | `?string` | INSEE code |
| `postCode` | `string[]` | Post codes |
| `boundingBox` | `?BoundingBox` | Bounding box coordinates |
| `center` | `?Coordinates` | Center point (longitude, latitude) |
| `label` | `string` | Human-readable label |

---

### Geographic Data

Retrieve geographic entities with their GeoJSON boundaries.

```php
// Region
$region = $client->geo()->region('11');
echo $region->regionName;  // "Ile-de-France"
$region->boundaries;       // GeoJsonPolygon

// Department
$department = $client->geo()->department('75');
echo $department->departmentName; // "Paris"

// City (by INSEE code)
$city = $client->geo()->city('75056');
echo $city->cityName;       // "Paris"
echo $city->postCode;       // ["75001", "75002", ...]
echo $city->districtCodes;  // ["7501", "7502", ...]

// District
$district = $client->geo()->district('7514');
echo $district->districtName;     // "Observatoire"
echo $district->subdistrictCodes; // ["751401", "751402", ...]

// Subdistrict (IRIS)
$subdistrict = $client->geo()->subdistrict('751104');
echo $subdistrict->subdistrictName;
```

All geographic entities include a `boundaries` property containing a `GeoJsonPolygon` with `type` and `coordinates`.

---

### Market Data

Retrieve real estate market data at the department, city, or district level.

```php
use ImmoData\Sdk\Enums\{GeoLevel, RealtyType};

// Price history for Paris apartments
$history = $client->market()->priceHistory(
    code: '75056',
    geoLevel: GeoLevel::City,
    realtyType: RealtyType::Apartment,
    startDate: '2020-01-01',
    endDate: '2024-12-31',
);

echo $history->metric; // "sqm_price"
foreach ($history->data as $point) {
    echo "{$point->period}: {$point->value} EUR/m²";
}

// Current price for a department
$price = $client->market()->currentPrice(
    code: '75',
    geoLevel: GeoLevel::Department,
    realtyType: RealtyType::Apartment,
);

echo $price->value; // 10234.5 (EUR/m²)
```

> Market endpoints only support `GeoLevel::Department`, `GeoLevel::City`, and `GeoLevel::District`. Using other levels will throw an `InvalidArgumentException`.

---

## Enums

| Enum | Values |
|------|--------|
| `RealtyType` | `House`, `Apartment` |
| `GeoLevel` | `Region`, `Department`, `City`, `District`, `Street`, `Address` |
| `Dpe` | `A`, `B`, `C`, `D`, `E`, `F`, `G` |
| `Condition` | `ToRenovate` (-1), `Standard` (0), `Excellent` (1) |
| `MarketType` | `Sales` |
| `Interval` | `Monthly` |
| `Metric` | `SqmPrice` |

---

## Error Handling

All API errors throw typed exceptions extending `ImmoDataException`:

```php
use ImmoData\Sdk\Exceptions\{
    ImmoDataException,
    AuthenticationException,
    ValidationException,
    InsufficientCreditsException,
    ForbiddenException,
    NotFoundException,
    RateLimitException,
    ServerException,
};

try {
    $result = $client->valuation()->estimate($request);
} catch (ValidationException $e) {
    // 400 — invalid parameters
    $errors = $e->errors(); // array of validation errors
} catch (AuthenticationException $e) {
    // 401 — invalid or missing API key
} catch (InsufficientCreditsException $e) {
    // 402 — not enough credits
} catch (ForbiddenException $e) {
    // 403 — forbidden
} catch (NotFoundException $e) {
    // 404 — resource not found
} catch (RateLimitException $e) {
    // 429 — too many requests
} catch (ServerException $e) {
    // 500/502 — server error
} catch (ImmoDataException $e) {
    // catch-all for any API error
    $e->getCode();    // HTTP status code
    $e->errorBody;    // raw error response body
}
```

---

## Custom HTTP Client

You can provide your own HTTP client for testing or custom transport by implementing `HttpClientInterface`:

```php
use ImmoData\Sdk\HttpClient\HttpClientInterface;

class MockHttpClient implements HttpClientInterface
{
    public function get(string $path, array $query = []): array
    {
        return match ($path) {
            '/v1/valuation' => [
                'mainValuation' => 500000,
                'upperValuation' => 550000,
                'lowerValuation' => 450000,
                'confidence' => 90,
            ],
            default => throw new \RuntimeException("Unexpected path: {$path}"),
        };
    }
}

$client = new ImmoDataClient(
    apiKey: 'test',
    httpClient: new MockHttpClient(),
);
```

---

## Code Style

This package uses [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) with the PER Coding Style preset.

```bash
# Fix code style
composer cs

# Check without fixing
composer cs:check
```

## Testing

```bash
composer test
```

## License

MIT
