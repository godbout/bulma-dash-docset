<?php

namespace App\Docsets;

use Godbout\DashDocsetBuilder\Docsets\BaseDocset;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class Chartjs extends BaseDocset
{
    public const CODE = 'chartjs';
    public const NAME = 'Chart.js';
    public const URL = 'www.chartjs.org';
    public const INDEX = 'index.html';
    public const PLAYGROUND = '';
    public const ICON_16 = '../../icons/icon.png';
    public const ICON_32 = '../../icons/icon@2x.png';
    public const EXTERNAL_DOMAINS = [
    ];


    public function grab(): bool
    {
        $toIgnore = implode('|', [
            '/docs/2.9.3',
            '/docs/master',
            '/samples/master'
        ]);

        $toGet = implode('|', [
            '\.css',
            // '\.gif',
            '\.ico',
            // '\.jpg',
            '\.js',
            // '\.png',
            // '\.svg',
            // '\.webmanifest',
            '/docs',
            '/samples'
        ]);

        system(
            "echo; wget www.chartjs.org \
                --mirror \
                --trust-server-names \
                --reject-regex='{$toIgnore}' \
                --accept-regex='{$toGet}' \
                --ignore-case \
                --page-requisites \
                --adjust-extension \
                --convert-links \
                --span-hosts \
                --domains={$this->externalDomains()} \
                --directory-prefix=storage/{$this->downloadedDirectory()} \
                -e robots=off \
                --quiet \
                --show-progress",
            $result
        );

        return $result === 0;
    }

    public function entries(string $file): Collection
    {
        $crawler = HtmlPageCrawler::create(Storage::get($file));

        $entries = collect();

        $crawler->filter('.summary a')->each(function (HtmlPageCrawler $node) use ($entries) {
            $entries->push([
                'name' => $node->text(),
                'type' => 'Guide',
                'path' => $node->attr('href'),
            ]);
        });

        return $entries;
    }

    public function format(string $file): string
    {
        $crawler = HtmlPageCrawler::create(Storage::get($file));

        return $crawler->saveHTML();
    }
}
