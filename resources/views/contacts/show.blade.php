@extends('layouts.app')

@section('title', $contact->name)
@section('breadcrumb', 'Contacts / '.$contact->name)

@section('content')
    <x-page-header title="{{ $contact->name }}" kicker="People">
        <x-slot:actions>
            <x-button href="{{ route('contacts.edit', $contact) }}">Edit</x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-2">
        <x-card title="Details">
            <dl class="space-y-3 text-body">
                <div>
                    <dt class="text-label text-ink/70">Company</dt>
                    <dd>{{ $contact->company ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Direction</dt>
                    <dd>{{ $contact->direction->label() }}</dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Status</dt>
                    <dd>{{ $contact->status->label() }}</dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Phone</dt>
                    <dd>
                        @if ($contact->phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}">{{ $contact->phone }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Email</dt>
                    <dd>
                        @if ($contact->email)
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Linked customer</dt>
                    <dd>
                        @if ($contact->customer)
                            <a href="{{ route('customers.show', $contact->customer) }}">{{ $contact->customer->name }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-label text-ink/70">Notes</dt>
                    <dd>{{ $contact->notes ?: '—' }}</dd>
                </div>
            </dl>
        </x-card>

        @foreach ($panels as $panel)
            @include($panel['view'], $panel['data'])
        @endforeach

        <x-card title="Danger">
            <form method="POST" action="{{ route('contacts.destroy', $contact) }}" onsubmit="return confirm('Delete this contact?')">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="secondary">Delete contact</x-button>
            </form>
        </x-card>
    </div>
@endsection
