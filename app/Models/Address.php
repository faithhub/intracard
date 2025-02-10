<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'amount',
        'province',
        'city',
        'street_name',
        'postal_code',
        'house_number',
        'unit_number',
        'tenancyAgreement',
        'reoccurring_monthly_day',
        'duration_from',
        'duration_to',
    ];

    protected $appends = ['edit_count', 'last_edit_date'];

    /**
     * Automatically encrypt sensitive fields.
     */
    protected $casts = [
        'name'             => 'encrypted',
        'province'         => 'encrypted',
        'tenancyAgreement' => 'encrypted',
        'city'             => 'encrypted',
        'street_name'      => 'encrypted',
        'postal_code'      => 'encrypted',
        'house_number'     => 'encrypted',
        'unit_number'      => 'encrypted',
    ];

    /**
     * Boot function to handle UUID generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    /**
     * Accessor to format the amount with 2 decimal places.
     *
     * @return string
     */
    // public function getAmountAttribute($value)
    // {
    //     return number_format((float) decrypt($value), 2, '.', '');
    // }

    /**
     * Mutator to ensure the amount is encrypted when set.
     *
     * @param  mixed  $value
     * @return void
     */
    // public function setAmountAttribute($value)
    // {
    //     $this->attributes['amount'] = encrypt(number_format((float) $value, 2, '.', ''));
    // }

    /**
     * Define a relationship to the user.
     */



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relationship to the LandlordFinancerDetails model.
     */
    public function landlordFinanceDetails()
    {
        return $this->hasMany(LandlordFinancerDetail::class, 'address_id');
    }
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function getEditCountAttribute()
    {
        return AddressEditLog::where('address_id', $this->id)
            ->whereYear('edited_at', now()->year)
            ->count();
    }

    public function getLastEditDateAttribute()
    {
        $lastEdit = AddressEditLog::where('address_id', $this->id)
            ->latest('edited_at')
            ->first();
            
        return $lastEdit ? $lastEdit->edited_at : null;
    }
}
