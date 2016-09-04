/**
 * This file is part of ADE Planning Viewer.
 * Copyright © 2012-2015 Julien Papasian
 *
 * ADE Planning Viewer is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License
 * as published by Affero; either version 3 of the License, or (at
 * your option) any later version.
 *
 * ADE Planning Viewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License along with this program. If not, see
 * <https://www.gnu.org/licenses/agpl-3.0.html>.
 */

/**
 * Fonctions de formulaire
 */

// Envoi du formulaire
function submitForm() {
    document.getElementById('img_planning').src = 'img/loading.gif';
    document.getElementById('url').style.display = 'none';

    var perconf = {};
    perconf.idTree = new Array();
    perconf.idPianoWeek = document.getElementById('idPianoWeek').value;
    perconf.saturday = 'no';
    perconf.sunday = 'no';
    perconf.displayConfId = document.getElementById('displayConfId').value;
    perconf.width = document.getElementById('width').value;
    var height = dimensions[parseInt(perconf.width)];
    var idPianoDay = '0,1,2,3,4';

    if (document.getElementById('saturday').checked == true) {
        perconf.saturday = 'yes';
        idPianoDay += ',5';
    }
    if (document.getElementById('sunday').checked == true) {
        perconf.sunday = 'yes';
        idPianoDay += ',6';
    }

    var elmt = document.getElementById('idTree');
    for (var i = 0, j = 0; i < elmt.options.length; ++i) {
        if (elmt.options[i].selected == true) {
            perconf.idTree[j] = elmt.options[i].value;
            ++j;
        }
    }

    // Envoi du cookie
    var today = new Date();
    today.setTime(today.getTime() + 365 * 24 * 3600);
    var expires = "; expires=" + today.toGMTString();
    document.cookie = conf.COOKIE_NAME + "=" + encodeURIComponent(JSON.stringify(perconf)) + expires + "; path=/";

    // Export iCal
    var button = document.getElementById('genbutton');

    // Traitement de l’image
    if (perconf.idTree != '') {
        var url = conf.URL_ADE + "/imageEt?identifier=" + identifier + "\&projectId=" + conf.PROJECT_ID + "\&idPianoWeek=" + perconf.idPianoWeek + "\&idPianoDay=" + idPianoDay + "\&idTree=" + perconf.idTree + "\&width=" + perconf.width + "\&height=" + height + "\&lunchName=REPAS\&displayMode=1057855\&showLoad=false\&ttl=" + today.getTime() + today.getMilliseconds() + "\&displayConfId=" + perconf.displayConfId;
        button.style.display = 'inline';
        document.getElementById('resources').innerHTML = perconf.idTree;
    } else {
        var url = 'img/bgAdeBlanc.png';
        button.style.display = 'none';
    }

    loadImage(url);
    //loadIframe(url, perconf.width, height);
}

function loadImage(url) {
    var img_planning = new Image();
    img_planning.addEventListener('load', function () {
        document.getElementById('href_planning').href = url;
        document.getElementById('img_planning').src = url;
    });
    img_planning.addEventListener('error', function () {
        document.getElementById('href_planning').href = url;
        document.getElementById('img_planning').src = 'img/error.png';
        //document.getElementById('img_planning').src = url;
        document.getElementById('href_planning').click();
        //var win = window.open(url, '_blank');
        //win.focus();
    });
    img_planning.src = url;
}

/*function loadIframe(url, width, height) {
    document.getElementById('iframe_planning').width = width;
    document.getElementById('iframe_planning').height = height;
    document.getElementById('iframe_planning').src = url;
}*/

// Bouton Semaine précédente
function go_previous_week(event) {
    stopEvent(event);
    document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) - 1;
    submitForm();
}

// Bouton Semaine suivante
function go_next_week(event) {
    stopEvent(event);
    document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) + 1;
    submitForm();
}

// Afficher l’URL iCal
function showiCal(event) {
    stopEvent(event);
    document.getElementById('url').style.display = 'block';
}

// Stoppe la propagation d’un événement
function stopEvent(event) {
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }

    if (event.preventDefault) {
        event.preventDefault();
    } else {
        event.returnValue = false;
    }
}
