<?php

namespace Shopmade\Crm;

use App\Core\Packages\Hooks;
use App\Models\Customer;
use Illuminate\Support\ServiceProvider;
use Shopmade\Crm\Mcp\ContactCreateTool;
use Shopmade\Crm\Mcp\ContactsListTool;
use Shopmade\Crm\Models\Contact;

/**
 * Contacts: people and leads, optionally tied to a customer, plugged into core through Hooks.
 */
class CrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ContactPanels::class);
    }

    public function boot(Hooks $hooks): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'crm');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Customer::resolveRelationUsing('contacts', fn (Customer $customer) => $customer->hasMany(Contact::class));

        $hooks->tool([
            'slug' => 'contacts',
            'label' => 'Contacts',
            'group' => 'People',
            'route' => 'contacts.index',
            'pattern' => 'contacts.*',
            'view_roles' => ['super_admin', 'admin', 'user'],
            'manage_roles' => ['super_admin', 'admin', 'user'],
        ]);

        $hooks->customerPanel('crm::hooks.customer-panel', fn (Customer $customer): array => [
            'contacts' => $customer->contacts()->orderBy('name')->get(),
        ]);

        $hooks->mcpTool('contacts_list', ContactsListTool::class);
        $hooks->mcpTool('contact_create', ContactCreateTool::class);
    }
}
