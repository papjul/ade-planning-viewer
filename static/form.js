/** 
 * Planning IUT Info
 * Copyright © 2012-2013 Julien Papasian
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Fonctions de formulaire
 */

// Envoi du formulaire
function submitForm()
{
  document.getElementById('img_planning').src = 'img/loading.gif';

  var perconf = { };
  perconf.idTree        = new Array();
  perconf.idPianoWeek   = document.getElementById('idPianoWeek').value;
  perconf.saturday      = 'no';
  perconf.sunday        = 'no';
  perconf.displayConfId = document.getElementById('displayConfId').value;
  perconf.width         = document.getElementById('width').value;
  var height            = dimensions[parseInt(perconf.width)];
  var idPianoDay        = '0,1,2,3,4';

  if(document.getElementById('saturday').checked == true)
  {
    perconf.saturday = 'yes';
    idPianoDay += ',5';
  }
  if(document.getElementById('sunday').checked == true)
  {
    perconf.sunday = 'yes';
    idPianoDay += ',6';
  }

  var elmt = document.getElementById('idTree');
  for(var i = 0, j = 0; i < elmt.options.length; ++i)
  {
    if(elmt.options[i].selected == true)
    {
      perconf.idTree[j] = elmt.options[i].value;
      ++j;
    }
  }

  if(perconf.idTree == '') perconf.idTree[0] = 0;

  // Envoi du cookie
  var today = new Date();
  today.setTime(today.getTime() + 365 * 24 * 3600);
  var expires = "; expires=" + today.toGMTString();
  document.cookie = conf.COOKIE_NAME+"="+encodeURIComponent(JSON.stringify(perconf))+expires+"; path=/";

  // Export iCal
  document.getElementById('resources').innerHTML = perconf.idTree;

  // Traitement de l’image
  img_planning = new Image();
  
  if(idTree != '')
  {
    var url = conf.URL_ADE + "/imageEt?identifier=" + identifier + "\&projectId=" + conf.PROJECT_ID + "\&idPianoWeek=" + perconf.idPianoWeek + "\&idPianoDay=" + idPianoDay + "\&idTree=" + perconf.idTree + "\&width=" + perconf.width + "\&height=" + height + "\&lunchName=REPAS\&displayMode=1057855\&showLoad=false\&ttl=" + today.getTime() + "000\&displayConfId=" + perconf.displayConfId;
  }
  else
  {
    var url = 'img/bgExpertBlanc.gif';
  }

  img_planning.onload = function() {
    document.getElementById('img_planning').src = url;
  }
  img_planning.src = url;
}

// Sélectionne plusieurs groupes à la fois
function selectOptionsByOptgroup(event)
{
  var options = event.target.getElementsByTagName("option");
  var len = options.length;
  for(var i = len - 1; i >= 0; --i)
  {
    options[i].selected = 'selected';
  }
  update_groups();
}

// Bouton Semaine précédente
function go_previous_week(event)
{
  stopEvent(event);
  document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) - 1;
  submitForm();
}

// Bouton Semaine suivante
function go_next_week(event)
{
  stopEvent(event);
  document.getElementById('idPianoWeek').selectedIndex = parseInt(document.getElementById('idPianoWeek').selectedIndex) + 1;
  submitForm();
}

// Stoppe la propagation d’un événement
function stopEvent(event)
{
  if(event.stopPropagation) {
    event.stopPropagation();
  }
  else {
    event.cancelBubble = true;
  }

  if(event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}