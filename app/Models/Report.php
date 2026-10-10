<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'location',
        'contact_number',
        'facebook_link',
        'description',
        'photo',
        'status',
        'responder_name',
        'admin_remarks',
        'backed_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ReportStatusHistory::class)->latest('created_at');
    }

    public function photoCollection(): array
    {
        $raw = $this->photo;

        if (empty($raw)) {
            return [];
        }

        if (is_array($raw)) {
            return array_values(array_filter(array_map(function ($item) {
                return is_string($item) ? trim($item) : null;
            }, $raw)));
        }

        if (is_string($raw)) {
            $trimmed = trim($raw);

            if ($trimmed === '') {
                return [];
            }

            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map(function ($item) {
                    return is_string($item) ? trim($item) : null;
                }, $decoded)));
            }

            if (str_contains($trimmed, ',')) {
                return array_values(array_filter(array_map(function ($item) {
                    return trim((string) $item) !== '' ? trim((string) $item) : null;
                }, explode(',', $trimmed))));
            }

            return [$trimmed];
        }

        return [];
    }

    public function firstPhoto(): ?string
    {
        $photos = $this->photoCollection();

        return $photos[0] ?? null;
    }

    public function hasPhotos(): bool
    {
        return ! empty($this->photoCollection());
    }
}
