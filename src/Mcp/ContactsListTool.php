<?php

namespace Shopmade\Crm\Mcp;

use App\Core\Mcp\McpContext;
use App\Core\Mcp\Tools\Tool;
use App\Core\Mcp\Tools\ToolResult;
use App\Enums\ConnectorScope;
use Shopmade\Crm\Enums\ContactStatus;
use Shopmade\Crm\Models\Contact;

class ContactsListTool extends Tool
{
    public function name(): string
    {
        return 'contacts_list';
    }

    public function title(): string
    {
        return 'List contacts';
    }

    public function description(): string
    {
        return 'Contacts, newest first, with email, phone, company, direction, status and linked customer. Search matches name, email, phone and company.';
    }

    public function scope(): ConnectorScope
    {
        return ConnectorScope::Operator;
    }

    public function inputSchema(): array
    {
        return $this->objectSchema([
            'search' => ['type' => 'string', 'maxLength' => 100],
            'status' => ['type' => 'string', 'enum' => array_map(fn (ContactStatus $status): string => $status->value, ContactStatus::cases())],
            'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 25],
        ]);
    }

    public function handle(array $arguments, McpContext $context): ToolResult
    {
        $contacts = Contact::query()
            ->with('customer')
            ->when($arguments['search'] ?? null, function ($query, string $search) {
                $like = '%'.strtolower($search).'%';
                $query->where(fn ($inner) => $inner->whereRaw('lower(name) like ?', [$like])
                    ->orWhereRaw('lower(coalesce(email, \'\')) like ?', [$like])
                    ->orWhereRaw('lower(coalesce(phone, \'\')) like ?', [$like])
                    ->orWhereRaw('lower(coalesce(company, \'\')) like ?', [$like]));
            })
            ->when($arguments['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest('id')
            ->limit($arguments['limit'] ?? 25)
            ->get();

        return new ToolResult([
            'contacts' => $contacts->map(fn (Contact $contact): array => ContactCreateTool::present($contact))->all(),
        ]);
    }
}
