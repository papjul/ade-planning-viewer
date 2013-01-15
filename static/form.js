/** 
 * Planning IUT Info
 * Copyright © 2012 Julien Papasian
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

  var idPianoWeek   = document.getElementById('idPianoWeek').value;
  var idPianoDay    = '0,1,2,3,4';
  var idTree        = new Array();
  var width         = document.getElementById('width').value;
  var height        = dimensions[parseInt(width)];
  var displayConfId = document.getElementById('displayConfId').value;
  var today         = new Date();

  var elmt = document.getElementById('idTree');
  var j = 0;
  for(var i = 0; i < elmt.options.length; ++i)
  {
    if(elmt.options[i].selected == true)
    {
      idTree[j] = elmt.options[i].value;
      ++j;
    }
  }

  if(document.getElementById('saturday').checked == true) idPianoDay += ',5';
  if(document.getElementById('sunday').checked == true) idPianoDay += ',6';

  img_planning = new Image();
  
  if(idTree != '')
  {
    var url = conf.URL_ADE + "/imageEt?identifier=" + identifier + "\&projectId=" + conf.PROJECT_ID + "\&idPianoWeek=" + idPianoWeek + "\&idPianoDay=" + idPianoDay + "\&idTree=" + idTree + "\&width=" + width + "\&height=" + height + "\&lunchName=REPAS\&displayMode=1057855\&showLoad=false\&ttl=" + today.getTime() + "000\&displayConfId=" + displayConfId;
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

// Permet d’envoyer en cookie le nouveau groupe sélectionné
function update_groups()
{
  var idTree = new Array();
  var elmt = document.getElementById('idTree');
  var j = 0;
  for(var i = 0; i < elmt.options.length; ++i)
  {
    if(elmt.options[i].selected == true)
    {
      idTree[j] = elmt.options[i].value;
      ++j;
    }
  }

  if(idTree != '')
  {
    var date = new Date();
    date.setTime(date.getTime() + 365 * 24 * 3600);
    var expires = "; expires=" + date.toGMTString();

    document.cookie = "idTree="+idTree+expires+"; path=/";
  }

  submitForm();
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