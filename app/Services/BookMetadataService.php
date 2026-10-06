<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookMetadataService
{
    /**
     * Fetch book metadata by ISBN using Google Books API with Open Library fallback.
     *
     * @param string $isbn
     * @return array|null
     */
    public function lookupByIsbn(string $isbn): ?array
    {
        $cleanIsbn = preg_replace('/[^0-9X]/i', '', $isbn);

        if (empty($cleanIsbn)) {
            return null;
        }

        // 1. Try Google Books API
        $googleData = $this->fetchFromGoogleBooks($cleanIsbn);
        if ($googleData) {
            return $googleData;
        }

        // 2. Fallback to Open Library API
        $openLibraryData = $this->fetchFromOpenLibrary($cleanIsbn);
        if ($openLibraryData) {
            return $openLibraryData;
        }

        return null;
    }

    /**
     * Query Google Books API.
     */
    protected function fetchFromGoogleBooks(string $isbn): ?array
    {
        try {
            $response = Http::timeout(8)->get('https://www.googleapis.com/books/v1/volumes', [
                'q' => 'isbn:' . $isbn,
            ]);

            if ($response->successful()) {
                $items = $response->json('items');
                if (!empty($items) && isset($items[0]['volumeInfo'])) {
                    $info = $items[0]['volumeInfo'];

                    $authors = isset($info['authors']) ? implode(', ', $info['authors']) : 'Unknown';
                    $publishedYear = null;
                    if (!empty($info['publishedDate'])) {
                        if (preg_match('/\b\d{4}\b/', $info['publishedDate'], $matches)) {
                            $publishedYear = $matches[0];
                        }
                    }

                    $coverUrl = null;
                    if (isset($info['imageLinks'])) {
                        $coverUrl = $info['imageLinks']['thumbnail']
                            ?? $info['imageLinks']['smallThumbnail']
                            ?? null;
                        if ($coverUrl) {
                            $coverUrl = str_replace('http://', 'https://', $coverUrl);
                        }
                    }

                    return [
                        'source' => 'google_books',
                        'isbn_barcode' => $isbn,
                        'title' => $info['title'] ?? '',
                        'author' => $authors,
                        'publisher' => $info['publisher'] ?? '',
                        'published_year' => $publishedYear ?? '',
                        'description' => $info['description'] ?? '',
                        'cover_image_url' => $coverUrl,
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Google Books lookup failed for ISBN {$isbn}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Query Open Library API.
     */
    protected function fetchFromOpenLibrary(string $isbn): ?array
    {
        try {
            $bibKey = 'ISBN:' . $isbn;
            $response = Http::timeout(8)->get('https://openlibrary.org/api/books', [
                'bibkeys' => $bibKey,
                'format' => 'json',
                'jscmd' => 'data',
            ]);

            if ($response->successful()) {
                $json = $response->json();
                if (!empty($json[$bibKey])) {
                    $item = $json[$bibKey];

                    $authors = [];
                    if (!empty($item['authors'])) {
                        foreach ($item['authors'] as $author) {
                            if (!empty($author['name'])) {
                                $authors[] = $author['name'];
                            }
                        }
                    }

                    $publishers = [];
                    if (!empty($item['publishers'])) {
                        foreach ($item['publishers'] as $pub) {
                            if (!empty($pub['name'])) {
                                $publishers[] = $pub['name'];
                            }
                        }
                    }

                    $publishedYear = null;
                    if (!empty($item['publish_date'])) {
                        if (preg_match('/\b\d{4}\b/', $item['publish_date'], $matches)) {
                            $publishedYear = $matches[0];
                        }
                    }

                    $coverUrl = $item['cover']['large'] ?? $item['cover']['medium'] ?? $item['cover']['small'] ?? null;

                    $description = '';
                    if (!empty($item['notes'])) {
                        $description = is_string($item['notes']) ? $item['notes'] : ($item['notes']['value'] ?? '');
                    }

                    return [
                        'source' => 'open_library',
                        'isbn_barcode' => $isbn,
                        'title' => $item['title'] ?? '',
                        'author' => !empty($authors) ? implode(', ', $authors) : 'Unknown',
                        'publisher' => !empty($publishers) ? implode(', ', $publishers) : '',
                        'published_year' => $publishedYear ?? '',
                        'description' => $description,
                        'cover_image_url' => $coverUrl,
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Open Library lookup failed for ISBN {$isbn}: " . $e->getMessage());
        }

        return null;
    }
}
