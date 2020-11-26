<?php

namespace App\Services;


use Illuminate\Support\Collection;
use samdark\sitemap\Sitemap;

class SitemapService
{

    protected $sitemap;

    protected $items;

    protected $baseURL;

    public function __construct(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    public function build(Collection $items, $baseURL)
    {
        $sitemap = $this->sitemap;
        $currentTime = time();

        $items->map(function($item) use ($sitemap, $baseURL, $currentTime) {

            try {
                $sitemap->addItem("{$baseURL}{$item->UrlTrack}/ID-{$item->ID}", $currentTime);
            } catch (\Exception $e) {

                // Ainda sem tratamento de erros
            }
        });

        return $sitemap;
    }


    public function save()
    {
        $this->sitemap->write();
    }
}