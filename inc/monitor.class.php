<?php

/*
 * @version $Id$
 -------------------------------------------------------------------------
 pdf - Export to PDF plugin for GLPI
 Copyright (C) 2003-2012 by the pdf Development Team.

 https://forge.indepnet.net/projects/pdf
 -------------------------------------------------------------------------

 LICENSE

 This file is part of pdf.

 pdf is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 pdf is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with pdf. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
*/

// Original Author of file: Remi Collet
// ----------------------------------------------------------------------

class PluginPdfMonitor extends PluginPdfCommon {


   function __construct(CommonGLPI $obj=NULL) {

      $this->obj = ($obj ? $obj : new Monitor());
   }



   function defineAllTabs($options=array()) {

      $onglets = parent::defineAllTabs($options);
      unset($onglets['Item_Problem$1']); // TODO add method to print linked Problems
      return $onglets;
   }


   static function pdfMain(PluginPdfSimplePDF $pdf, Monitor $item) {
      global $LANG;

      PluginPdfCommon::mainTitle($pdf, $item);

      PluginPdfCommon::mainLine($pdf, $item, 'name-status');
      PluginPdfCommon::mainLine($pdf, $item, 'location-type');
      PluginPdfCommon::mainLine($pdf, $item, 'tech-manufacturer');
      PluginPdfCommon::mainLine($pdf, $item, 'group-model');
      PluginPdfCommon::mainLine($pdf, $item, 'usernum-serial');
      PluginPdfCommon::mainLine($pdf, $item, 'user-otherserial');


      $pdf->displayLine(
         '<b><i>'.sprintf(__('%1$s: %2$s'), __('User').'</i></b>',
                          getUserName($item->fields['users_id'])),
         '<b><i>'.$LANG['peripherals'][33].' :</i></b> '.
               ($item->fields['is_global']?$LANG['peripherals'][31]:$LANG['peripherals'][32]));

      $pdf->displayLine(
         '<b><i>'.sprintf(__('%1$s: %2$s'), __('Group').'</i></b>',
                          Html::clean(Dropdown::getDropdownName('glpi_groups',
                                                                $item->fields['groups_id']))),
         '<b><i>'.$LANG['monitors'][21].' :</i></b> '.$item->fields['size'].'"');

      $opts = array(
         'have_micro'         => $LANG['monitors'][14],
         'have_speaker'       => $LANG['monitors'][15],
         'have_subd'          => $LANG['monitors'][19],
         'have_bnc'           => $LANG['monitors'][20],
         'have_dvi'           => $LANG['monitors'][32],
         'have_pivot'         => $LANG['monitors'][33],
         'have_hdmi'          => $LANG['monitors'][34],
         'have_displayport'   => $LANG['monitors'][31],
      );
      foreach ($opts as $key => $val) {
         if (!$item->fields[$key]) {
            unset($opts[$key]);
         }
      }
      $pdf->setColumnsSize(100);
      $pdf->displayLine(
         '<b><i>'.$LANG['monitors'][18].' : </i></b>'.
            (count($opts) ? implode(', ',$opts) : $LANG['job'][32]));

      PluginPdfCommon::mainLine($pdf, $item, 'comment');

      $pdf->displaySpace();
   }


   static function displayTabContentForPDF(PluginPdfSimplePDF $pdf, CommonGLPI $item, $tab) {

      switch ($tab) {
          case 'Computer_Item$1' :
            PluginPdfComputer_Item::pdfForItem($pdf, $item);
            break;

         default :
            return false;
      }
      return true;
   }
}