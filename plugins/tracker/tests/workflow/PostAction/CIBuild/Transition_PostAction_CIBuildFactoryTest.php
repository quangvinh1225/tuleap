<?php
/**
 * Copyright (c) Enalean, 2013. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require_once dirname(__FILE__).'/../../../builders/aPostActionCIBuildFactory.php';
require_once dirname(__FILE__).'/../../../builders/aCIBuildPostAction.php';

class Transition_PostAction_CIBuildFactoryTest extends TuleapTestCase {
    
    protected $factory;
    protected $dao;

    public function setUp() {
        parent::setUp();

        $this->transition_id  = 123;
        $this->post_action_id = 789;

        $this->transition = aTransition()->withId($this->transition_id)->build();
        $this->factory = partial_mock('Transition_PostAction_CIBuildFactory', array('getDao', 'loadPostActionRows'));
        $this->dao = mock('Transition_PostAction_CIBuildDao');
        
        stub($this->factory)->getDao()->returns($this->dao);
    }

    public function itLoadsCIBuildPostActions() {
        $post_action_value = 'http://ww.myjenks.com/job';
        $post_action_rows  = array(
            array(
                'id'         => $this->post_action_id,
                'job_url'    => $post_action_value,
                )
            );

        stub($this->factory)->loadPostActionRows($this->transition)->returns($post_action_rows);

        $this->assertCount($this->factory->loadPostActions($this->transition), 1);
        
        $post_action_array = $this->factory->loadPostActions($this->transition);
        $first_pa = $post_action_array[0];
        
        $this->assertEqual($first_pa->getJobUrl(), $post_action_value);
        $this->assertEqual($first_pa->getId(), $this->post_action_id);
        $this->assertEqual($first_pa->getTransition(), $this->transition);
    }
}
?>