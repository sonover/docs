<?php

namespace Sonover\Docs;

use Parsedown;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;

class Documentation
{
    /**
     * The filesystem implementation.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The cache implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    protected $bladeCompiler;

    /**
     * Create a new documentation instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(Filesystem $files, Cache $cache, BladeCompiler $bladeCompiler, Factory $viewFactory)
    {
        $this->files = $files;
        $this->cache = $cache;
        $this->bladeCompiler = $bladeCompiler;
        $this->viewFactory = $viewFactory;
        $this->viewFactory->addExtension('md', 'blade');
        $this->viewFactory->addExtension('md.blade.php', 'blade');
    }

    /**
     * Get the documentation index page.
     *
     * @param  string  $version
     * @return string|null
     */
    public function getTableOfContents($version)
    {
        return $this->cache->remember('docs.' . $version . '.index', 5, function () use ($version) {
            $path = $version . '/' . config('documentation.contents');
            $content = $this->viewFactory->make($path)->render();
            $content = (new Parsedown())->text($content);
            $content = $this->replaceLinks($version, $content);
            return $content;
        });
    }

    /**
     * Get the given documentation page.
     *
     * @param  string  $version
     * @param  string  $page
     * @return string|null
     */
    public function get($version, $page)
    {
        return $this->cache->remember('docs.' . $version . '.' . $page, 5, function () use ($version, $page) {
            $path = $version . '/' . $page . '.md';

            if (Storage::disk(config('documentation.disk'))->exists($path)) {
                $content = $this->replaceLinks($version, Storage::disk(config('documentation.disk'))->get($path));
                $result = YamlFrontMatter::parse($content);

                return array_merge($result->matter(), ['content' => (new Parsedown())->text($result->body())]);
            }

            return null;
        });
    }

    /**
     * Replace the version place-holder in links.
     *
     * @param  string  $version
     * @param  string  $content
     * @return string
     */
    public static function replaceLinks($version, $content)
    {
        return str_replace('{{version}}', $version, $content);
    }

    /**
     * Check if the given section exists.
     *
     * @param  string  $version
     * @param  string  $page
     * @return boolean
     */
    public function sectionExists($version, $page)
    {
        return Storage::disk(config('documentation.disk'))->exists($version . '/' . $page . '.md');
    }

    /**
     * Determine which versions a page exists in.
     *
     * @param  string  $page
     * @return \Illuminate\Support\Collection
     */
    public function versionsContainingPage($page)
    {
        return collect(static::getDocVersions())
            ->filter(function ($version) use ($page) {
                return $this->sectionExists($version, $page);
            });
    }

    public static function currentVersion()
    {
        return config('documentation.current');
    }

    public static function defaultPage()
    {
        return config('documentation.start_page');
    }


    public static function defaultVersion()
    {
        return config('documentation.default');
    }

    /**
     * Get the publicly available versions of the documentation
     *
     * @return array
     */
    public static function getDocVersions()
    {

        return config('documentation.versions');
    }
}
