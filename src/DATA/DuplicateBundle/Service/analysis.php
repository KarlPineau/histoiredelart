<?php

namespace DATA\DuplicateBundle\Service;

use DATA\DataBundle\Service\entity;
use DATA\DuplicateBundle\Entity\WordType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class analysis
{
    protected $em;
    protected $security;
    protected $entity;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
    }

    /* -- Recherche des doublons par recoupement des mots d'un nom -- */
    /* Méthodologie :
     *  -> On récupère le nom d'un édifice
     *  -> On séquence le nom en tableau de mot
     *      -> On se base pour ça sur les espaces entre les mots
     *      -> On enlève les caractères ponctués à la fin des mots : ,.;:-
     *  -> On obtient un tableau des mots qui composent un nom
     *
     *
     *  -> On analyse le tableau :
     *      -> Une analyse des mots de liaison
     *      -> Une analyse des mots qualificatif d'un édifice "maison, hôtel, église ..."
     *      -> Une analyse des lieux "Paris, Saint-Denis ..."
     *      -> Une analyse des personnes "Charles VIII ..."
     *
     *      -> L'objectif est de réussir à constituer une intelligence artificielle qui classe seule les mots
     *      en fonction de son apprentissage
     *
     *  -> On crée 4 tables pour chaque type de mot
     *  -> A chaque analyse d'un terme (manuel ou auto), l'algo rentre dans la table correspondante le mot trouvé,
     *     en indiquant si c'est lui qui a trouvé ça ou si un humain l'a indiqué.
     *
     *  -> Lorsqu'un nouvel édifice à analyser se présente :
     *      -> Le logiciel va pour chaque mot chercher dans la base de données des termes pour déterminer la structure
     *         du nom qui lui est donnée
     *      -> Il crée alors un tableau à la volée contenant pour chaque mot du nom de l'édifice en cours d'analyse
     *          (dans un type de mot) tous les édifices qui présente une occurrence :
     *          Mot      |  Edifices
     *          "Paris"  |  "Musée du Louvre, Paris", "Cathédrale Notre-Dame de Paris"
     *      -> Il convient ensuite de matcher selon une proportion à déterminer les valeurs :
     *              -> A chaque match confirmé, on insère dans une table les termes matché et le % de correspondance
     *                 entre les 2 termes
     *              -> On fait une moyenne des résultats courants obtenu, on lui retranche 20% (chiffre totalement
     *                 arbitraire) et on prend toutes les valeurs matchés au dessus de ce pourcentage
     *              -> Si on a plusieurs valeurs qui peuvent correspondre, on prendla plus élevée.
     *
     *  -> Méthode de détection des mots : compter la longueur (stastiquement)
     *  -> Méthode de détection des mots : regarder le caractère répétitif -> "de" revient dans toutes les fiches, plusieurs fois -> caractère répétitif = discriminant
     *  -> Essayer de trier les mots en fonction de leur contexte : artwork/building, par matière
     * */

    public function globalAnalysis($string, $entityRequest=null)
    {
        if($entityRequest != null) {$string = $this->entity->getName($entityRequest);}
        $string = strtolower($string);

        //Etape 1 :
        $stringToArray = $this->splitWords($string);

        //Pour chaque mot :
        $repositoryWordType = $this->em->getRepository('DATADuplicateBundle:WordType');
        $stringWordArray = array();
        foreach ($stringToArray as $key => $expression) {
            //On regarde de quel type de mot il s'agit
            //En récupérant tous les mots correspondant dans la table des types de mots
            $matchs = $repositoryWordType->findByWord($expression);

            if(count($matchs) > 0) { //Si on a des correspondances :
                foreach($matchs as $match) {
                    $wordArray =
                        ['word' => $match->getWord(),
                        'type' => $match->getType(),
                        'context' => $match->getContext()];
                    array_push($stringWordArray, $wordArray);
                }
            } else { //Si pas de correspondance :
                //On demande l'avis à l'administrateur
                $repositoryType = $this->em->getRepository('DATADuplicateBundle:Type');
                $undefined = $repositoryType->findOneByName('undefined');

                $newWordType = new WordType();
                $newWordType->setWord($expression);
                $newWordType->setType($undefined);
                $newWordType->setContext($string);
                $this->em->persist($newWordType);
                $this->em->flush();

                $wordArray =
                    ['word' => $expression,
                    'type' => $undefined,
                    'context' => $string];
                array_push($stringWordArray, $wordArray);
            }
        }

        //----------------------------------------------------------------------
        //----------------------------------------------------------------------
        // Seconde étape : lecture de tous les titres d'oeuvre pour créer un tableau global des mots :
        $entities = $this->entity->find('all', null, 'large');
        if($entityRequest != null) {$entities = $this->checkUnmatch($entityRequest, $entities);} //On supprime de entities toutes les entités dont on sait déjà qu'il ne s'agit pas de doublons

        //On crée un array qui contient tous les noms :
        $globalArraySplitName = array();
        foreach($entities as $entity) {
            $globalArraySplitName[] = ['string' => strtolower($this->entity->getName($entity)),
                                       'stringSplit' => $this->splitWords(strtolower($this->entity->getName($entity))),
                                       'entity' => $entity];
        }

        //On boucle cet array et on cree une ligne par mot (en minuscule et en clé) avec pour valeur toutes les entités matchés :
        $globalWordArray = array();
        foreach($globalArraySplitName as $keySplitName => $arraySplitName) {
            foreach($arraySplitName['stringSplit'] as $stringSplit) {
                //Mais on exclut du tableau tous les mots tagués comme mot de liaison
                $wordStringSplit = $repositoryWordType->findOneByWord($stringSplit);
                if($wordStringSplit != null) {
                    if($wordStringSplit->getType()->getName() != 'liaison') {
                        $globalWordArray[strtolower($stringSplit)][] = $globalArraySplitName[$keySplitName]['entity'];
                    }
                }
                else{
                    $globalWordArray[strtolower($stringSplit)][] = $globalArraySplitName[$keySplitName]['entity'];
                }
            }
        }
        ksort($globalWordArray); //On réordonne le tableau par ordre alphabétique sur les clés

        //Dans le cas où on trace une entité précise :
        if($entityRequest != null) {
            $entityRequestGlobalWordArray = array(); //Array global retournée qui contient la liste des doublons
            $pushDuplicate = 0; //Variable qui permet de compter le nombre total de doublon détecté pour une entité

            foreach($globalWordArray as $keyGlobalWordArrayLine => $globalWordArrayLine) {
                if(array_search($entityRequest, $globalWordArrayLine)) {
                    $globalWordArrayLineRebuilt = array();

                    foreach($globalWordArrayLine as $arrayLine) {
                        //On retire tous les résultats au sein d'un terme qui auraient une variation de plus de 30% de nombre de mots avec l'original
                        //Ici, arrayLine == entité comparée
                        //Ici, string = nom de entityRequest
                        $difflen = (strlen($this->entity->getName($arrayLine))*100)/strlen($string);
                        $difflenBoolean = false;
                        if($difflen > 70 AND $difflen < 130) {
                            $difflenBoolean = true;
                        }

                        //On vérifie que au moins 40% des mots de chaque titre sont similaires :
                        $diffLenWordsBoolean = false;
                        $nbDiff = count(array_diff($this->splitWords($string), $this->splitWords($this->entity->getName($arrayLine))));
                        $nbTotal = count($this->splitWords($string));
                        if(($nbDiff*100)/$nbTotal < 60) {$diffLenWordsBoolean = true;}

                        //Si toutes les conditions sont requises, on insère
                        if($difflenBoolean == true AND $diffLenWordsBoolean == true AND $entityRequest != $arrayLine) {
                            array_push($globalWordArrayLineRebuilt, $arrayLine);
                            $pushDuplicate++;
                        }
                    }

                    //On ajoute à notre array si le terme ne se trouve pas dans plus de 10% de l'ensemble des titres
                    if(count($globalWordArrayLine) < ((count($entities)*10)/100)) {
                        $globalWordArrayLineRebuilt = array_unique($globalWordArrayLineRebuilt, SORT_REGULAR);
                        //array_push($entityRequestGlobalWordArray, $globalWordArrayLineRebuilt);
                        $entityRequestGlobalWordArray = array_merge($entityRequestGlobalWordArray, $globalWordArrayLineRebuilt);
                    }
                }
            }
        }

        /*
         * Si on a plusieurs entrées dans $entityRequestGlobalWordArray qui sont la même entité, c'est que cette entité a plusieurs
         * mots communs avec l'oeuvre testée, donc plus de chance d'être un doublon
         */
        $arrayId = array();
        $entityRequestGlobalWordArrayFinal = array();
        $addArray = array();
        //On liste toutes les entités de l'array
        foreach($entityRequestGlobalWordArray as $entity) {
            //Si l'id est déjà répertorié :
            if(in_array($entity->getId(), $arrayId)) {
                $addArray[] = $entity->getId();
            } else {
                $entityRequestGlobalWordArrayFinal[] = ["id" => $entity->getId(), "occurrence" => 1, "entity" => $entity];
                $arrayId[] = $entity->getId();
            }
        }

        $arrayOk = array();
        foreach ($addArray as $add) {
            foreach ($entityRequestGlobalWordArrayFinal as &$entityFinal) { // le & précédent la valeur permet de mettre une référence et de modifier la valeur
                if ($entityFinal['id'] == $add) {
                    $entityFinal['occurrence'] = 2;
                }
            }
        }
        
        //On ne retourne un résultat que si on a détecté des doublons
        if($pushDuplicate > 0) {
            return ['stringToArray' => $stringToArray,
                    'stringWordArray' => $stringWordArray,
                    'globalArraySplitName' => $globalArraySplitName,
                    'globalWordArray' => $globalWordArray,
                    'entityRequestGlobalWordArray' => $entityRequestGlobalWordArrayFinal
                    ];
        } else {
            return null;
        }
    }

    public function splitWords($string)
    {
        if(empty($string)) { return null;}

        // On découpe notre chaine de caractères en array.
        // On prend en compte espaces, virgules, points, deux points, slashs, underscores et apostrophes
        $stringToArray = preg_split("/ |,|\.|:|\/|_|'/", $string);

        //On obtient un array :
        foreach($stringToArray as $key => $expression) {
            //On vérifie pour chaque mot qu'il ne commence pas ou ne finit pas par un caractère spécial :
            $arrayPrint = ['.', ',', '!', '?', ';', ':', '/', '+', '=', '_', '(', ')', '[', ']', '{', '}', '"', '\''];
            $expressionToArray = str_split($expression);

            foreach ($expressionToArray as $keyExpressionToArray => $letter) {
                foreach($arrayPrint as $print)
                {
                    /*
                     * Attention, algo actuel supp apostrophe -> l'amour -> lamour
                     *                                          -> aujourd'hui -> aujuurdhui
                     * Regarder si on ne peut pas prend les caractères "latéraux" -> 25% sur les côtés ?
                     * MAJ -> Solution : les apostrophes sont définies comme séparateur de mot
                     * */
                    if($letter == $print) {unset($expressionToArray[$keyExpressionToArray]); break;}
                }
            }
            $stringToArray[$key] = implode('', $expressionToArray);

            //On supprime les cellules vides
            if($expression == "") {unset($stringToArray[$key]);}
        }

        return $stringToArray;
    }

    public function checkUnmatch($entityRequest, $entities)
    {
        $repositoryUnmatch = $this->em->getRepository('DATADuplicateBundle:Unmatch');
        $returnArray = array();
        foreach($entities as $entity)
        {
            $check = false;

            $choice1 = count($repositoryUnmatch->findBy(array('entityLeft' => $entity, 'entityRight' => $entityRequest)));
            $choice2 = count($repositoryUnmatch->findBy(array('entityRight' => $entity, 'entityLeft' => $entityRequest)));

            if($choice1 > 0 OR $choice2 > 0) {$check = true;}

            
            if($check == false) {array_push($returnArray, $entity);}
        }

        return $returnArray;
    }
}
