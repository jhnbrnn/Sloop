<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Service;

use Sloop\Model\Zine;
use Sloop\Model\ZineIssue;

class ZineService extends AbstractService
{

    public function getZine($id)
    {
        return Zine::query()->find($id);
    }

    public function getAll()
    {
        return Zine::all();
    }

    public function getIssuesForZine($zineId)
    {
        //TODO implement this!
        return [];
    }

    public function getZineIssue($zineId, $id)
    {

        return Zine::with([
            'zineIssues' => function ($query) use ($id) {
                $query->where('issue_number', $id);
            }
        ])->find($zineId);
    }

    public function createZine($title, $description)
    {
        $zine = new Zine();
        $zine->title = $title;
        $zine->description = $description;
        return $zine->save();
    }

    public function createZineIssue($zineId, $zineIssueArray)
    {
        $zineIssue = new ZineIssue();
        $zineIssue->zine_id = $zineId;
        $zineIssue->setIssueNumber($zineIssueArray['issueNumber']);
        return $zineIssue->save();
    }

}