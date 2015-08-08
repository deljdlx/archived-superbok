<?php

namespace PMD\Capital\Module\Tag\View;


class Manager
{


    public function initialize() {
        return json_encode(array(
           'view'=>file_get_contents('public/application/module/tag/template/mainpanel.html'),
            'javascripts'=>array(

                'coreCodeMirror'=>array(
                    'url'=>'vendor/codemirror/lib/codemirror.js',
                ),

                'codeMirrorHighLight'=>array(
                    'url'=>'vendor/codemirror/mode/javascript/javascript.js',
                ),
                'tagTypeManager'=>array(
                    'url'=>'application/module/tag/TagTypeManager.js',
                    'callback'=>'TagTypeManager.initialize()'
                ),
                'tagManager'=>array(
                    'url'=>'application/module/tag/TagManager.js',
                    'callback'=>'TagManager.initialize()'
                ),
            ),


            'css'=>array(
                'codeMirror'=>array(
                    'url'=>'vendor/codemirror/lib/codemirror.css',
                ),
            ),
        ));

    }

}

