<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchHero extends Component
{
    public $background;
    public $title;
    public $highlight;
    public $subtitle;

    public function __construct($background, $title, $highlight, $subtitle)
    {
        $this->background = $background;
        $this->title = $title;
        $this->highlight = $highlight;
        $this->subtitle = $subtitle;
    }

    public function render()
    {
        return view('components.search-hero');
    }
}
