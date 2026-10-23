<?php

namespace Shopmade\Crm\Mcp;

use App\Core\Mcp\McpContext;
use App\Core\Mcp\McpError;
use App\Core\Mcp\Tools\Tool;
use App\Core\Mcp\Tools\ToolResult;
use Shopmade\Crm\Enums\ContactDirection;
use Shopmade\Crm\Enums\ContactStatus;
use Shopmade\Crm\Models\Contact;

class ContactCreateTool extends Tool
{
    public function name(): string
    {
        return 'contact_create';
    }

    public function title(): string
    {
        return 'Add a contact';
    }

    public function description(): string
    {
        return 'Adds a person or lead to Contacts. Give at least a name, plus an email or phone when you have one. New contacts start with status "new".';
    }

    public function annotations(): array
    {
        return $this->writes(destructive: false, idempotent: false);
    }

    public function inputSchema(): array
    {
        return $this->objectSchema([
            'name' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 255],
            'email' => ['type' => 'string', 'maxLength' => 255],
            'phone' => ['type' => 'string', 'maxLength' => 50],
            'company' => ['type' => 'string', 'maxLength' => 255],
            'direction' => ['type' => 'string', 'enum' => array_map(fn (ContactDirection $direction): string => $direction->value, ContactDirection::cases()), 'default' => 'inbound'],
            'notes' => ['type' => 'string', 'maxLength' => 5000],
        ], ['name']);
    }

    public function handle(array $arguments, McpContext $context): ToolResult
    {
        $email = trim($arguments['email'] ?? '');

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw McpError::invalidParams('email is not a valid address');
        }

        $contact = Contact::query()->create([
            'name' => $arguments['name'],
            'email' => $email ?: null,
            'phone' => ($arguments['phone'] ?? '') ?: null,
            'company' => ($arguments['company'] ?? '') ?: null,
            'direction' => ContactDirection::from($arguments['direction'] ?? 'inbound'),
            'status' => ContactStatus::New,
            'notes' => ($arguments['notes'] ?? '') ?: null,
        ]);

        return new ToolResult(['contact' => self::present($contact)]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function present(Contact $contact): array
    {
        return [
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'company' => $contact->company,
            'direction' => $contact->direction->value,
            'status' => $contact->status->value,
            'customer' => $contact->customer?->name,
            'url' => route('contacts.show', $contact),
        ];
    }
}
