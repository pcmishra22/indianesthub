<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionDocument extends Model
{
    use HasFactory;

    public const TYPES = [
        'sale_deed'              => 'Sale Deed',
        'ownership_proof'        => 'Ownership Proof / Title Document',
        'loan_noc'                => 'Bank Loan NOC (if mortgaged)',
        'encumbrance_certificate' => 'Encumbrance Certificate',
        'site_map'                => 'Site Map / Layout Plan',
        'property_tax_receipt'    => 'Latest Property Tax Receipt',
        'identity_proof'          => 'Seller Identity Proof (PAN/Aadhaar)',
        'other'                   => 'Other Supporting Document',
    ];

    protected $fillable = [
        'auction_id', 'document_type', 'title', 'file_path', 'original_filename',
        'status', 'admin_remarks', 'reviewed_by_admin_id', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->document_type] ?? ($this->title ?: 'Document');
    }
}
