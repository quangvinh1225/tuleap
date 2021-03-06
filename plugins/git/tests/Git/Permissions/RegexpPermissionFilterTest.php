<?php
/**
 * Copyright (c) Enalean, 2016. All Rights Reserved.
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
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tuleap\Git\Permissions;

use TuleapTestCase;
use User_ForgeUGroup;
use GitRepository;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class RegexpPermissionFilterTest extends TuleapTestCase
{
    /**
     * @var RegexpPermissionFilter
     */
    private $permission_filter;

    /**
     * @var FineGrainedPermissionFactory
     */
    private $permission_factory;

    /**
     * @var GitRepository
     */
    private $repository;

    /**
     * @var FineGrainedPermissionDestructor
     */
    private $permission_destructor;

    public function setUp()
    {
        parent::setUp();

        $this->repository = mock('GitRepository');
        stub($this->repository)->getId()->returns(1);

        $this->permission_factory    = mock('Tuleap\Git\Permissions\FineGrainedPermissionFactory');
        $this->permission_destructor = mock('Tuleap\Git\Permissions\FineGrainedPermissionDestructor');
        $this->permission_filter     = new RegexpPermissionFilter(
            $this->permission_factory,
            new PatternValidator(
                new FineGrainedPatternValidator(),
                mock('Tuleap\Git\Permissions\FineGrainedRegexpValidator'),
                mock('Tuleap\Git\Permissions\RegexpFineGrainedRetriever')
            ),
            $this->permission_destructor,
            mock('Tuleap\Git\Permissions\DefaultFineGrainedPermissionFactory')
        );
    }

    public function itShouldKeepOnlyNonRegexpPattern()
    {
        $patterns = $this->buildPatterns();

        stub($this->permission_factory)->getBranchesFineGrainedPermissionsForRepository()->returns($patterns);
        stub($this->permission_factory)->getTagsFineGrainedPermissionsForRepository()->returns(array());
        $this->permission_destructor->expectCallCount('deleteRepositoryPermissions', 16);

        $this->permission_filter->filterNonRegexpPermissions($this->repository);
    }

    private function buildPatterns()
    {
        $patterns = array(
            '*',
            '/*',
            'master',
            'master*',
            'master/*',
            'master/*/*',
            'master/dev',
            'master/dev*',
            'master*/dev',
            '',
            'master*[dev',
            'master dev',
            'master?dev',

            "master\n",
            "master\r",
            "master\n\r",
            "master\ndev",
            "\n",
            "\v",
            "\f"
        );

        $built_pattern = array();

        foreach ($patterns as $key => $pattern) {
            $built_pattern[] = new FineGrainedPermission(
                $key,
                $this->repository->getId(),
                $pattern,
                array(User_ForgeUGroup::PROJECT_ADMINS),
                array(User_ForgeUGroup::PROJECT_MEMBERS)
            );
        }

        return $built_pattern;
    }
}
