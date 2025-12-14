[![deutsche Version](https://logos.oxidmodule.com/de2_xs.svg)](README.md)
[![english version](https://logos.oxidmodule.com/en2_xs.svg)](README.en.md)

# OxLogIQ Sentry Provider

## Übersicht
Hierbei handelt es sich um den Provider für den Loggingdienst Sentry zur Integration in den Logger **OxLogIQ** 
für den OXID eShop.

Bitte beachten Sie auch die **OxLogIQ** Dokumentation.

## Features
- Übergabe der Logeinträge nach [Sentry](https://sentry.io) (optional + einstellbar)
- Einfache Konfiguration über `config.inc.php`- oder Environment-Variablen

## Installation
1. über Composer installieren
   ```bash
   composer require d3/oxlogiq_sentry
   ```
2. Konfiguration setzen
3. TMP-Ordner des Shops leeren

## Konfiguration

Über diese Variablen lassen sich folgende Parameter anpassen:

| Einstellung               | Beschreibung                                                                                                              |
|---------------------------|---------------------------------------------------------------------------------------------------------------------------|
| oxlogiq_sentryDsn         | *optional:* Sentry Data Source Name                                                                                       |       

### Codebeispiel

```PHP
$this->oxlogiq_sentryDsn = 'https://yourkey.ingest.us.sentry.io/yourproject';
```

## Changelog

Siehe [CHANGELOG](CHANGELOG.md) für weitere Informationen.

## Beitragen

Wenn Sie eine Verbesserungsvorschlag haben, legen Sie einen Fork des Repositories an und erstellen Sie einen Pull 
Request. Alternativ können Sie einfach ein Issue erstellen. Fügen Sie das Projekt zu Ihren Favoriten hinzu. Vielen Dank.

- Erstellen Sie einen Fork des Projekts
- Erstellen Sie einen Feature Branch (git checkout -b feature/AmazingFeature)
- Fügen Sie Ihre Änderungen hinzu (git commit -m 'Add some AmazingFeature')
- Übertragen Sie den Branch (git push origin feature/AmazingFeature)
- Öffnen Sie einen Pull Request

## Softwarelizenz (OxLogIQ_Sentry) [MIT]
(14.12.2025)

```
Copyright (c) D3 Data Development (Inh. Thomas Dartsch)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```