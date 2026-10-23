<?php

namespace Shopmade\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Shopmade\Crm\ContactPanels;
use Shopmade\Crm\Enums\ContactDirection;
use Shopmade\Crm\Enums\ContactStatus;
use Shopmade\Crm\Models\Contact;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $contacts = Contact::query()
            ->with('customer')
            ->when($request->string('q')->toString(), function ($query, string $search) {
                $like = '%'.strtolower($search).'%';
                $query->where(function ($inner) use ($like) {
                    $inner->whereRaw('lower(name) like ?', [$like])
                        ->orWhereRaw('lower(coalesce(email, \'\')) like ?', [$like])
                        ->orWhereRaw('lower(coalesce(phone, \'\')) like ?', [$like])
                        ->orWhereRaw('lower(coalesce(company, \'\')) like ?', [$like]);
                });
            })
            ->when($request->string('direction')->toString(), function ($query, string $direction) {
                $query->where('direction', $direction);
            })
            ->when($request->string('status')->toString(), function ($query, string $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('crm::contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('crm::contacts.create', [
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $contact = Contact::query()->create($this->validated($request));

        return redirect()->route('contacts.show', $contact)->with('status', 'Contact created.');
    }

    public function show(Contact $contact, ContactPanels $panels): View
    {
        $contact->load('customer');

        return view('crm::contacts.show', [
            'contact' => $contact,
            'panels' => $panels->resolve($contact),
        ]);
    }

    public function edit(Contact $contact): View
    {
        return view('crm::contacts.edit', [
            'contact' => $contact,
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update($this->validated($request));

        return redirect()->route('contacts.show', $contact)->with('status', 'Contact updated.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('status', 'Contact deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'direction' => ['required', Rule::enum(ContactDirection::class)],
            'status' => ['required', Rule::enum(ContactStatus::class)],
            'notes' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'exists:customers,id'],
        ]);
    }
}
