<?php
/**
 * Copyright (c) 2012 Soflomo http://soflomo.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     Ensemble\Admin
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Soflomo http://soflomo.com.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://ensemble.github.com
 */

namespace SlmCmfAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use SlmCmfKernel\Service\PageInterface as PageService;
use SlmCmfKernel\Model\PageInterface;
use SlmCmfKernel\Model\PageCollectionInterface;

/**
 * Description of PageTree
 */
class PageTree extends AbstractHelper
{
    /**
     * @var PageService
     */
    protected $service;

    public function setPageService(PageService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        return $this;
    }

    public function __toString()
    {
        $pages = $this->service->getTree();
        $html  = $this->parseCollection($pages);

        return $html;
    }

    protected function parseCollection(PageCollectionInterface $collection)
    {
        $html = '<ul>';
        foreach($collection as $page) {
            $html .= $this->parsePage($page);
        }
        $html .= '</ul>';

        return $html;
    }

    protected function parsePage(PageInterface $page)
    {
        $title = $page->getMetaData()->getTitle();
        $url   = $this->getView()->url('admin/page/open', array(
            'id' => $page->getId())
        );

        $children = '';
        if ($page->hasChildren()) {
            $collection = $page->getChildren();
            $children   = $this->parseCollection($collection);
        }

        $html = sprintf('<li><a href="%s">%s</a>%s</li>',
                        $url,
                        $title,
                        $children);

        return $html;
    }
}