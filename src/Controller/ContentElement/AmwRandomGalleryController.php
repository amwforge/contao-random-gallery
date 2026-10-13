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

namespace Amwforge\ContaoRandomGallery\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\FilesModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(category: 'media')]
class AmwRandomGalleryController extends AbstractContentElementController
{
    public const TYPE = 'amw_random_gallery';

    private $template, $studio, $count=0;

    public function __construct(Studio $studio)
    {
        $this->studio = $studio;
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $multiSrc = $model->multiSRC != '' ? StringUtil::deserialize($model->multiSRC) : [];
        $imgCount = $model->amw_rg_count != '' ? $model->amw_rg_count : 0;
        $imgHeight = $model->amw_rg_height > 0 ? $model->amw_rg_height : 300;
        $imgWidth = $model->amw_rg_square == true ? $imgHeight : 'auto';
        $imgFullsize = $model->fullsize;

        if (isset($request->request) && $request->isXmlHttpRequest() && $request->query->get('ajax') == 'AmwRandomGallery')
        {
            // Ajax-Request - Mehr Bilder anzeigen
            $action = $request->query->get('action');
            $arrOptions = [
                'multiSrc'      => $multiSrc,
                'count'         => $imgCount,
                'imgHeight'     => $imgHeight,
                'imgWidth'      => $imgWidth,
                'imgFullsize'   => $imgFullsize,
                'galid'         => $model->id,
            ];

            $return['images'] = $this->getMoreImages($arrOptions);
            echo json_encode($return);
            die();
        }
        else {
            // Galerie anzeigen
            $images = $this->getRandomImages($multiSrc, $imgCount);
            $moreImages = ($this->count > $imgCount) ? $model->amw_rg_more : false;

            $template->set('imgHeight', $imgHeight);
            $template->set('imgWidth', $imgWidth);
            $template->set('images', $images);
            $template->set('enableLightbox', $imgFullsize);
            $template->set('galid', $model->id);
            $template->set('moreImages', $moreImages);

            return $template->getResponse();
        }
    }

    public function getRandomImages($multiSrc, $imgCount)
    {
        $galleryImages = [];
        $images = $this->getAllImages($multiSrc);
        $this->count = count($images);
        $qty = $imgCount <= count($images) ? $imgCount : count($images);

        $keys = array_rand($images, $qty);
        foreach ($keys as $i) {
            $galleryImages[] = $images[$i];
        }

        return $galleryImages;
    }

    private function getAllImages($multiSrc)
    {
        $images = [];

        foreach ($multiSrc as $uuid) {
            $objItem = FilesModel::findByUuid($uuid);

            if ($objItem === null) {
                continue;
            }

            if ($objItem->type === 'folder') {
                $objFiles = FilesModel::findBy('pid', $objItem->uuid);

                if ($objFiles !== null) {
                    while ($objFiles->next()) {
                        $objFile = $objFiles->current();

                        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $objFile->name)) {
                            $images[] = $objFile;
                        }
                    }
                }
            } else {
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $objItem->name)) {
                    $images[] = $objFile;
                }
            }   
        }

        shuffle($images);
        return $images;
    }
    
    public function getMoreImages($options)
    {
        $galleryImages = [];
        $multiSrc = StringUtil::deserialize($options['multiSrc']);
        $images = $this->getAllImages($multiSrc);
        $keys = array_rand($images, $options['count']);

        foreach ($keys as $i) { 
            $img = $images[$i];
            $figureBuilder = $this->studio->createFigureBuilder();
            $figureBuilder
                ->fromUuid($img->uuid)
                ->setSize([$options['imgWidth'], $options['imgHeight'], 'crop'])
                ->setLinkHref($img->link);

            if ($options['imgFullsize']) {
                $figureBuilder
                    ->enableLightbox()
                    ->setLightboxGroupIdentifier('randomGallery');
            }

            $figure = $figureBuilder->build();
            $twig = $this->container->get('twig');
            $galleryImages[] = $twig->render('@Contao/component/_figure.html.twig', ['figure' => $figure]);
        }

        return $galleryImages;
    }
}
