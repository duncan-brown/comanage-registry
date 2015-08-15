<!--
/**
 * COmanage Registry CO Enrollment Flow Index View
 *
 * Copyright (C) 2011-15 University Corporation for Advanced Internet Development, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2011-15 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.3
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */-->
<?php
  // Add breadcrumbs
  print $this->element("coCrumb");
  $this->Html->addCrumb(_txt('ct.co_enrollment_flows.pl'));

  // Add page title
  $params = array();
  $params['title'] = $title_for_layout;

  // Add top links
  $params['topLinks'] = array();

  if($permissions['add']) {
    $params['topLinks'][] = $this->Html->link(
      _txt('op.add-a', array(_txt('ct.co_enrollment_flows.1'))),
      array(
        'controller' => 'co_enrollment_flows',
        'action' => 'add',
        'co' => $cur_co['Co']['id']
      ),
      array('class' => 'addbutton')
    );
    $params['topLinks'][] = $this->Html->link(
      _txt('op.restore.ef'),
      array(
        'controller' => 'co_enrollment_flows',
        'action' => 'addDefaults',
        'co' => $cur_co['Co']['id']
      ),
      array('class' => 'addbutton')
    );
  }

  print $this->element("pageTitleAndButtons", $params);

?>

<table id="cous" class="ui-widget">
  <thead>
    <tr class="ui-widget-header">
      <th><?php print $this->Paginator->sort('name', _txt('fd.name')); ?></th>
      <th><?php print $this->Paginator->sort('status', _txt('fd.status')); ?></th>
      <th><?php print $this->Paginator->sort('authz_level', _txt('fd.ef.authz')); ?></th>
      <th><?php print _txt('fd.actions'); ?></th>
    </tr>
  </thead>
  
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($co_enrollment_flows as $c): ?>
    <tr class="line<?php print ($i % 2)+1; ?>">
      <td>
        <?php
          print $this->Html->link($c['CoEnrollmentFlow']['name'],
                                  array('controller' => 'co_enrollment_flows',
                                        'action' => ($permissions['edit'] ? 'edit' : ($permissions['view'] ? 'view' : '')), $c['CoEnrollmentFlow']['id'], 'co' => $this->request->params['named']['co']));
        ?>
      </td>
      <td><?php print _txt('en.status.ef', null, $c['CoEnrollmentFlow']['status']); ?></td>
      <td>
        <?php
          print _txt('en.enrollment.authz', null, $c['CoEnrollmentFlow']['authz_level']);
          
          if($c['CoEnrollmentFlow']['authz_level'] == EnrollmentAuthzEnum::CoGroupMember) {
            print " ("
                  . $this->Html->link($c['CoEnrollmentFlowAuthzCoGroup']['name'],
                                      array(
                                       'controller' => 'co_groups',
                                       'action' => 'view',
                                       $c['CoEnrollmentFlow']['authz_co_group_id']
                                      ))
                  . ")";
          }
          
          if($c['CoEnrollmentFlow']['authz_level'] == EnrollmentAuthzEnum::CouAdmin
             || $c['CoEnrollmentFlow']['authz_level'] == EnrollmentAuthzEnum::CouPerson) {
            print " ("
                  . $this->Html->link($c['CoEnrollmentFlowAuthzCou']['name'],
                                      array(
                                       'controller' => 'cous',
                                       'action' => 'view',
                                       $c['CoEnrollmentFlow']['authz_cou_id']
                                      ))
                  . ")";
          }
        ?>
      </td>
      <td>
        <?php
          if($permissions['select']
             && $c['CoEnrollmentFlow']['status'] == EnrollmentFlowStatusEnum::Active) {
            print $this->Html->link(_txt('op.begin'),
                                    array(
                                      'controller' => 'co_petitions',
                                      'action' => 'start',
                                      'coef' => $c['CoEnrollmentFlow']['id']
                                    ),
                                    array('class' => 'forwardbutton')) . "\n";
          }
          
          if($permissions['edit']) {
            print $this->Html->link(_txt('op.edit'),
                                    array('controller' => 'co_enrollment_flows', 'action' => 'edit', $c['CoEnrollmentFlow']['id']),
                                    array('class' => 'editbutton')) . "\n";
          }
          
          if($permissions['duplicate']) {
            print $this->Html->link(_txt('op.dupe'),
                                    array('controller' => 'co_enrollment_flows', 'action' => 'duplicate', $c['CoEnrollmentFlow']['id']),
                                    array('class' => 'copybutton')) . "\n";
          }
          
          if($permissions['delete']) {
            print '<button class="deletebutton" title="' . _txt('op.delete') . '" onclick="javascript:js_confirm_delete(\'' . _jtxt(Sanitize::html($c['CoEnrollmentFlow']['name'])) . '\', \'' . $this->Html->url(array('controller' => 'co_enrollment_flows', 'action' => 'delete', $c['CoEnrollmentFlow']['id'])) . '\')";>' . _txt('op.delete') . '</button>';
          }
        ?>
        <?php ; ?>
      </td>
    </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
  
  <tfoot>
    <tr class="ui-widget-header">
      <th colspan="4">
        <?php print $this->Paginator->numbers(); ?>
      </th>
    </tr>
  </tfoot>
</table>
