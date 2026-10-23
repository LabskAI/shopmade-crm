<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Shopmade\Crm\ContactPanels;
use Shopmade\Crm\Enums\ContactStatus;
use Shopmade\Crm\Models\Contact;
use Tests\TestCase;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

it('lets staff add a contact, link it to a customer and see it on the customer page', function () {
    $user = User::factory()->user()->create();
    $customer = Customer::factory()->create(['name' => 'Harbor Dental']);

    $this->actingAs($user)
        ->post(route('contacts.store'), [
            'name' => 'Rita Moreno',
            'email' => 'rita@example.com',
            'direction' => 'inbound',
            'status' => 'new',
            'customer_id' => $customer->id,
        ])
        ->assertRedirect();

    $contact = Contact::query()->sole();

    $this->actingAs($user)->get(route('contacts.index'))->assertOk()->assertSee('Rita Moreno');
    $this->actingAs($user)->get(route('contacts.show', $contact))->assertOk()->assertSee('Harbor Dental');
    $this->actingAs($user)->get(route('customers.show', $customer))->assertOk()->assertSee('Rita Moreno');
});

it('keeps clients out of contacts', function () {
    $contact = Contact::factory()->create();

    $this->actingAs(User::factory()->client()->create())
        ->get(route('contacts.show', $contact))
        ->assertForbidden();
});

it('renders panels other packages add to the contact page', function () {
    view()->addNamespace('crm-test', __DIR__.'/views');
    app(ContactPanels::class)->add('crm-test::panel', fn (Contact $contact) => ['greeting' => 'Hello '.$contact->name]);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('contacts.show', Contact::factory()->create(['name' => 'Rita'])))
        ->assertOk()
        ->assertSee('Hello Rita');
});

it('lists and adds contacts over MCP', function () {
    require_once base_path('tests/Feature/Agents/Mcp/McpHelpers.php');

    [$bearer, $session] = mcpDirector();

    mcpCall('contact_create', ['name' => 'Sam Lee', 'email' => 'sam@example.com', 'notes' => 'Asked for a quote on the site.'], $bearer, $session)
        ->assertOk()
        ->assertJsonPath('result.structuredContent.contact.status', 'new');

    mcpCall('contact_create', ['name' => 'Bad', 'email' => 'nope'], $bearer, $session)->assertJsonPath('error.code', -32602);

    expect(Contact::query()->sole()->status)->toBe(ContactStatus::New);

    [$reader, $readerSession] = mcpDirector(['operator']);

    mcpCall('contacts_list', ['search' => 'sam'], $reader, $readerSession)
        ->assertOk()
        ->assertJsonPath('result.structuredContent.contacts.0.email', 'sam@example.com');

    mcpCall('contact_create', ['name' => 'Nope'], $reader, $readerSession)->assertJsonMissingPath('result.structuredContent.contact');
});
