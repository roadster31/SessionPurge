<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace SessionPurge\Event;

use Thelia\Core\Event\ActionEvent;

class SessionPurgeEvent extends ActionEvent
{
    const PURGE = 'sessionpurge.purge';
    
    /** @var int max session age in seconds */
    protected $maxAge;
    
    /** @var  string[] */
    protected $status = [];
    
    /** @var  bool */
    protected $verbose;
    
    /** @var  int */
    protected $deletedCount = 0;
    
    /**
     * SessionPurgeEvent constructor.
     * @param int $maxAge max session age in seconds
     */
    public function __construct($maxAge, $verbose = false)
    {
        $this->maxAge = $maxAge;
        $this->verbose = $verbose;
    }
    
    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }
    
    /**
     * @return string[]
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param string $status
     * @return $this
     */
    public function appendStatus($status, $level = 'info')
    {
        $this->status[$status] = $level;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }
    
    /**
     * @return int
     */
    public function getDeletedCount()
    {
        return $this->deletedCount;
    }
    
    /**
     * @param int $deletedCount
     * @return $this
     */
    public function setDeletedCount($deletedCount)
    {
        $this->deletedCount = $deletedCount;
        return $this;
    }
}
