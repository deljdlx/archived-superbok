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


    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">





        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="vendor/mdl/material.min.css">
    <script src="vendor/mdl/material.min.js"></script>
    <link rel="stylesheet" href="asset/css/material.css">



    <script src="vendor/jquery-2.1.4.min.js"></script>


    <link rel="stylesheet" href="vendor/jstree/dist/themes/default/style.min.css" />
    <script src="vendor/jstree/dist/jstree.min.js"></script>








    <script type="text/javascript">
        $(document).ready(function () {


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












            $('#tree').jstree({
                'core' : {

                    'check_callback' : function(o, n, p, i, m) {
                        /*
                         if(m && m.dnd && m.pos !== 'i') { return false; }
                         if(o === "move_node" || o === "copy_node") {
                         if(this.get_node(n).parent === this.get_node(p).id) { return false; }
                         }
                         */

                        //prevent all modifications

                        return true;
                    },
                    'data' : {
                        "success":function(data) {
                            console.debug(data)
                        },

                        "url" : function (node) {
                            return 'action.php?action=getChildren';
                        },
                        "dataType" : "json", // needed only if you do not supply JSON headers

                        "data" : function (node) {
                            return { "nodeId" : node.id };
                        }
                    }
                },
                "plugins" : [ "dnd", "contextmenu", "foo"]
            });
            $('#tree').on("move_node.jstree", function (e, data) {
                console.debug(data.node.original);
                console.debug(data.node.id);
                console.debug(data.parent);
                $('#tree').jstree().open_node(data.parent);

            });
            $(document).on('dnd_stop.vakata', function(event, data) {
            });







        });
    </script>










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
            <a class="mdl-navigation__link" href="#"><i class="fa fa-2x fa-fw fa-tags"></i> Tags</a>

            <hr/>

            <a class="mdl-navigation__link" href=""><i class="fa fa-2x fa-fw fa-users"></i> Tags</a>




            <a class="mdl-navigation__link" href=""><i class="fa fa-2x fa-fw fa-database"></i> Système</a>

            <div class="mdl-layout-spacer"></div>
            <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>






    </div>



    <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">




            <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
                main-top
            </div>









            <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--4-col">


                <div style="border: none; " id='tree'></div>


            </div>




            <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">


                <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                    <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                        <h2 class="mdl-card__title-text">Updates</h2>
                    </div>
                    <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                        Non dolore elit adipisicing ea reprehenderit consectetur culpa.
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
                    </div>
                </div>


                <div class="demo-separator mdl-cell--1-col"></div>


                <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                    <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
                        <h2 class="mdl-card__title-text">Updates</h2>
                    </div>
                    <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                        Non dolore elit adipisicing ea reprehenderit consectetur culpa.
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
                    </div>
                </div>






                <div class="demo-separator mdl-cell--1-col"></div>



                <div class="demo-options mdl-card mdl-color--deep-purple-500 mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet mdl-cell--12-col-desktop">
                    <div class="mdl-card__supporting-text mdl-color-text--blue-grey-50">
                        <h3>View options</h3>
                        <ul>
                            <li>
                                <label for="chkbox1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                    <input type="checkbox" id="chkbox1" class="mdl-checkbox__input" />
                                    <span class="mdl-checkbox__label">Click per object</span>
                                </label>
                            </li>
                            <li>
                                <label for="chkbox2" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                    <input type="checkbox" id="chkbox2" class="mdl-checkbox__input" />
                                    <span class="mdl-checkbox__label">Views per object</span>
                                </label>
                            </li>
                            <li>
                                <label for="chkbox3" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                    <input type="checkbox" id="chkbox3" class="mdl-checkbox__input" />
                                    <span class="mdl-checkbox__label">Objects selected</span>
                                </label>
                            </li>
                            <li>
                                <label for="chkbox4" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                    <input type="checkbox" id="chkbox4" class="mdl-checkbox__input" />
                                    <span class="mdl-checkbox__label">Objects viewed</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--blue-grey-50">Change location</a>
                        <div class="mdl-layout-spacer"></div>
                        <i class="material-icons">location_on</i>
                    </div>
                </div>

            </div>



            <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
                main-bottom
            </div>


        </div>









    </main>
</div>


</body>
</html>