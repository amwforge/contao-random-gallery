<?php

declare(strict_types=1);

/**
 * This file is part of contao-random-gallery.
 *
 * (c) Andreas Maier <mail@maier-digital.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @see https://github.com/amwforge/contao-random-gallery
 */

use Amwforge\ContaoRandomGallery\Controller\ContentElement\AmwRandomGalleryController;

/**
 * Content elements
 */
 
/* Palettes */
$GLOBALS['TL_DCA']['tl_content']['palettes'][AmwRandomGalleryController::TYPE] = '
    {type_legend},type,headline,title;
    {source_legend},multiSRC;
    {amw_display_legend},amw_rg_count,fullsize,amw_rg_height,amw_rg_square,amw_rg_more;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

/* Fields */
$GLOBALS['TL_DCA']['tl_content']['fields']['amw_rg_count'] = 
[
    'label' => &$GLOBALS['TL_LANG']['tl_content']['amw_rg_count'],
    'inputType' => 'text',
    'eval' => [
        'mandatory' => true,
        'tl_class' => 'w50',
        'rgxp' => 'natural',
    ],
    'sql' => "smallint(5) unsigned NOT NULL default '0'",
];
$GLOBALS['TL_DCA']['tl_content']['fields']['amw_rg_height'] = 
[
    'label' => &$GLOBALS['TL_LANG']['tl_content']['amw_rg_height'],
    'inputType' => 'text',
    'eval' => [
        'mandatory' => true,
        'tl_class' => 'w50',
        'rgxp' => 'natural',
    ],
    'sql' => "smallint(5) unsigned NOT NULL default '0'",
];
$GLOBALS['TL_DCA']['tl_content']['fields']['amw_rg_square'] = 
[
    'label' => &$GLOBALS['TL_LANG']['tl_content']['amw_rg_square'],
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];
$GLOBALS['TL_DCA']['tl_content']['fields']['amw_rg_more'] = 
[
    'label' => &$GLOBALS['TL_LANG']['tl_content']['amw_rg_more'],
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];