<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'title',
        'image',
        'description',
    ];

    /**
     * Full displayable URL for the image — works whether `image` is a local
     * storage path (real admin upload) or a full external URL (seeded demo
     * data / placeholder images), so views never need to branch on this.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset('storage/'.$this->image);
    }
}