<?php

namespace OnixSystemsPHP\HyperfInertia\View\Component;

use Hyperf\Context\ApplicationContext;
use Hyperf\ViewEngine\Component\Component;

class ReactRefresh extends Component
{
    public function render(): mixed
    {
        return "<?php echo \Hyperf\Support\make(\OnixSystemsPHP\HyperfInertia\Vite::class)->reactRefresh() ?>";
    }

}
