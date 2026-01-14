# FOSSBilling KeyHelp Server Manager

![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![FOSSBilling](https://img.shields.io/badge/FOSSBilling-0.7.2+-green)
![License](https://img.shields.io/badge/License-GPLv3-blue)

> [!TIP]
> ### 🛠️ Need Support or Installation Help?
> We provide **free** official support and professional services for this module.
>
> * **❓ General Support:** View our available support channels at [modgecko.com/support](https://modgecko.com/support).
> * **🐛 Bug Reports:** Found a glitch? Please open a public [Issue on GitHub](https://github.com/ModGecko/fossbilling-keyhelp-manager/issues).
> * **🔐 Security Issues:** Do **NOT** report security flaws on GitHub. Please submit a private ticket via our [Support Center](https://modgecko.com/support).

**Maintained by the [ModGecko Team](https://modgecko.com)**

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

## Beta Notice

⚠️ **The module should be considered BETA** - the module works as of this release date, but issues may occur.  
Please report any bugs or unexpected behavior via the **GitHub Issues** page so they can be addressed in future updates.

---

## Requirements

- **PHP >= 8.2**
- **FOSSBilling v0.7.2**
- KeyHelp API access with a valid API key

---

## Installation

> 📖 **Need a step-by-step walkthrough?** > For a more detailed installation guide, please visit our knowledgebase article:  
> [https://help.modgecko.com/en/knowledgebase/article/keyhelp-fossmanager](https://help.modgecko.com/en/knowledgebase/article/keyhelp-fossmanager)

1. Clone or download this repository into your FOSSBilling `/library/Server/Manager` directory.
2. Configure a new server in FOSSBilling using this module.
3. Provide your **KeyHelp hostname/IP** and **API key** in the server settings.
4. Make sure your products have the **custom parameter** `id_hosting_plan` with the correct plan ID from KeyHelp. (Do not use the name of the Template it has to be a number, you can find it in the url if you edit the Template in KeyHelp)

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

- **[ModGecko](https://modgecko.com)** – Project Maintainer
- **[@Pyryxe (Hadrian)](https://github.com/Pyryxe)** – Lead Developer
