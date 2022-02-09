<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Grabitmk\Http;

use Carbon\Carbon;
use Omniship\Common\Component;
use Omniship\Common\EventBag;
use Omniship\Common\TrackingBag;
use Omniship\Common\TrackingMultipleBag;
use ResultTrackPickingEx;

class TrackingParcelsResponse extends AbstractResponse
{

    /**
     * @return TrackingMultipleBag
     */
    public function getData()
    {
        $results = new TrackingMultipleBag();
        if(!is_null($this->getCode())) {
            return $results;
        }

        if(is_array($this->data)) {
            foreach ($this->data AS $bol_id => $tracking) {
                $result = new TrackingBag();
                foreach ($tracking AS $row => $track) {
                    $track = $this->_getTrackPicking($track);
                    $name = implode(' ', array_filter([$track->getSiteType(), $track->getSiteName()]));
                    $name = explode('[', !$name && !$row ? $track->getOperationComment() : $name);
                    $name = trim(array_shift($name));
                    $result->push([
                        'id' => $track->getOperationCode(),
                        'name' => $name,
                        'events' => $this->_getEvents($track),
                        'shipment_date' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $track->getMoment()),
                        'estimated_delivery_date' => $this->_findEstimatedDeliveryDate($tracking[0]),
                        'origin_service_area' => null,
                        'destination_service_area' => $name ? new Component(['id' => md5($name), 'name' => $name]) : null,
                    ]);
                }
                $results->put($bol_id, $result);
            }
        }

        return $results;
    }

    /**
     * @param ResultTrackPickingEx $track
     * @return EventBag
     */
    protected function _getEvents(ResultTrackPickingEx $track)
    {
        $result = new EventBag();
        if($event = $track->getOperationComment()) {
            $result->push(new Component([
                'id' => 'comment',
                'name' => trim($event),
            ]));
        }
        if($event = $track->getOperationDescription()) {
            $result->push(new Component([
                'id' => 'description',
                'name' => trim($event),
            ]));
        }
        if($event = $track->getSignatureImage()) {
            $result->push(new Component([
                'id' => 'image',
                'name' => trim($event),
            ]));
        }
        return $result;
    }

    /**
     * @param ResultTrackPickingEx $track
     * @return ResultTrackPickingEx
     */
    protected function _getTrackPicking(ResultTrackPickingEx $track) {
        return $track;
    }

    /**
     * @param ResultTrackPickingEx $track
     * @return null|Carbon
     */
    protected function _findEstimatedDeliveryDate(ResultTrackPickingEx $track) {
        if(preg_match('~\:\s(([\d]{2}).([\d]{2}).([\d]{4}))$~im', $track->getOperationComment(), $match)) {
            return Carbon::createFromFormat('d.m.Y', $match[1], $this->getRequest()->getReceiverTimeZone());
        }
        return null;
    }

}
