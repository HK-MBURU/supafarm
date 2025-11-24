<?php

namespace App\Helpers;

class SeoHelper
{
    public static function getOgImage($type = 'default')
    {
        $images = [
            'default' => 'images/social/og-image.jpg',
            'twitter' => 'images/social/twitter-image.jpg',
            'wide' => 'images/social/og-image-wide.jpg',
            'logo' => 'images/logo/logo-wide.png',
        ];

        return asset($images[$type] ?? $images['default']);
    }

    public static function getMetaTags($title = null, $description = null, $image = null)
    {
        return [
            'title' => $title ?? 'Supa Farm Supplies - Premium Eggs, Honey & Coffee in Kenya',
            'description' => $description ?? 'Kenya\'s leading supplier of fresh farm eggs, pure honey, and premium coffee.',
            'image' => $image ?? self::getOgImage(),
            'url' => url()->current(),
        ];
    }
}
