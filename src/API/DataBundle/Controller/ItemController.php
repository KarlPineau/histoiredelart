<?php

namespace API\DataBundle\Controller;

use DATA\DataBundle\Entity\EntityProperty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    public function getAction($entity_id, $context)
    {
        $array = $this->loadData($entity_id);

        if($array === null) {
            $array =
                [
                    'response' => 'No item for this identifier'
                ];
        }
        if($context == 'messenger') {$array = $this->getMessengerJson($array);}

        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('title', 'Items from data.histoiredelart.fr');
        return $response;
    }

    public function loadData($entity_id) {
        $entity = $this->get('data_data.entity')->find('one', ['id' => $entity_id], null);

        $array = [];
        $array['id'] = $entity_id;
        $array['uri'] = 'https://histoiredelart.fr/data/item/'.$entity_id;
        if(!empty($this->get('data_data.entity')->getName($entity))) {
            $array['label'] = $this->get('data_data.entity')->getName($entity);
        }
        if(!empty($this->get('data_data.entity')->get('auteur', $entity))) {
            $array['creator'] = $this->get('data_data.entity')->get('auteur', $entity);
        }
        if(!empty($this->get('data_data.entity')->get('datation', $entity))) {
            $array['date'] = $this->get('data_data.entity')->get('datation', $entity);
        }
        if(!empty($this->get('data_data.entity')->get('commanditaire', $entity))) {
                $array['sponsor'] = $this->get('data_data.entity')->get('commanditaire', $entity);
        }
        if(!empty($this->get('data_data.entity')->get('mattech', $entity))) {
            $array['mattech'] = $this->get('data_data.entity')->get('mattech', $entity);
        }
        if(!empty($this->get('data_data.entity')->get('dimensions', $entity))) {
            $array['dimensions'] = $this->get('data_data.entity')->get('dimensions', $entity);
        }
        if(!empty($this->get('data_data.entity')->get('style', $entity))) {
            $array['style'] = $this->get('data_data.entity')->get('style', $entity);
        }
        if($this->get('data_data.entity')->getType($entity) == 'artwork' AND !empty($this->get('data_data.entity')->get('lieuDeConservation', $entity))) {
            $array['location'] = $this->get('data_data.entity')->get('lieuDeConservation', $entity);
        }
        if($this->get('data_data.entity')->getType($entity) == 'artwork' AND !empty($this->get('data_data.entity')->get('sujetIcono', $entity))) {
            $array['iconography'] = $this->get('data_data.entity')->get('sujetIcono', $entity);
        }
        if($this->get('data_data.entity')->getType($entity) == 'artwork' AND !empty($this->get('data_data.entity')->get('provenance', $entity))) {
            $array['provenance'] = $this->get('data_data.entity')->get('provenance', $entity);
        }

        foreach($this->get('data_data.entity')->getViews($entity) as $view) {
            $arrayForView = [];
            $arrayForView['thumbnail'] = $this->get('data_image.view')->getThumbnail($view);
            $arrayForView['id'] = $view->getId();
            foreach($this->get('data_image.view')->getFieldsForView($view) as $fieldForView) {
                $field = $fieldForView['field'];
                $arrayForView[$field] = $fieldForView['value'];
            }

            $array['views'][$view->getId()] = $arrayForView;
        }

        foreach($this->get('data_data.entity')->getTeachings($entity) as $teaching) {
            $arrayForTeaching = [];
            $arrayForTeaching['label'] = $teaching->getName();
            $arrayForTeaching['id'] = $teaching->getId();
            $array['teachings'][] = $arrayForTeaching;
        }

        foreach($this->get('data_data.entity')->getSameAs($entity) as $sameAs) {
            $array['sameAs'][] = $sameAs->getUrl();
        }

        foreach($this->get('data_data.entity')->getSources($entity) as $source) {
            $arrayForSource = [];
            $arrayForSource['id'] = $source->getId();
            $arrayForSource['title'] = $source->getTitle();
            $arrayForSource['url'] = $source->getUrl();
            $arrayForSource['authorityControl'] = $source->getAuthorityControl();
            $array['sources'][] = $arrayForSource;
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

    public function getAllAction()
    {
        set_time_limit(0);
        $entitys = $this->get('data_data.entity')->find('all');

        $array = array();
        foreach($entitys as $entity) {
            $array[$entity->getId()] = $this->loadData($entity->getId());
        }

        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function set($entity_id, $property, $value)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('data_data.entity')->find('one', ['id' => $entity_id], null);

        if($entity !== null) {
            $entityProperty = $em->getRepository('DATADataBundle:EntityProperty')->findOneBy(array('entity' => $entity_id, 'property' => $property));
            if($entityProperty !== null) {
                $entityProperty->setValue($value);
                $response = "update";
            } else {
                $entityProperty = new EntityProperty();
                $entityProperty->setEntity($entity_id);
                $entityProperty->setProperty($property);
                $entityProperty->setValue($value);
                $em->persist($entityProperty);
                $response = "set";
            }
            $em->flush();
        } else {
            $response = false;
        }

        $response = new Response(json_encode($response));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updateDataAction()
    {
        set_time_limit(0);
        $data = json_decode(file_get_contents('https://histoiredelart.fr/data/new-entities.json'),true);

        $countfalse = 0;
        $countset = 0;
        $countupdate = 0;

        foreach($data as $entity_id => $entity) {
            if (array_key_exists("wikidata", $entity)) {
                foreach ($entity["wikidata"] as $property => $value) {
                    $property = "wd-" . $property;
                    $result = $this->set($entity_id, $property, json_encode($value));

                    if ($result == false) {
                        $countfalse++;
                    } elseif ($result == "set") {
                        $countset++;
                    } elseif ($result == "update") {
                        $countupdate++;
                    }
                }
            }
            if (array_key_exists("wikidata-alignment", $entity)) {
                $property = "wd-wikidata-alignment";
                $result = $this->set($entity_id, $property, json_encode($entity["wikidata-alignment"]));

                if ($result == false) {
                    $countfalse++;
                } elseif ($result == "set") {
                    $countset++;
                } elseif ($result == "update") {
                    $countupdate++;
                }
            }
        }

        $this->get('session')->getFlashBag()->add('notice', 'Set: '.$countset.' | Update: '.$countupdate.' | False: '.$countfalse );
        return $this->redirectToRoute('data_administration_home_index');
    }
}
