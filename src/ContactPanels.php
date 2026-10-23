<?php

namespace Shopmade\Crm;

use Closure;
use Shopmade\Crm\Models\Contact;

/**
 * Panels other packages add to the contact page (outreach history, for one). Resolve it in a provider's boot()
 * and call add(); the contact page renders each view with the data the closure returns.
 */
class ContactPanels
{
    /**
     * @var list<array{view: string, data: Closure|null}>
     */
    private array $panels = [];

    /**
     * @param  Closure(Contact): array<string, mixed>|null  $data
     */
    public function add(string $view, ?Closure $data = null): void
    {
        $this->panels[] = ['view' => $view, 'data' => $data];
    }

    /**
     * @return list<array{view: string, data: array<string, mixed>}>
     */
    public function resolve(Contact $contact): array
    {
        return array_map(fn (array $panel): array => [
            'view' => $panel['view'],
            'data' => $panel['data'] ? ($panel['data'])($contact) : [],
        ], $this->panels);
    }
}
