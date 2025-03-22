# AP\PublicID

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A small PHP library for generating short, public-friendly integer IDs from internal numeric IDs.

Designed to hide system scale and client-specific patterns while keeping IDs compact and reversible.

> ❗️Not for security purposes. 
> 
> If clients know you're using `UnsafePublicID`, they might infer ID ranges or volumes.
> Use `SafePublicID` for stronger obfuscation with a mask. 
> 
> It's still reversible and not secure, but the effort required to reverse-engineer it is significantly greater.

---

## Installation

```bash
composer require ap-lib/public-id
```

## Features

- Encode numeric IDs into non-sequential public integers
- Keep public-facing IDs short and opaque
- Optional masking layer to prevent reverse-engineering
- Fully reversible with `decode()`

## Requirements

- PHP 8.3 or higher

## Getting started

### Basic usage with `UnsafePublicID`

```php
use AP\PublicID\UnsafePublicID;

$digits_divider = 2;
$original_id = 1000;

$public_id = UnsafePublicID::encode(
    int: $original_id,
    digits_divider: $digits_divider
);

$decoded_original_id = UnsafePublicID::decode(
    public: $public_id,
    digits_divider: $digits_divider
);

// $decoded_original_id equal $original_id

$public_id_2 = UnsafePublicID::encode(
    int: $original_id,
    digits_divider: $digits_divider
);

// $public_id equal $public_id_2

```

---

### Safer encoding with `SafePublicID`

```php
use AP\PublicID\SafePublicID;

$secret_mask_from_envs = 902734092;

$safePublicId = new SafePublicID(
    mask: $secret_mask_from_envs
);

$digits_divider = 2;
$original_id = 1000;

$public_id = $safePublicId->encode(
    int: $original_id,
    digits_divider: $digits_divider
);

$decoded_original_id = $safePublicId->decode(
    int: $public_id,
    digits_divider: $digits_divider
);

// $decoded_original_id equal $original_id

$public_id_2 = $safePublicId->encode(
    int: $original_id,
    digits_divider: $digits_divider
);


// $public_id equal $public_id_2

```

---

## License

This library is open-sourced software licensed under the [MIT license](LICENSE).

