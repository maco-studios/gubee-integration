<a name="readme-top"></a>

<h1 align="left">Gubee Integration</h1>

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![Mozilla Public License Version 2.0][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<details>
  <summary>Summary</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
    <!--
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
    -->
  </ol>
</details>

## About The Project

The Gubee Integration for Magento 2 is designed to streamline the synchronization process between your Magento 2 store and Gubee's advanced integration services. This module facilitates seamless data exchange, empowering e-commerce platforms with enhanced capabilities for managing products, orders, inventory, and more.

### Built With

- Magento ^2.3
- PHP ^7.4
- Gubee API Services

## Getting Started

To get started with Gubee Integration, ensure you have a Magento 2 store set up and ready. This module requires Magento version 2.x and PHP 7.4 or later.

### Prerequisites:
- Magento 2.x installed
- PHP 7.4 or higher
- composer
- Gubee account and API credentials

### Installation

1. On your Magento 2 root directory, run the following command to install the module via composer:

```sh
composer require maco-studios/gubee-integration
```

2. Enable the module by running the following command:

```sh
php bin/magento module:enable Gubee_Integration
```

3. Run the setup upgrade command to install the module:

```sh
php bin/magento setup:upgrade
```
4. Compile the code:

```sh
php bin/magento setup:di:compile
```

5. Clear the cache:

```sh
php bin/magento cache:clean
```
<!-- 
## Usage

## Roadmap

## Contributing


## Acknowledgments

-->

## License

Distributed under the [Mozilla Public License Version 2.0](LICENSE.txt). See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/maco-studios/gubee-integration.svg?style=for-the-badge
[contributors-url]: https://github.com/maco-studios/gubee-integration/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/maco-studios/gubee-integration.svg?style=for-the-badge
[forks-url]: https://github.com/maco-studios/gubee-integration/network/members
[stars-shield]: https://img.shields.io/github/stars/maco-studios/gubee-integration.svg?style=for-the-badge
[stars-url]: https://github.com/maco-studios/gubee-integration/stargazers
[issues-shield]: https://img.shields.io/github/issues/maco-studios/gubee-integration.svg?style=for-the-badge
[issues-url]: https://github.com/maco-studios/gubee-integration/issues
[license-shield]: https://img.shields.io/github/license/maco-studios/gubee-integration.svg?style=for-the-badge
[license-url]: https://github.com/maco-studios/gubee-integration/blob/main/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/macoaure