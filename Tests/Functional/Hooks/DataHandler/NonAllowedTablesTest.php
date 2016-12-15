<?php
namespace Leonmrni\SearchCore\Tests\Functional\Hooks\DataHandler;

/*
 * Copyright (C) 2016  Daniel Siepmann <coding@daniel-siepmann.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

use Leonmrni\SearchCore\Configuration\ConfigurationContainerInterface;
use Leonmrni\SearchCore\Domain\Service\DataHandler as DataHandlerService;
use Leonmrni\SearchCore\Hook\DataHandler as DataHandlerHook;
use TYPO3\CMS\Core\DataHandling\DataHandler as Typo3DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class NonAllowedTablesTest extends AbstractDataHandlerTest
{
    /**
     * @var DataHandlerService|\PHPUnit_Framework_MockObject_MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function getDataSets()
    {
        return array_merge(
            parent::getDataSets(),
            ['Tests/Functional/Fixtures/Hooks/DataHandler/NonAllowedTables.xml']
        );
    }

    /**
     * @test
     */
    public function deletionWillNotBeTriggeredForSysCategories()
    {
        $this->subject->expects($this->exactly(0))->method('delete');

        $tce = GeneralUtility::makeInstance(Typo3DataHandler::class);
        $tce->stripslashes_values = 0;
        $tce->start([], [
            'sys_category' => [
                '1' => [
                    'delete' => 1,
                ],
            ],
        ]);
        $tce->process_cmdmap();
    }

    /**
     * @test
     */
    public function updateWillNotBeTriggeredForSysCategory()
    {
        $this->subject->expects($this->exactly(0))->method('update');

        $tce = GeneralUtility::makeInstance(Typo3DataHandler::class);
        $tce->stripslashes_values = 0;
        $tce->start([
            'sys_category' => [
                '1' => [
                    'title' => 'something new',
                ],
            ],
        ], []);
        $tce->process_datamap();
    }

    /**
     * @test
     */
    public function addWillNotBeTriggeredForSysCategoy()
    {
        $this->subject->expects($this->exactly(0))->method('add');

        $tce = GeneralUtility::makeInstance(Typo3DataHandler::class);
        $tce->stripslashes_values = 0;
        $tce->start([
            'sys_category' => [
                'NEW_1' => [
                    'pid' => 1,
                    'title' => 'a new record',
                ],
            ],
        ], []);

        $tce->process_datamap();
    }
}
