@extends('layouts.app')

@section('title', 'Contacts')
@section('breadcrumb', 'Contacts')

@section('content')
    <x-page-header title="Contacts" kicker="People">
        <x-slot:actions>
            <x-button href="{{ route('contacts.create') }}"><i data-lucide="plus" class="size-4"></i>New contact</x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="border-2 border-line bg-surface">
        <form method="GET">
            <x-toolbar title="Contacts" :count="$contacts->total()">
                <x-slot:right>
                    <x-input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email, phone, company" />
                </x-slot:right>
            </x-toolbar>
        </form>
        <x-table :columns="[['label' => 'Name'], ['label' => 'Company'], ['label' => 'Direction'], ['label' => 'Status'], ['label' => 'Phone'], ['label' => 'Email']]">
            @foreach ($contacts as $contact)
                <tr class="border-b border-line-soft">
                    <td class="px-4 py-3 text-control"><a href="{{ route('contacts.show', $contact) }}">{{ $contact->name }}</a></td>
                    <td class="px-4 py-3 text-control text-ink/70">{{ $contact->company ?: '—' }}</td>
                    <td class="px-4 py-3 text-control text-ink/70">{{ $contact->direction->label() }}</td>
                    <td class="px-4 py-3 text-control text-ink/70">{{ $contact->status->label() }}</td>
                    <td class="px-4 py-3 text-control text-ink/70">{{ $contact->phone ?: '—' }}</td>
                    <td class="px-4 py-3 text-control text-ink/70">{{ $contact->email ?: '—' }}</td>
                </tr>
            @endforeach
            <x-slot:empty>
                <x-empty title="No contacts yet" body="Log people you reach or who reach you.">
                    <x-slot:action>
                        <x-button variant="secondary" href="{{ route('contacts.create') }}">New contact</x-button>
                    </x-slot:action>
                </x-empty>
            </x-slot:empty>
        </x-table>
        {{ $contacts->links() }}
    </div>
@endsection
