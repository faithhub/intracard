<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens; // Add this trait
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'team_id',
        'middle_name',
        'email',
        'is_team',
        'phone',
        'status',
        'password',
        'otp_code',
        'otp_expires_at',
        'otp_verified',
        'account_goal',
        'account_type',
        'status',
        'date_deactivated',
        'payment_setup',
    ];

    protected $appends = ['has_address'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'first_name'        => 'encrypted', // Encrypt first name
            'last_name'         => 'encrypted', // Encrypt last name
            'middle_name'       => 'encrypted', // Encrypt middle name
            'date_deactivated'  => 'datetime',
                                               // 'email' => 'encrypted',      // Encrypt email
                                               // 'phone' => 'encrypted',      // Encrypt phone
                                               // 'status' => 'encrypted',     // Encrypt status
                                               // 'account_goal' => 'encrypted', // Encrypt account goal
                                               // 'account_type' => 'encrypted', // Encrypt account type
                                               // 'payment_setup' => 'encrypted', // Encrypt payment setup
                                               // 'otp_code' => 'encrypted',   // Encrypt OTP code
            'otp_verified'      => 'boolean',  // Cast OTP verified to boolean
            'email_verified_at' => 'datetime', // Handle email verified datetime
            'password'          => 'hashed',   // Automatically hash password
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // Decrypt email when accessed
    // public function getEmailAttribute($value)
    // {
    //     return Crypt::decrypt($value);
    // }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Hash the password before saving.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function transactions()
    {
        return $this->hasMany(CardTransaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function buildCreditCards()
    {
        return $this->hasOne(BuildCreditCard::class, 'user_id');
    }
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id', 'id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }
    public function getHasAddressAttribute()
    {
        return $this->address()->exists();
    }

}
