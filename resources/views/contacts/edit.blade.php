@extends('layouts.app')

@section('title', 'Edit contact')
@section('breadcrumb', 'Contacts / Edit')

@section('content')
    <x-page-header title="Edit {{ $contact->name }}" kicker="People" />
    <form method="POST" action="{{ route('contacts.update', $contact) }}" class="max-w-3xl">
        @csrf
        @method('PUT')
        <x-card title="Details">
            @include('crm::contacts._form')
            <div class="mt-6 flex justify-start gap-2">
                <x-button type="submit">Save</x-button>
                <x-button variant="secondary" href="{{ route('contacts.show', $contact) }}">Cancel</x-button>
            </div>
        </x-card>
    </form>
@endsection
