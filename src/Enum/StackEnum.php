<?php

namespace OnixSystemsPHP\HyperfInertia\Enum;

use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string', enum: ['vue', 'react', 'svelte'])]
enum StackEnum: string
{
    case VUE = 'vue';
    case REACT = 'react';
    case SVELTE = 'svelte';
}


