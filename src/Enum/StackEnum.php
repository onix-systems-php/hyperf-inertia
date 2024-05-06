<?php

declare(strict_types=1);

namespace OnixSystemsPHP\HyperfInertia\Enum;

enum StackEnum: string
{
    case VUE = 'vue';
    case REACT = 'react';
    case SVELTE = 'svelte';
}
