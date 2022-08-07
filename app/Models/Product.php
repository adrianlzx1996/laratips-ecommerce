<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    class Product extends Model
    {
        use HasFactory;

        public function categories ()
        : BelongsToMany
        {
            return $this->belongsToMany(Category::class);
        }

        public function scopeActive ()
        {
            return $this->where('active', true);
        }

        public function scopeInactive ()
        {
            return $this->where('active', false);
        }
    }
