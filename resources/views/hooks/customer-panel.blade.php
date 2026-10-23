<x-card title="Contacts">
    @forelse ($contacts as $contact)
        <div class="border-b border-line-soft py-2 last:border-0">
            <a class="text-control" href="{{ route('contacts.show', $contact) }}">{{ $contact->name }}</a>
            <p class="text-label text-ink/70">{{ $contact->email ?: ($contact->phone ?: '—') }}</p>
        </div>
    @empty
        <x-empty title="No contacts" body="Link a contact to this customer from the contact's page." />
    @endforelse
</x-card>
