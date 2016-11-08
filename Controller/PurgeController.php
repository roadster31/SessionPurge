<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace SessionPurge\Controller;

use SessionPurge\Event\SessionPurgeEvent;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Model\ConfigQuery;

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 08/11/2016 22:15
 */
class PurgeController extends BaseFrontController
{
    public function purge($secretKey)
    {
        $responseText = '';
    
        $storedSecretKey = trim(@file_get_contents(__DIR__ .'/../Config/secret-key.txt'));
        
        if ($storedSecretKey != $secretKey) {
            $responseText .= sprintf("ERROR: key verification failed.<br>");
        } else {
            $lifetime = intval($this->getRequest()->query->get('older_than', 0));
    
            if ($lifetime <= 0) {
                $lifetime = ConfigQuery::read('session_config.lifetime', 0);
            }
    
            if ($lifetime > 0) {
                $responseText .= sprintf("INFO: Deleting session files older than %d seconds<br>", $lifetime);
        
                $verbose = $this->getRequest()->query->get('verbose', false);
        
                $event = new SessionPurgeEvent($lifetime, !empty($verbose));
        
                $this->getDispatcher()->dispatch(SessionPurgeEvent::PURGE, $event);
        
                foreach ($event->getStatus() as $status => $level) {
                    $responseText .= strtoupper($level) . ": $status<br>";
                }
        
                $responseText .= sprintf("INFO: %d session files deleted<br>", $event->getDeletedCount());
            } else {
                $responseText .= sprintf("ERROR: Session lifetime is undefined, please check session_config.lifetime variable or add older_than parameter.<br>");
            }
        }
        
        return new Response($responseText);
    }
}
