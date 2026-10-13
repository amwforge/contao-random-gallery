<?php

declare(strict_types=1);

/*
 * This file is part of contao-random-gallery.
 *
 * (c) Andreas Maier <mail@maier-digital.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/amwforge/contao-random-gallery
 */

namespace Amwforge\ContaoRandomGallery;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AmwforgeContaoRandomGallery extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
