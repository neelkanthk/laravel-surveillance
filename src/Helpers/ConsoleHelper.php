<?php

namespace Neelkanth\Laravel\Surveillance\Helpers;

use Neelkanth\Laravel\Surveillance\Models\SurveillanceManager;
use stdClass;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ConsoleHelper
{
    /**
     * Formats the output table to be shown in the console
     *
     * @param [mixed] $unformattedData
     * @return void
     */
    public static function formatConsoleTableOutput($unformattedData)
    {
        $header = ['ID', 'Type', "Value", "Surveillance Enabled", "Access Blocked", "Surveillance Enabled at", "Surveillance Disabled at", "Access Blocked at", "Access Unblocked at", "Created at", "Updated at"];
        $rows = array();
        if (($unformattedData instanceof EloquentCollection)) {
            foreach ($unformattedData as $data) {
                $transformed = self::transform($data);
                array_push($rows, $transformed);
            }
        } else {
            if ($unformattedData instanceof SurveillanceManager) {
                $transformed = self::transform($unformattedData);
                array_push($rows, $transformed);
            }
        }

        return [
            "header" => $header,
            "rows" => $rows
        ];
    }

    /**
     * Console Table Output Transformer
     *
     * @param SurveillanceManager $model
     * @return void
     */
    public static function transform(SurveillanceManager $model)
    {
        $row = new stdClass();
        $row->id = $model->id;
        $row->type = $model->type;
        $row->value = $model->value;
        $row->surveillance_enabled = ($model->surveillance_enabled == 1) ? "Yes" : "No";
        $row->access_blocked = ($model->access_blocked == 1) ? "Yes" : "No";
        $row->surveillance_enabled_at = is_null($model->surveillance_enabled_at) ? "-" : $model->surveillance_enabled_at->toDateTimeString();
        $row->surveillance_disabled_at = is_null($model->surveillance_disabled_at) ? "-" : $model->surveillance_disabled_at->toDateTimeString();
        $row->access_blocked_at =  is_null($model->access_blocked_at) ? "-" : $model->access_blocked_at->toDateTimeString();
        $row->access_unblocked_at = is_null($model->access_unblocked_at) ? "-" : $model->access_unblocked_at->toDateTimeString();
        $row->created_at = is_null($model->created_at) ? "-" : $model->created_at->toDateTimeString();
        $row->updated_at = is_null($model->updated_at) ? "-" : $model->updated_at->toDateTimeString();
        return (array) $row;
    }
}
