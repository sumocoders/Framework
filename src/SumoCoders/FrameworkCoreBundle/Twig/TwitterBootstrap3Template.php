<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SumoCoders\FrameworkCoreBundle\Twig;

use Pagerfanta\View\Template\TwitterBootstrapTemplate;

/**
 * TwitterBootstrap3Template
 */
class TwitterBootstrap3Template extends TwitterBootstrapTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->setOptions(array('active_suffix' => '<span class="sr-only">current page</span>'));
    }

    public function container()
    {
        return sprintf(
            '<nav aria-label="Page navigation"><ul class="%s">%%pages%%</ul></nav>',
            $this->option('css_container_class')
        );
    }
}
