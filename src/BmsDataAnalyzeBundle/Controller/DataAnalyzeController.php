<?php

namespace BmsDataAnalyzeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DataAnalyzeController extends Controller
{

    /**
     * @Route("/", name="bms_data_analyze_index")
     */
    public function indexAction()
    {
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        $registers = $registerRepo->getArchivedRegisters();
        $detailChart = new Highchart();
        $masterChart = new Highchart();

        return $this->render('BmsDataAnalyzeBundle::index.html.twig', ['registers' => $registers,
            'detailChart' => $this->generateDetailChart($detailChart),
            'masterChart' => $this->generateMasterChart($masterChart)]);
    }

    public function generateDetailChart($detailChart)
    {
        $detailChart->global->useUTC(false);
        $detailChart->colors = ['#2f7ed8', '#ffffff', '#8bbc21', '#910000', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'];
        $detailChart->chart
            ->renderTo('detailContainer')
            ->reflow(true)
            ->backgroundColor("#272b30")
            ->color("#c8c8c8");
        $detailChart->credits->enabled(false);
        $detailChart->title
            ->text('Pomiar danych, wykres odświeża się automatycznie')
            ->style(["color" => "#c8c8c8"]);
        $detailChart->xAxis->type('datetime');
        $yAxis = array(
            array(
                'title' => array(
                    'offset' => 0,
                    'text' => '%',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                ),
                'label' => array(
                    'style' => array(
                        "fontSize" => "70%"
                    )
                ),
                'showEmpty' => false,
                'opposite' => true,
            ),
            array(
                'title' => array(
                    'offset' => 0,
                    'text' => '°C',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                ),
                'label' => array(
                    'style' => array(
                        "fontSize" => "70%"
                    )
                ),
                'showEmpty' => false
            ),
            array(
                'title' => array(
                    'offset' => 0,
                    'text' => 'num',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                ),
                'label' => array(
                    'style' => array(
                        "fontSize" => "70%"
                    )
                ),
                'showEmpty' => false
            )
        );
        $detailChart->yAxis($yAxis);

        $detailChart->tooltip
            ->pointFormat("<span style='color:{point.color}'>&#x25cf;</span> {series.name}: <b>{point.y:.2f}</b><br/>")
            ->crosshairs(true)
            ->shared(false)
            ->style(['width' => '250px'])
            ->useHTML(true);
        $detailChart->legend
            ->align('left')
            ->itemStyle(['color' => '#c8c8c8'])
            ->verticalAlign('bottom')
            ->useHTML(true)
            ->layout('horizontal')
            ->itemHoverStyle(['color' => '#5BC0DE'])
            ->itemHiddenStyle(['color' => '#000000']);
        $detailChart->series();
//        $detailChart->plotOptions->series([
//            'marker' => [
//                'enabled' => true,
//                'fillColor' => "#FFF",
//                'lineWidth' => 1,
//                'lineColor' => null
//            ]
//        ]);
        $detailChart->exporting->enabled(true);
        return $detailChart;
    }

    public function generateMasterChart($masterChart)
    {

        $masterChart->global->useUTC(false);
        $masterChart->chart->renderTo('masterContainer')
            ->reflow(true)
            ->borderWidth(0)
            ->backgroundColor(null)
            ->marginLeft(65)
            ->marginRight(50)
            ->zoomType('x')
            ->events(null);
        $masterChart->title->text(null);
        $masterChart->xAxis->type('datetime')
            ->showLastTickLabel(true)
            ->title(array('text' => null));
        $yAxis = array(
            array(
                'floor' => 0,
                'gridlineWidth' => 0,
                'labels' => array(
                    'enabled' => false
                ),
                'title' => array(
                    'text' => null
                ),
                'opposite' => true,
                'showFirstLabel' => false
            ),
            array(
                'floor' => 0,
                'gridlineWidth' => 0,
                'labels' => array(
                    'enabled' => false
                ),
                'title' => array(
                    'text' => null
                ),
                'opposite' => true,
                'showFirstLabel' => false
            ),
            array(
                'floor' => 0,
                'gridlineWidth' => 0,
                'labels' => array(
                    'enabled' => false
                ),
                'title' => array(
                    'text' => null
                ),
                'opposite' => true,
                'showFirstLabel' => false
            )
        );
        $masterChart->yAxis($yAxis);
        $masterChart->tooltip->pointFormat('{series.name}: <b>{point.y}</b><br/>');
        $masterChart->legend->enabled(false);
        $masterChart->plotOptions->series(array(
            'marker' => array(
                'enabled' => false
            ),
            'lineWidth' => 1,
            'fillColor' => array(
                'linearGradient' => [0, 0, 0, 70],
                'stops' => [
                    [1, '#FFF']
                ]
            ),
            'shadow' => false,
            'states' => array(
                'hover' => array(
                    'lineWidth' => 1
                )
            ),
            'enableMouseTracking' => false
        ));
        $masterChart->credits->enabled(true);
        $masterChart->series();
        $masterChart->exporting->enabled(false);
        return $masterChart;
    }

    /**
     * @Route("/add_series", name="bms_data_analyze_add_series", options={"expose"=true})
     */
    public function addSeriesAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $from = $request->get('from');
            $to = $request->get('to');
            $registerId = $request->get('registerId');
            $yAxis = $request->get('yAxis');

            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $register = $registerRepo->find($registerId);

            $registerArchiveDataRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:RegisterArchiveData');
            $registerArchiveData = $registerArchiveDataRepo->getArchiveData($from, $to, $registerId);
            $arrayToChart = array();
            foreach ($registerArchiveData as $rad) {
                $time = $rad["timeOfInsert"]->getTimestamp() * 1000;
                array_push($arrayToChart, [$time, $rad["fixedValue"]]);
            }
            $series = [
                'id' => $register->getId(),
                'name' => $register->getName(),
                'data' => $arrayToChart,
                'yAxis' => $yAxis,
                'suffix' => '°C'
            ];

            return new JsonResponse($series);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
