<?php namespace Waka\Wiki\WakaRules\Contents;

use Waka\WakaBlocs\Classes\Rules\RuleContentBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\WakaBlocs\Interfaces\RuleContent as RuleContentInterface;
use Wikipedia; 

class Wiki extends RuleContentBase implements RuleContentInterface
{
    

    /**
     * Returns information about this event, including name and description.
     */
    public function subFormDetails()
    {
        return [
            'name'        => 'Données de wikipedia',
            'description' => 'Prend les données d\'une page Wikipedia et les affiche',
            'icon'        => 'wn-icon-wikipedia-w',
            'premission'  => 'wcli.utils.cond.edit.admin',
        ];
    }

    public $importExportConfig = [
    ];

    public function getText()
    {
        //trace_log('getText HTMLASK---');
        $hostObj = $this->host;
        //trace_log($hostObj->config_data);
        $name = $hostObj->config_data['name'] ?? null;
        if($name) {
            return "Page Wiki : ".$name;
        }
        return parent::getText();

    }

    /**
     * IS true
     */

    public function resolve($datas = []) {
        //trace_log('resolve');
        $name = $this->getConfig('name');
        
        $data = $this->getConfigs();
        $page = \Cache::rememberForever('wiki.'.$name, function () use($name) {
            return (new Wikipedia('fr'))->preview($name)->toArray();
        });
        //on ajoute toutes les données du formulaire
        $data = array_merge($data, $page);
        //trace_log($data);
        return $data;
    }
    
}
