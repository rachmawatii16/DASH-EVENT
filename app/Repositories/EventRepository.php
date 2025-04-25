<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    protected $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByRecruiterId($recruiterId)
    {
        return $this->model->where('recruiter_id', $recruiterId)->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Event $event, array $data)
    {
        $event->update($data);
        return $event;
    }

    public function delete(Event $event)
    {
        return $event->delete();
    }

    public function attachApplicant(Event $event, $applicantId)
    {
        return $event->applicants()->attach($applicantId);
    }

    public function hasApplicant(Event $event, $applicantId)
    {
        return $event->applicants()->where('applicant_id', $applicantId)->exists();
    }
} 