# FOSSBilling KeyHelp Server Manager

![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![FOSSBilling](https://img.shields.io/badge/FOSSBilling-0.7.2+-green)
![License](https://img.shields.io/badge/License-GPLv3-blue)

**Coded and maintained by [@Pyryxe (Hadrian)](https://github.com/Pyryxe)**

---

## Overview

This server manager integrates **FOSSBilling** with the **KeyHelp API (v2)**, enabling automated management of client hosting accounts.  
It supports:

- Account creation
- Suspension / Unsuspension
- Password changes
- Package changes
- Account cancellation
- Direct client login URL retrieval

---

## Requirements

- **PHP >= 8.2**
- **FOSSBilling v0.7.2**
- KeyHelp API access with a valid API key

---

## Installation

1. Clone or download this repository into your FOSSBilling `modules/Server` directory.
2. Configure a new server in FOSSBilling using this module.
3. Provide your **KeyHelp hostname/IP** and **API key** in the server settings.
4. Make sure your products have the **custom parameter** `id_hosting_plan` with the correct plan ID from KeyHelp.

---

## Usage

- Once installed, the module handles all account lifecycle actions automatically.
- The **direct login URL** can be retrieved for clients if the KeyHelp API supports it.

---

## Notes

- The product must define a **custom parameter** `id_hosting_plan`.
- API key is stored in the server **password field**.
- Username-based lookups are used for all account actions.

---

## Known Limitations

- Changing usernames, domains, or IP addresses is **not supported** by KeyHelp.
- Reseller login URLs are **not available**.
- Account synchronization is limited to **basic existence checks**.

---

## Changelog

- Initial release with full client lifecycle support.
- Added suspension / unsuspension handling.
- Added direct login URL retrieval.
- Aligned parameters with KeyHelp API v2.13.

---

## License

This project is licensed under the **[GPLv3 License](LICENSE)**.  
You may use, modify, and redistribute the module **as long as credit is preserved** and derivatives remain open source.

---

## Credits

- **[@Pyryxe (Hadrian)](https://github.com/Pyryxe)** – Author & Maintainer