@extends('layouts.app')

@section('title', 'New contact')
@section('breadcrumb', 'Contacts / New')

@section('content')
    <x-page-header title="New contact" kicker="People" />
    <form method="POST" action="{{ route('contacts.store') }}" class="max-w-3xl">
        @csrf
        <x-card title="Details">
            @include('crm::contacts._form')
            <div class="mt-6 flex justify-start gap-2">
                <x-button type="submit">Save</x-button>
                <x-button variant="secondary" href="{{ route('contacts.index') }}">Cancel</x-button>
            </div>
        </x-card>
    </form>
@endsection
