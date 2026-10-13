<?php

declare(strict_types=1);

/*+
 * This file is part of contao-random-gallery.
 * (c) Andreas Maier <mail@maier-digital.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/amwforge/contao-random-gallery
 */

namespace Amwforge\ContaoRandomGallery\ContaoManager;

use Amwforge\ContaoRandomGallery\AmwforgeContaoRandomGallery;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(AmwforgeContaoRandomGallery::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
