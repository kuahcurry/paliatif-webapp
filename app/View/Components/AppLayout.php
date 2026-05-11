<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public bool $hideNavigation;
    public bool $hideHeader;
    public string $bodyClass;
    public string $pageClass;

    public function __construct(
        bool $hideNavigation = false,
        bool $hideHeader = false,
        string $bodyClass = 'font-sans antialiased',
        string $pageClass = 'min-h-screen bg-gray-100'
    ) {
        $this->hideNavigation = $hideNavigation;
        $this->hideHeader = $hideHeader;
        $this->bodyClass = $bodyClass;
        $this->pageClass = $pageClass;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
