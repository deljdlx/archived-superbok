<?php

namespace PMD\Capital\Module\Tag\View;


class Manager
{


    public function initialize() {
        return json_encode(array(
           'view'=>file_get_contents('public/application/module/tag/template/mainpanel.html'),
            'javascripts'=>array(
                'tagTypeManager'=>array(
                    'url'=>'application/module/tag/TagTypeManager.js',
                    'callback'=>'TagTypeManager.initialize()'
                ),
            ),
            'css'=>array(),
        ));

    }

}

