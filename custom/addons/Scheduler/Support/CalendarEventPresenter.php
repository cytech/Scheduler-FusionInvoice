<?php

namespace Addons\Scheduler\Support;

use stdClass;

class calendarEventPresenter
{
    public function calendarEvent($entity,$type)
    {

        $data = new stdClass();

        $data->allDay = true;

        switch ($type){
            case 'quote':
                $data->id = ucfirst($type) . ': ' . $entity->number;
                $data->url = url("/quotes/{$entity->id}/edit");
                $data->title = trans("Scheduler::texts.{$type}") . ' ' . $entity->number . ' for ' . $entity->client->name;
                $data->start = $entity->expires_at ?: $entity->quote_date;
                break;
            case 'invoice':
                $data->id = ucfirst($type) . ': ' . $entity->number;
                $data->url = url("/invoices/{$entity->id}/edit");
                $data->title = trans("Scheduler::texts.{$type}") . ' ' . $entity->number . ' for ' . $entity->client->name ;
                $data->start = $entity->due_at ?: $entity->invoice_date;
                break;
            case 'payment':
                $data->id = ucfirst($type) . ': ' . $entity->id;
                $data->url = url("/payments/{$entity->id}");
                $data->title = trans("Scheduler::texts.{$type}") . ' for Invoice ' . $entity->invoice_id ;
                $data->start = $entity->paid_at;
                break;
            case 'expense':
                $data->id = ucfirst($type) . ': ' . $entity->id;
                $data->url = url("/expenses/{$entity->id}/edit");
                $data->title = trans("Scheduler::texts.{$type}") . ' for Category ' . $entity->category->name ;
                $data->start = $entity->expense_date;
                break;
            case 'project':
                $data->id = ucfirst($type) . ': ' . $entity->id;
                $data->url = url("/time_tracking/projects/{$entity->id}/edit");
                $data->title = trans("Scheduler::texts.{$type}") . ' for Client ' . $entity->client->name ;
                $data->start = $entity->due_at;
                break;
            case 'task':
                $data->id = ucfirst($type) . ': ' . $entity->id;
                $data->url = url("/time_tracking/projects/{$entity->time_tracking_project_id}/edit");
                $data->title = trans("Scheduler::texts.{$type}") . ' ' . $entity->name . ' for Project ' . $entity->project->name ;
                $data->start = $entity->timers->first()->start_at;
                break;
            default:

        }


        return $data;
    }
}