@php
    $directionOptions = collect(\Shopmade\Crm\Enums\ContactDirection::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()]);
    $statusOptions = collect(\Shopmade\Crm\Enums\ContactStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()]);
    $customerOptions = collect($customers ?? [])->mapWithKeys(fn ($c) => [$c->id => $c->name]);
@endphp
<div class="grid gap-4 sm:grid-cols-2">
    <x-field class="sm:col-span-2" label="Name" for="name" :error="$errors->first('name')">
        <x-input id="name" name="name" value="{{ old('name', $contact->name ?? '') }}" required :error="$errors->has('name')" />
    </x-field>
    <x-field label="Phone" for="phone" :error="$errors->first('phone')">
        <x-input id="phone" name="phone" value="{{ old('phone', $contact->phone ?? '') }}" :error="$errors->has('phone')" />
    </x-field>
    <x-field label="Email" for="email" :error="$errors->first('email')">
        <x-input id="email" name="email" type="email" value="{{ old('email', $contact->email ?? '') }}" :error="$errors->has('email')" />
    </x-field>
    <x-field class="sm:col-span-2" label="Company" for="company" :error="$errors->first('company')">
        <x-input id="company" name="company" value="{{ old('company', $contact->company ?? '') }}" :error="$errors->has('company')" />
    </x-field>
    <x-field label="Direction" for="direction" :error="$errors->first('direction')">
        <x-select id="direction" name="direction" :options="$directionOptions" :selected="old('direction', isset($contact) ? $contact->direction->value : 'inbound')" :error="$errors->has('direction')" />
    </x-field>
    <x-field label="Status" for="status" :error="$errors->first('status')">
        <x-select id="status" name="status" :options="$statusOptions" :selected="old('status', isset($contact) ? $contact->status->value : 'new')" :error="$errors->has('status')" />
    </x-field>
    <x-field class="sm:col-span-2" label="Linked customer" for="customer_id" :error="$errors->first('customer_id')">
        <x-select id="customer_id" name="customer_id" :options="$customerOptions" :selected="old('customer_id', $contact->customer_id ?? '')" placeholder="None" :error="$errors->has('customer_id')" />
    </x-field>
    <x-field class="sm:col-span-2" label="Notes" for="notes" :error="$errors->first('notes')">
        <x-textarea id="notes" name="notes" rows="4" :error="$errors->has('notes')">{{ old('notes', $contact->notes ?? '') }}</x-textarea>
    </x-field>
</div>
