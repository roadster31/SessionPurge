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
 * Date: 08/11/2016 19:40
 */
namespace SessionPurge\Command;

use SessionPurge\Event\SessionPurgeEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Model\ConfigQuery;

class SessionPurge extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("sessions:purge")
            ->setDescription("Purge all outdated session in local/sessions directory")
            ->addOption(
                "older-than",
                's',
                InputOption::VALUE_OPTIONAL,
                "Delete sessions older than N seconds. The session_config.lifetime value is ignored."
            )->addOption(
                'day',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Delete sessions older than N days. The session_config.lifetime value is ignored.',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $days = $seconds = 0;

        $seconds = $lifetime = intval($input->getOption('older-than'));

        if ($lifetime <= 0) {
            $days = intval($input->getOption('day'));
            $lifetime = 86400 * $days;
        }

        if ($lifetime <= 0) {
            $lifetime = $seconds = ConfigQuery::read('session_config.lifetime', 0);
        }

        if ($lifetime > 0) {
            $output->writeln(
                sprintf(
                    "<info>Deleting session files older than %d %s</info>",
                    $days > 0 ? $days : $seconds,
                    $days > 0 ? 'days' : 'seconds'
                )
            );

            $event = new SessionPurgeEvent($lifetime, $input->getOption('verbose'));

            $this->getDispatcher()->dispatch(SessionPurgeEvent::PURGE, $event);

            foreach ($event->getStatus() as $status => $level) {
                $output->writeln("<$level>$status</$level>");
            }

            $output->writeln(sprintf("<info>%d session files deleted</info>", $event->getDeletedCount()));
        } else {
            $output->writeln(sprintf("<info>Session lifetime is undefined, please check session_config.lifetime variable.</info>"));
        }
    }
}
