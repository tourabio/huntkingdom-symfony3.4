<?php

namespace TrainingBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use TrainingBundle\Entity\Animal;
use TrainingBundle\Entity\Entrainement;

class DefaultController extends Controller
{
    public function trainingAction()
    {
        return $this->render('@Training/dashboard/list_training.html.twig');
    }



    public function chartAction()
    {

        $pieChart = new PieChart();
        $em= $this->getDoctrine();

        $totalEtudiant= sizeof($em->getRepository(Animal::class)->findAll());
        $arrray = [];
        $cats = $em->getRepository(Entrainement::class)->findAll();
        foreach ($cats as $cat) {
            $arrray[($cat->getCategorie())] = (sizeof($em->getRepository(Animal::class)->findBy(['categorie'=>$cat->getCategorie()])));
        }

        $data= array();
        $stat=['Categorie', 'count'];
        $nb=0;
        array_push($data,$stat);
        foreach ($arrray as $key=>$value) {

            $stat=array();
            array_push($stat,$key,$value * 100 / $totalEtudiant);
            $nb=$value * 100 / $totalEtudiant;
            $stat=[$key,$nb];
            array_push($data,$stat);
        }


        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Percentage of animals per category');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('@Training/chart.html.twig', ['pieChart' => $pieChart]);
    }

}
