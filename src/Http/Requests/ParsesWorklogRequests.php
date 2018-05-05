<?php
/**
 * Contains the ParsesWorklogRequests trait.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-05
 *
 */

namespace Konekt\Stift\Http\Requests;

use Carbon\Carbon;

trait ParsesWorklogRequests
{
    public function getValidatorInstance()
    {
        $this->parseDates();
        $this->parseDuration();

        return parent::getValidatorInstance();
    }

    protected function parseDates()
    {
        $toMerge = [];
        if (!empty($this->request->get('started_at'))) {
            $toMerge['started_at'] = Carbon::parse($this->request->get('started_at'));
        }

        if (!empty($this->request->get('finished_at'))) {
            $toMerge['finished_at'] = Carbon::parse($this->request->get('finished_at'));
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }

    protected function parseDuration()
    {
        if (!empty($this->request->get('duration'))) {
            $submittedValue = $this->request->get('duration');
            if ((string)(int)$submittedValue != $submittedValue) {
                $this->merge(['duration' => app('stift.duration_humanizer')->humanReadableToSeconds($submittedValue)]);
            }
        }
    }
}
