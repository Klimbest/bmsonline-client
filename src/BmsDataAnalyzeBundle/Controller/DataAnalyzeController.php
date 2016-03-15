<?php

namespace BmsDataAnalyzeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DataAnalyzeController extends Controller {

    public function indexAction() {
        $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
        $registers = $registerRepo->findAll();
        
        return $this->render('BmsDataAnalyzeBundle::index.html.twig', ['registers' => $registers, 
                                                                       'detailChart' => $this->generateDetailChart(), 
                                                                       'masterChart' => $this->generateMasterChart()]);
    }

    public function generateDetailChart(){
        $detailChart = new Highchart();
        $detailChart->global->useUTC(false);
        $detailChart->chart->renderTo('detailContainer')
                           ->reflow(true)
                           ->alignTicks(false);
        $detailChart->credits->enabled(false);
        $detailChart->title->text('Pomiar danych, wykres odświeża się automatycznie');
        $detailChart->xAxis->type('datetime');
        $yAxis = array(
            array(
                'floor' => 0,
                'lineWidth' => 1,
                'tickWidth' => 1,
                'title' => array(
                    'offset' => 0,
                    'text' => '%',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                ),
                'opposite' => true,
                'min' => 0,
                'max' => 100
            ),
            array(
                'floor' => 0,
                'lineWidth' => 1,
                'tickWidth' => 1,
                'title' => array(
                    'offset' => 0,
                    'text' => '°C',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                ),
                'min' => 0,
                'max' => 40
            ),
            array(
                'floor' => 0,
                'lineWidth' => 1,
                'tickWidth' => 1,
                'title' => array(
                    'offset' => 0,
                    'text' => 'num',
                    'rotation' => 0,
                    'align' => 'high',
                    'y' => -10
                )
            )
        );
        $detailChart->yAxis($yAxis);
        $detailChart->tooltip->pointFormat('<span style="color:{point.color}">O</span> {series.name}: <b>{point.y}</b><br/>')
                             ->crosshairs(true)
                             ->shared(true)
                             ->backgroundColor('#FFF')
                             ->borderColor('#000')
                             ->style(['width' => '250px']);
        $detailChart->legend->align('right')
                            ->y(25)
                            ->x(-50)
                            ->width(150)
                            ->verticalAlign('top')
                            ->useHTML(true)
                            ->layout('vertical')
                            ->padding(3)
                            ->itemMarginTop(3)
                            ->itemMarginBottom(0)
                            ->itemHoverStyle(['color' => '#5BC0DE']);
        $detailChart->plotOptions->series(array(
                                            'marker' => array(
                                                            'fillColor' => '#FFF',                                                
                                                            'lineWidth' => 1,
                                                            'lineColor' => null
                                                        )
                                        ));
        $detailChart->series($this->addSeries());
        $detailChart->exporting->enabled(true);
        return $detailChart;
    }
    
    public function generateMasterChart(){
        $masterChart = new Highchart();
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
        $masterChart->series($this->addSeries());
        $masterChart->exporting->enabled(false);
        return $masterChart;
    }
    
    public function addSeries(){
        $registerArchiveDataRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:RegisterArchiveData');
        $registerArchiveData = $registerArchiveDataRepo->getArchiveData('\'2016-03-15 08:26\'', '\'000A\'');
        $arrayToChart1= array();
        foreach($registerArchiveData as $rad){
            $time = $rad["timeOfInsert"]->getTimestamp() * 1000;
            array_push($arrayToChart1, [$time, $rad["fixedValue"]] );
        }
        $registerArchiveData = $registerArchiveDataRepo->getArchiveData('\'2016-03-15 08:26\'', '\'000C\'');
        $arrayToChart2= array();
        foreach($registerArchiveData as $rad){
            $time = $rad["timeOfInsert"]->getTimestamp() * 1000;
            array_push($arrayToChart2, [$time, $rad["fixedValue"]] );
        }
        $series = array(
            array(
                'id' => '000A',
                'name' => 'Temperatura 000A',
                'data' => $arrayToChart1,
                'yAxis' => 1,
                'tooltip' => array(
                    'valueSuffix' => '°C'
                ),
                'type' => 'spline'
            ),
            array(
                'id' => '000C',
                'name' => 'Temperatura 000C',
                'data' => $arrayToChart2,
                'yAxis' => 1,
                'tooltip' => array(
                    'valueSuffix' => '°C'
                ),
                'type' => 'spline'
            )
        );
        return $series;
    }
}
