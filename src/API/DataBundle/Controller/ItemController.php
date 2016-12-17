<?php

namespace API\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    public function getAction()
    {
        if(isset($_GET['keyHDA']) AND $_GET['keyHDA'] == 'histoiredelartfr') {
            if(isset($_GET['item'])) {
                if(!empty($_GET['item'])) {
                    $array = $this->loadData($_GET['item']);
                } else {
                    $items = $this->get('data_data.entity')->find('all');
                    $array = $this->loadData($items[rand(0,count($items)-1)]->getId());
                }

                if($array === null) {
                    $array =
                        [
                            'response' => 'No item for this identifier'
                        ];
                }
                if(isset($_GET['context']) AND $_GET['context'] == 'messenger') {$array = $this->getMessengerJson($array);}
            } else {
                $array =
                    [
                        'response' => 'You must specify a item parameter'
                    ];
            }
        } else {
            $array =
                [
                    'response' => 'You must be authenticated to query the API'
                ];
        }

        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('title', 'Test');
        return $response;
    }

    public function loadData($item_id) {
        $item = $this->get('data_data.entity')->find('one', ['id' => $item_id], null);

        $array = [];
        $array['id'] = $item_id;
        $array['uri'] = 'https://histoiredelart.fr/data/item/'.$item_id;
        if(!empty($this->get('data_data.entity')->getName($item))) {
            $array['label'] = $this->get('data_data.entity')->getName($item);
        }
        if(!empty($this->get('data_data.entity')->get('auteur', $item))) {
            $array['creator'] = $this->get('data_data.entity')->get('auteur', $item);
        }
        if(!empty($this->get('data_data.entity')->get('datation', $item))) {
            $array['date'] = $this->get('data_data.entity')->get('datation', $item);
        }
        if(!empty($this->get('data_data.entity')->get('commanditaire', $item))) {
                $array['sponsor'] = $this->get('data_data.entity')->get('commanditaire', $item);
        }
        if(!empty($this->get('data_data.entity')->get('mattech', $item))) {
            $array['mattech'] = $this->get('data_data.entity')->get('mattech', $item);
        }
        if(!empty($this->get('data_data.entity')->get('dimensions', $item))) {
            $array['dimensions'] = $this->get('data_data.entity')->get('dimensions', $item);
        }
        if(!empty($this->get('data_data.entity')->get('style', $item))) {
            $array['style'] = $this->get('data_data.entity')->get('style', $item);
        }
        if($this->get('data_data.entity')->getType($item) == 'artwork' AND !empty($this->get('data_data.entity')->get('lieuDeConservation', $item))) {
            $array['location'] = $this->get('data_data.entity')->get('lieuDeConservation', $item);
        }
        if($this->get('data_data.entity')->getType($item) == 'artwork' AND !empty($this->get('data_data.entity')->get('sujetIcono', $item))) {
            $array['iconography'] = $this->get('data_data.entity')->get('sujetIcono', $item);
        }
        if($this->get('data_data.entity')->getType($item) == 'artwork' AND !empty($this->get('data_data.entity')->get('provenance', $item))) {
            $array['provenance'] = $this->get('data_data.entity')->get('provenance', $item);
        }

        foreach($this->get('data_data.entity')->getViews($item) as $view) {
            $arrayForView = [];
            $arrayForView['thumbnail'] = $this->get('data_image.view')->getThumbnail($view);
            foreach($this->get('data_image.view')->getFieldsForView($view) as $fieldForView) {
                $field = $fieldForView['field'];
                $arrayForView[$field] = $fieldForView['value'];
            }

            $array['views'][] = $arrayForView;
        }

        foreach($this->get('data_data.entity')->getTeachings($item) as $teaching) {
            $arrayForTeaching = [];
            $arrayForTeaching['label'] = $teaching->getName();
            $array['teachings'][] = $arrayForTeaching;
        }



        return $array;
    }

    public function getMessengerJson($array)
    {

        if(isset($array['views'])) {$thumbnail = $array['views'][0]['thumbnail'];}

        $return = [
            "messages" => [
                [
                    "attachment" => [
                        "type" => "template",
                        "payload" => [
                            "template_type" => "generic",
                            "elements" => [
                                [
                                    "title" => $array['label'],
                                    "image_url" => $thumbnail,
                                    "subtitle" => $this->getDescription($array),
                                    "buttons" => [
                                        [
                                            "type" => "web_url",
                                            "url" => "https://petersapparel.parseapp.com/view_item?item_id=100",
                                            "title" => "View Item"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $return;
    }

    public function getDescription($array) {
        $description = [];
        if(isset($array['creator'])) {$description[] = $array['creator'];}
        if(isset($array['date'])) {$description[] = $array['date'];}
        if(isset($array['location'])) {$description[] = $array['location'];}
        if(isset($array['style'])) {$description[] = $array['style'];}
        if(isset($array['provenance'])) {$description[] = 'de '.$array['provenance'];}
        if(isset($array['sponsor'])) {$description[] = 'pour '.$array['sponsor'];}

        return implode(', ', $description);
    }
}
