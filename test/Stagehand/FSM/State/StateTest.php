<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5.3
 *
 * Copyright (c) 2013 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Stagehand_FSM
 * @copyright  2013 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://opensource.org/licenses/BSD-2-Clause  The BSD 2-Clause License
 * @version    Release: @package_version@
 * @since      File available since Release 2.0.0
 */

namespace Stagehand\FSM\State;

use Stagehand\FSM\Event\TransitionEvent;

/**
 * @package    Stagehand_FSM
 * @copyright  2013 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://opensource.org/licenses/BSD-2-Clause  The BSD 2-Clause License
 * @version    Release: @package_version@
 * @since      Class available since Release 2.0.0
 */
class StateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function raisesAnExceptionIfTheEventIsAlreadyDefinedWhenAddingAnEvent()
    {
        $state = new State('foo');
        $state->addTransitionEvent(new TransitionEvent('bar'));

        try {
            $state->addTransitionEvent(new TransitionEvent('bar'));
        } catch (DuplicateEventException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * @test
     */
    public function raisesAnExceptionIfTheEventIdIsInvalidWhenSettingTheEntryEvent()
    {
        $state = new State('foo');

        try {
            $state->setEntryEvent(new TransitionEvent('bar'));
        } catch (InvalidEventException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * @test
     */
    public function raisesAnExceptionIfTheEventIdIsInvalidWhenSettingTheExitEvent()
    {
        $state = new State('foo');

        try {
            $state->setExitEvent(new TransitionEvent('bar'));
        } catch (InvalidEventException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * @test
     */
    public function raisesAnExceptionIfTheEventIdIsInvalidWhenSettingTheDoEvent()
    {
        $state = new State('foo');

        try {
            $state->setDoEvent(new TransitionEvent('bar'));
        } catch (InvalidEventException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * @test
     * @since Method available since Release 2.1.0
     */
    public function isNotAnEndStateIfNoEndEventsAreFound()
    {
        $event = \Phake::mock('Stagehand\FSM\Event\TransitionEventInterface');
        \Phake::when($event)->getEventID()->thenReturn('bar');
        \Phake::when($event)->isEndEvent()->thenReturn(false);
        $state = new State('foo');
        $state->addTransitionEvent($event);
        $result = $state->isEndState();

        $this->assertThat($result, $this->isFalse());
        \Phake::verify($event)->isEndEvent();
    }

    /**
     * @test
     * @since Method available since Release 2.1.0
     */
    public function isAnEndStateIfAtLeastATransitionEventIsAnEndEvent()
    {
        $event1 = \Phake::mock('Stagehand\FSM\Event\TransitionEventInterface');
        \Phake::when($event1)->getEventID()->thenReturn('bar');
        \Phake::when($event1)->isEndEvent()->thenReturn(false);
        $event2 = \Phake::mock('Stagehand\FSM\Event\TransitionEventInterface');
        \Phake::when($event2)->getEventID()->thenReturn('baz');
        \Phake::when($event2)->isEndEvent()->thenReturn(true);
        $state = new State('foo');
        $state->addTransitionEvent($event1);
        $state->addTransitionEvent($event2);
        $result = $state->isEndState();

        $this->assertThat($result, $this->isTrue());
        \Phake::verify($event1)->isEndEvent();
        \Phake::verify($event2)->isEndEvent();
    }
}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * indent-tabs-mode: nil
 * End:
 */
