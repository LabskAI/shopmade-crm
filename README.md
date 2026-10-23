# shopmade-crm

Contacts for [Shopmade core](https://github.com/LabskAI/shopmade-core): people and leads, inbound or outbound, optionally linked to a customer. MIT licensed.

## What it adds

- **Staff:** Contacts in the sidebar (People group) with search and direction/status filters.
- **Customer page:** the customer's contacts.
- **MCP:** `contacts_list` (read-only) and `contact_create` (writes), so Claude can log a lead from a call or the website.
- **For other packages:** `Shopmade\Crm\ContactPanels`. Resolve it in your provider's `boot()` and `add('your::view', fn (Contact $contact) => [...])` to put a panel on the contact page. The outreach package uses it for its history.

## Install

```bash
composer require labskai/shopmade-crm
php artisan migrate
npm run build
```

## Tests

```bash
cd ../shopmade-core
composer config repositories.crm '{"type":"path","url":"../shopmade-crm"}'
composer require "labskai/shopmade-crm:*@dev"
vendor/bin/pest vendor/labskai/shopmade-crm/tests
```
