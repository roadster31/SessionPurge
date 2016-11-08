<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 08/11/2016 21:54
 */
namespace SessionPurge\EventListener;

use SessionPurge\Event\SessionPurgeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Thelia\Model\Exception\InvalidArgumentException;

class EventManager implements EventSubscriberInterface
{
    public function purge(SessionPurgeEvent $event)
    {
        $lifetime = $event->getMaxAge();

        if ($lifetime <= 0) {
            throw new InvalidArgumentException("lifetime could not be 0");
        }
        
        $verbose = $event->isVerbose();
        
        $files = Finder::create()
            ->in(THELIA_LOCAL_DIR . 'session')
            ->files()
            ->ignoreDotFiles(true)
            ->date('<= now - ' . $lifetime . ' seconds');
        
        $deleted = 0;
        
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $path = $file->getRealPath();
            
            if (false === @unlink($path)) {
                $event->appendStatus(sprintf("Failed to delete %s file", $path), 'error');
            } else {
                if ($verbose) {
                    $event->appendStatus(sprintf("%s sucessfully deleted", $path));
                }
                
                $deleted++;
            }
        }
        
        $event->setDeletedCount($deleted);
    }
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SessionPurgeEvent::PURGE => ["purge", 128]
        ];
    }
}
