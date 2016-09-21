<?php

namespace VisualizationBundle\Entity\Repository;

use Ob\HighchartsBundle\Highcharts\Highchart;

/**
 * GadgetChartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GadgetChartRepository extends \Doctrine\ORM\EntityRepository
{

    public function findForPage($page_id)
    {
        $data = self::findBy(['page' => $page_id]);
        $ret = [];

        foreach ($data as $element) {
            $id = $element->getId();

            $chart = new Highchart();
            $chart->global->useUTC(false);
            $chart->chart
                ->animation(false)
                ->renderTo('chart_' . $id)
                ->backgroundColor('rgba(255, 255, 255, 0)')
                ->height($element->getHeight())
                ->margin([2, 2, 2, 2]);
            $chart->title->text(null);
            $chart->xAxis
                ->title(null)
                ->type('datetime')
                ->visible(false);
            $chart->yAxis
                ->title(null)
                ->visible(true)
                ->minorTickColor('transparent')
                ->minorGridLineColor('transparent')
                ->gridLineColor('transparent')
                ->lineColor('transparent')
                ->plotLines([
                    [
                        'value' => $element->getConst(),
                        'color' => $element->getConstColor(),
                        'width' => 1,
                    ],
                ]);;
            $chart->exporting->enabled(false);
            $chart->legend->enabled(false);
            $chart->credits->enabled(false);
            $chart->tooltip->enabled(false);
            $chart->plotOptions
                ->series([
                    'lineWidth' => 1,
                    'marker' => [
                        'enabled' => false
                    ],
                    'enableMouseTracking' => false,
                ]);
            $from = (new \DateTime())->modify("-" . $element->getHourOffset() . " hour");
            $registerArchiveData = self::getArchiveData($from, $element->getSource()->getId());
            $arrayToChart = array();
            foreach ($registerArchiveData as $rad) {
                $time = $rad["timeOfInsert"]->getTimestamp() * 1000;
                array_push($arrayToChart, [$time, $rad["fixedValue"]]);
            }
            $series = [
                'data' => $arrayToChart,
                'color' => $element->getColor(),
            ];

            $chart->series([$series]);
            $ret[$id] = $chart;
        }
        return $ret;

    }

    public function updateForPage($page_id)
    {
        $data = self::findBy(['page' => $page_id]);
        $ret = [];

        foreach ($data as $element) {

            $from = (new \DateTime())->modify("-" . $element->getHourOffset() . " hour");
            $registerArchiveData = self::getArchiveData($from, $element->getSource()->getId());
            $arrayToChart = [];
            foreach ($registerArchiveData as $rad) {
                $time = $rad["timeOfInsert"]->getTimestamp() * 1000;
                array_push($arrayToChart, [$time, $rad["fixedValue"]]);
            }
            $series = [
                'data' => $arrayToChart,
                'color' => $element->getColor(),
            ];

            array_push($ret, ['series' => $series, 'id' => $element->getId()]);
        }

        return $ret;

    }

    public function getArchiveData($from, $registerId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT rad.timeOfInsert, rad.fixedValue '
                . 'FROM BmsConfigurationBundle:RegisterArchiveData AS rad '
                . 'WHERE rad.register = ' . $registerId
                . ' AND rad.timeOfInsert >= \'' . $from->format('Y-m-d H:i:s') . '\''
            )
            ->getResult();
    }

}
