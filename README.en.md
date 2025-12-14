[![deutsche Version](https://logos.oxidmodule.com/de2_xs.svg)](README.md)
[![english version](https://logos.oxidmodule.com/en2_xs.svg)](README.en.md)

# OxLogIQ

## Overview
This is the provider for the Sentry logging service for integration into the **OxLogIQ** logger for the 
OXID eShop.

Please also refer to the **OxLogIQ** documentation.

## Features
- Transfer log entries to [Sentry](https://sentry.io) (optional + adjustable)
- Simple configuration via `config.inc.php` or environment variables

## Installation
1. Install via Composer
    ```bash
   composer require d3/oxlogiq_sentry
   ```
2. Set configuration
3. Clear the shop's TMP folder

## Configuration

The following parameters can be adjusted using these variables:

| Setting                  | Description                                                                                                    |
|--------------------------|----------------------------------------------------------------------------------------------------------------|
| oxlogiq_sentryDsn        | *optional:* Sentry Data Source Name                                                                            |


Define these settings either as an environment variable or as a variable in the shop's `config.inc.php` file.

### Code example

```PHP
$this->oxlogiq_sentryDsn = 'https://yourkey.ingest.us.sentry.io/yourproject';
```

## Changelog

See [CHANGELOG](CHANGELOG.md) for further information.

## Contributing

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also 
simply open an issue. Don't forget to give the project a star! Thanks again!

- Fork the Project
- Create your Feature Branch (git checkout -b feature/AmazingFeature)
- Commit your Changes (git commit -m 'Add some AmazingFeature')
- Push to the Branch (git push origin feature/AmazingFeature)
- Open a Pull Request

## Licence of this software (OxLogIQ_Sentry) [MIT]
(2025-12-14)

```
Copyright (c) D3 Data Development (Inh. Thomas Dartsch)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```