<?php

namespace PMD\Capital\Module\Tag\View;


class Manager
{


    public function initialize() {
        return json_encode(array(
           'views'=>array(
               'tagTypeManagerLayout'=>file_get_contents('public/application/module/Tag/template/tagtypemanager.mainpanel.html'),
               'tagManagerLayout'=>file_get_contents('public/application/module/Tag/template/tagmanager.mainpanel.html'),
           ),
            'javascripts'=>array(

                'jsTree'=>array(
                   'url'=>'vendor/jstree/dist/jstree.min.js',
                ),
                'coreCodeMirror'=>array(
                    'url'=>'vendor/codemirror/lib/codemirror.js',
                ),
                'codeMirrorHighLight'=>array(
                    'url'=>'vendor/codemirror/mode/javascript/javascript.js',
                ),
                'tagTypeManager'=>array(
                    'url'=>'application/module/Tag/TagTypeManager.js',
                    'callback'=>'TagTypeManager.initialize()'
                ),
                'tagManager'=>array(
                    'url'=>'application/module/Tag/TagManager.js',
                    'callback'=>'TagManager.initialize()'
                ),
            ),
            'css'=>array(
                'codeMirror'=>array(
                    'url'=>'vendor/codemirror/lib/codemirror.css',
                ),
                'jsTree'=>array(
                    'url'=>'vendor/jstree/dist/themes/default/style.min.css',
                )
            ),
        ));

    }

}

