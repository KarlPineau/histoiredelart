<?php

namespace API\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function queryAction()
    {
        if(isset($_GET['keyHDA']) AND $_GET['keyHDA'] == 'histoiredelartfr') {
            if(isset($_GET['query']) AND !empty($_GET['query'])) {
                $array = $this->loadData($_GET['query']);

                if($array === null) {
                    $array =
                        [
                            'response' => 'No response for this search query'
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

    public function loadData($query) {
        $items = $this->get('data_search.search')->search($query, null);
        $results = [];
        foreach($items as $item) {
            $results[] =
                [
                    'id' => $item->getId(),
                    'label' => $this->get('data_data.entity')->getName($item),
                    'uri' => 'https://histoiredelart.fr/data/item/'.$item->getId()
                ];
        }

        return ['query' => $query, 'results' => $results];
    }

    public function getMessengerJson($array)
    {
        $query = $array['query'];
        $results = $array['results'];
        $return = [
            "messages" => [
                ["text" =>  "Résultats de la recherche \"".$query."\" :"],

            ]
        ];

        foreach($results as $result) {
            if(isset($array['views'])) {$thumbnail = $array['views'][0]['thumbnail'];}
            $card = [
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
            ];

            $return['messages'][] = $card;
        }
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
