<?php

namespace Sonover\Docs\Http\Controllers;

use Sonover\Docs\Documentation;

class DocumentationController
{
    /**
     * The documentation repository.
     *
     * @var \App\Documentation
     */
    protected $docs;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Documentation  $docs
     * @return void
     */
    public function __construct(Documentation $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Show the root documentation page (/docs).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showRootPage()
    {
        return $this->redirectRoot();
    }

    /**
     * Show a documentation page.
     *
     * @param  string  $version
     * @param  string|null  $page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($version, $page = null)
    {
        if (count(Documentation::getDocVersions()) == 1) {
            $version = config('documentation.current');
        }

        if (!$this->isVersion($version)) {
            return redirect('docs/' . Documentation::defaultVersion() . '/' . $version, 301);
        }

        if (!defined('CURRENT_VERSION')) {
            define('CURRENT_VERSION', $version);
        }

        $sectionPage = $page ?: Documentation::defaultPage();
        $content = $this->docs->get($version, $sectionPage);

        if (is_null($content)) {
            abort(404);
        }
        $section = '';

        if ($this->docs->sectionExists($version, $page)) {
            $section .= '/' . $page;
        } elseif (!is_null($page)) {
            return $this->redirectRoot();
        }

        return view('documentation::docs', array_merge($content, [
            'index' => $this->docs->getTableOfContents($version),
            'section' => $section,
            'currentVersion' => $version,
            'versions' => Documentation::getDocVersions(),
        ]));
    }

    /**
     * Determine if the given URL segment is a valid version.
     *
     * @param  string  $version
     * @return bool
     */
    protected function isVersion($version)
    {
        return array_key_exists($version, Documentation::getDocVersions());
    }

    protected function redirectRoot()
    {
        if (count(Documentation::getDocVersions()) == 1) {
            return redirect(config('documentation.path') . '/' . Documentation::defaultPage());
        }
        return redirect(config('documentation.path') . '/' . Documentation::defaultVersion() . '/' . Documentation::defaultPage());
    }
}
