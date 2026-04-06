# FOSSBilling KeyHelp Server Manager

![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![FOSSBilling](https://img.shields.io/badge/FOSSBilling-0.7.2+-green)
![License](https://img.shields.io/badge/License-GPLv3-blue)

> [!TIP]
> ### 🛠️ Need Support?
> * **🐛 Bug Reports:** Found a glitch? Open a public [Issue on GitHub](https://github.com/ModGecko/fossbilling-keyhelp-manager/issues).

---

## ⚠️ Maintenance Status

The module should be fully working unless reported otherwise. I don't actively use this module myself anymore, but I will happily maintain it if needed. 

If the module stops working or the API breaks, report it and I'll patch it. 
**Note:** I am only pushing security patches and updates to keep it functioning—I won't be adding any new features or quality-of-life updates. 

---

## Overview

This module hooks FOSSBilling up to the **KeyHelp API (v2)** to automate client hosting accounts. 

It handles:
- Account creation and cancellation
- Suspending and unsuspending users
- Upgrading/downgrading packages
- Password changes
- Grabbing direct client login URLs

---

## Requirements

- **PHP 8.2+**
- **FOSSBilling v0.7.2+**
- KeyHelp API access (with a valid API key)

---

## Installation

1. Clone or download this repo into your FOSSBilling `/library/Server/Manager` folder.
2. Set up a new server in FOSSBilling and select this module.
3. Enter your **KeyHelp hostname/IP**. 
4. Put your **KeyHelp API key** in the server's password field.
5. **Important:** Your products in FOSSBilling need a custom parameter called `id_hosting_plan`. This must be the exact Plan ID number from KeyHelp (do not use the template name). You can find the ID in the URL when editing a template in KeyHelp.

---

## Good to Know & Limitations

- The module uses username-based lookups for all account actions.
- KeyHelp's API doesn't support changing usernames, domains, or IP addresses.
- Reseller login URLs are not available.
- Account syncing is pretty basic—it essentially just checks if the account exists.

---

## License

This project is licensed under the **[GPLv3 License](LICENSE)**. 
Feel free to use, modify, and redistribute it—just keep the credits intact and derivatives open source.
