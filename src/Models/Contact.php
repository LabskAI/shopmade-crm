<?php

namespace Shopmade\Crm\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopmade\Crm\Database\Factories\ContactFactory;
use Shopmade\Crm\Enums\ContactDirection;
use Shopmade\Crm\Enums\ContactStatus;

#[UseFactory(ContactFactory::class)]
#[Fillable(['name', 'email', 'phone', 'company', 'direction', 'status', 'notes', 'customer_id'])]
class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'direction' => ContactDirection::class,
            'status' => ContactStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
