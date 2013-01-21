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
 * Gestion des écouteurs
 */

// Sélecteur de groupe
var select_idTree = document.getElementById('idTree');
if(select_idTree)
{
  if(select_idTree.addEventListener)
  {
    select_idTree.addEventListener('change', update_groups, false);
  }
  else
  {
    select_idTree.attachEvent('onchange', update_groups);
  }
}

// Sélecteur de semaine
var select_idPianoWeek = document.getElementById('idPianoWeek');
if(select_idPianoWeek)
{
  if(select_idPianoWeek.addEventListener)
  {
    select_idPianoWeek.addEventListener('change', submitForm, false);
  }
  else
  {
    select_idPianoWeek.attachEvent('onchange', submitForm);
  }
}

// Bouton de semaine précédente
var button_previous_week = document.getElementById('previous_week');
if(button_previous_week)
{
  if(button_previous_week.addEventListener)
  {
    button_previous_week.addEventListener('click', go_previous_week, false);
  }
  else
  {
    button_previous_week.attachEvent('onclick', go_previous_week);
  }
}

// Bouton de semaine suivante
var button_next_week = document.getElementById('next_week');
if(button_next_week)
{
  if(button_next_week.addEventListener)
  {
    button_next_week.addEventListener('click', go_next_week, false);
  }
  else
  {
    button_next_week.attachEvent('onclick', go_next_week);
  }
}

// Checkbox de samedi
var input_saturday = document.getElementById('saturday');
if(input_saturday)
{
  if(input_saturday.addEventListener)
  {
    input_saturday.addEventListener('change', submitForm, false);
  }
  else
  {
    input_saturday.attachEvent('onchange', submitForm);
  }
}

// Checkbox de dimanche
var input_sunday = document.getElementById('sunday');
if(input_sunday)
{
  if(input_sunday.addEventListener)
  {
    input_sunday.addEventListener('change', submitForm, false);
  }
  else
  {
    input_sunday.attachEvent('onchange', submitForm);
  }
}

// Sélecteur d’affichage
var select_displayConfId = document.getElementById('displayConfId');
if(select_displayConfId)
{
  if(select_displayConfId.addEventListener)
  {
    select_displayConfId.addEventListener('change', submitForm, false);
  }
  else
  {
    select_displayConfId.attachEvent('onchange', submitForm);
  }
}

// Sélecteur de dimensions
var select_width = document.getElementById('width');
if(select_width)
{
  if(select_width.addEventListener)
  {
    select_width.addEventListener('change', submitForm, false);
  }
  else
  {
    select_width.attachEvent('onchange', submitForm);
  }
}

// Image
var img_planning = document.getElementById('img_planning');
if(img_planning)
{
  img_planning.onerror = function () {
    img_planning.onerror = null;
    img_planning.src = 'img/error.png';
  }
  img_planning.onabort = function () {
    img_planning.src = 'img/error.png';
  }
}