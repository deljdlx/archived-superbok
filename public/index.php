<?php
include('../bootstrap.php');
?>


<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Design Lite</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!--<link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">//-->

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">






    <script src="vendor/webcomponents.min.js"></script>

    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">


    <link rel="stylesheet" href="vendor/mdl/material.min.css"></link>
    <script src="vendor/mdl/material.min.js"></script>
    <link rel="stylesheet" href="asset/css/material.css"></link>



    <script src="vendor/jquery-2.1.4.min.js"></script>




    <link href="vendor/froala_editor/css/froala_editor.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/froala_editor/css/froala_style.min.css" rel="stylesheet" type="text/css">

    <script src="vendor/froala_editor/js/froala_editor.min.js"></script>
    <!--[if lt IE 9]>
    <script src="vendor/froala_editor/js/froala_editor_ie8.min.js"></script>
    <![endif]-->
    <script src="vendor/froala_editor/js/plugins/tables.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/lists.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/char_counter.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/colors.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/font_family.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/font_size.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/block_styles.min.js"></script>
    <script src="vendor/froala_editor/js/plugins/video.min.js"></script>



    <script src="vendor/line-control-master/editor.js"></script>



    <script src="application/Application.js"></script>
    <script src="application/Module.js"></script>




    <script src="vendor/focus-point-picker/bower_components/cropper/dist/cropper.js"></script>
    <script src="vendor/focus-point-picker/src/js/focusPointPicker.js"></script>



    <script src="asset/webcomponent/pmd/form/image/component.js"></script>
    <script src="asset/webcomponent/pmd/form/text/component.js"></script>




    <!--
        <script src="vendor/codemirror/lib/codemirror.js"></script>
        <script src="vendor/codemirror/mode/javascript/javascript.js"></script>
    //-->







    <script>







    </script>





    <script type="text/javascript">




        jQuery(function() {


            var backoffice=new Application();
            backoffice.start();
            //backoffice.loadModule('tag');
            //TagTypeManager.initialize();


            /*

            $.jstree.plugins.foo = function (options, parent) {


                this.redraw_node = function(obj, deep, callback, force_draw) {



                    var span = document.createElement('SPAN');
                    var i, j, tmp = null, elm = null, org = 2;




                    obj = parent.redraw_node.call(this, obj, deep, callback, force_draw);

                    console.debug(obj);



                    if(obj) {
                        for(i = 0, j = obj.childNodes.length; i < j; i++) {
                            if(obj.childNodes[i] && obj.childNodes[i].className && obj.childNodes[i].className.indexOf("jstree-anchor") !== -1) {
                                tmp = obj.childNodes[i];
                                break;
                            }
                        }
                        if(tmp) {
                            elm = span.cloneNode(true);
                            elm.innerHTML = org + '. ';
                            tmp.insertBefore(elm, tmp.childNodes[tmp.childNodes.length - 1]);
                        }
                    }
                    return obj;
                };
            };
            */





        });
    </script>




    <style>

       .jstree-icon.tag-category:before {
           content: "\f07b";
       }
       .jstree-icon.tag-company:before {
           content: "\f275";
       }
       .jstree-icon.tag-person:before {
           content: "\f007 ";
       }
       .jstree-icon.tag-content:before {
           content: "\f02b  ";
       }


       .jstree-contextmenu {
           z-index:1000;
       }





       .mdl-card__supporting-text {
           width:100%;
           box-sizing: border-box;
           /*height: 450px;*/
       }

       .CodeMirror {
           width:100%;
           height: 100% !important;
       }




       main {
           height: calc(100% - 80px);
       }

       .mdl-grid.demo-content {
           position: relative;
           /*background-color:#F00;*/
           height: 100%;
       }

        .demo-graphs {
            height:calc(100% - 20px);
            /*height: 100% !important;*/
            overflow: auto;
        }

    </style>






</head>
<body>
<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--white mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title">Home</span>
            <div class="mdl-layout-spacer"></div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
                    <i class="material-icons">search</i>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <input class="mdl-textfield__input" type="text" id="search" />
                    <label class="mdl-textfield__label" for="search">Enter your query...</label>
                </div>
            </div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
                <li class="mdl-menu__item">About</li>
                <li class="mdl-menu__item">Contact</li>
                <li class="mdl-menu__item">Legal information</li>
            </ul>
        </div>
    </header>



    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
            <img src="images/user.jpg" class="demo-avatar">
            <div class="demo-avatar-dropdown">
                <span>hello@example.com</span>
                <div class="mdl-layout-spacer"></div>
                <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                    <i class="material-icons" role="presentation">arrow_drop_down</i>
                    <span class="visuallyhidden">Accounts</span>
                </button>
                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                    <li class="mdl-menu__item">hello@example.com</li>
                    <li class="mdl-menu__item">info@example.com</li>
                    <li class="mdl-menu__item"><i class="material-icons">add</i>Add another account...</li>
                </ul>
            </div>
        </header>





        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">



            <a class="mdl-navigation__link" href="#"><i class="fa fa-2x fa-fw fa-home"></i> Accueil</a>
            <a class="mdl-navigation__link" href="#"><i class="fa fa-2x fa-fw fa-newspaper-o"></i> Contenus</a>
            <a class="mdl-navigation__link" href="#"><i class="fa fa-2x fa-fw fa-industry"></i> Entreprises</a>
            <a class="mdl-navigation__link" href="#module=Tag&action=TagTypeManager.initialize"><i class="fa fa-2x fa-fw fa-tags"></i> Tags</a>

            <hr/>

            <a class="mdl-navigation__link" href=""><i class="fa fa-2x fa-fw fa-users"></i> Utilisateurs</a>




            <a class="mdl-navigation__link" href=""><i class="fa fa-2x fa-fw fa-database"></i> Système</a>


        </nav>
    </div>



    <main class="mdl-layout__content mdl-color--grey-100 mainPanel">

<!--
<div class="testMe">hello</div>
<script>
    $('.testMe').editable({
        minHeight: 400,
        inlineMode: false,
        multiLine: true
    })
</script>
//-->


    </main>
</div>


<script>



</script>



</body>
</html>