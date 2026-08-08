<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'phone_verified_at',
        'two_factor_verified_at',
        'pan_number',
        'kyc_id_proof_path',
        'kyc_status',
        'kyc_submitted_at',
        'kyc_verified_at',
        'kyc_rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function inquiries()
    {
        return $this->hasMany(\App\Models\Inquiry::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(\App\Models\RecentlyViewed::class);
    }

    public function auctionBids()
    {
        return $this->hasMany(\App\Models\AuctionBid::class);
    }

    public function auctionDeposits()
    {
        return $this->hasMany(\App\Models\Payment::class, 'user_id')->where('payment_type', 'auction_deposit');
    }

    public function hasVerifiedKyc(): bool
    {
        return $this->kyc_status === 'verified';
    }

    public function hasVerifiedDepositFor(int $auctionId): bool
    {
        return $this->auctionDeposits()
            ->where('auction_id', $auctionId)
            ->where('status', 'completed')
            ->exists();
    }
}
