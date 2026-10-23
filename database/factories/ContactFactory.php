<?php

namespace Shopmade\Crm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopmade\Crm\Enums\ContactDirection;
use Shopmade\Crm\Enums\ContactStatus;
use Shopmade\Crm\Models\Contact;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->optional(0.8)->safeEmail(),
            'phone' => fake()->optional(0.7)->numerify('(###) ###-####'),
            'company' => fake()->optional(0.6)->company(),
            'direction' => fake()->randomElement(ContactDirection::cases()),
            'status' => fake()->randomElement(ContactStatus::cases()),
            'notes' => fake()->optional(0.4)->sentence(),
            'customer_id' => null,
        ];
    }
}
