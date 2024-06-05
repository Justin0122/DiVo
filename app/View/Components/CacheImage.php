<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/// Used to cache online images to local storage
class CacheImage extends Component
{
    public function __construct(public string $src, public string $alt = '', public string $class = '', public string $width = "", public string $height = "")
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;

        $this->handleCache();
    }

    public function handleCache()
    {
        if (!\Cache::has($this->src)) {
            $image = file_get_contents($this->src);
            \Cache::put($this->src, $image, 60 * 24 * 7); // 1 week
        }
        return \Cache::get($this->src);
    }

    public function render(): View
    {
        return view('components.cache-image', ['src' => $this->handleCache(), 'alt' => $this->alt, 'class' => $this->class, 'width' => $this->width, 'height' => $this->height]);
    }
}
