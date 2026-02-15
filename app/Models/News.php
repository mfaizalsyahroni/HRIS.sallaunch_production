<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;




class News extends Model
{
    use HasFactory;

    protected $table = 'news';

protected $fillable = [
    'title',
    'slug',
    'excerpt',
    'content',
    'category',
    'thumbnail',
    'is_headline',
    'author_id',
    'published_at',
    'is_published',
    'tags',
    'views',
];


    protected $casts = [
        'is_published' => 'boolean',
        'is_headline' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ======== Relasi ========

    // Relasi ke user (penulis)
    public function author()
    {
        return $this->belongsTo(Worker::class, 'author_id');
    }

    // ======== Mutator & Accessor ========

    // Buat slug otomatis dari title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    // Format tanggal untuk tampilan
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->published_at)->translatedFormat('d F Y');
    }


    //I'm here
    public function getTimeAgoAttribute()
    {
        // Membutuhkan published_at agar tidak null
        if (!$this->published_at) {
            return 'Belum dipublikasi';
        }
        // Menggunakan diffForHumans() dari Carbon
        return Carbon::parse($this->published_at)->diffForHumans();
    }

    // URL gambar
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail
            ? asset('storage/news/' . $this->thumbnail)
            : asset('img/default-news.jpg');
    }

    // ======== Scope untuk query ========

    // Berita yang sudah dipublikasikan
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc');
            // Scope untuk mengambil berita yang sudah dipublikasikan.
            // Hanya menampilkan berita dengan is_published = true,
            // memiliki tanggal publish, dan mengurutkannya dari yang terbaru.
    }



    // Headline utama (ditampilkan besar di atas)
    public function scopeHeadline($query)
    {
        return $query->where('is_headline', true)
            ->where('is_published', true)
            ->latest('published_at');
    }

    // Berita populer
    public function scopePopular($query)
    {
        return $query->where('is_published', true)
            ->orderBy('views', 'desc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


}
