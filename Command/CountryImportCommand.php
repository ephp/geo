<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ephp\GeoBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Ephp\GeoBundle\Command\Helper\DialogHelper;
use Ephp\GeoBundle\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;

/**
 * Generates a CRUD for a Doctrine entity.
 *
 * @author Ephraim Pepe <fabien@symfony.com>
 */
class CountryImportCommand extends DoctrineCommand {

    /**
     * @see Command
     */
    protected function configure() {
        $this
                ->setDefinition(array())
                ->setDescription('Importa le nazioni')
                ->setHelp(<<<EOT
The <info>ephp:geo:importCountry</info> command import country from csv in defined table.

<info>php app/console ephp:geo:importCountry</info>
EOT
                )
                ->setName('ephp:geo:importCountry')
                ->setAliases(array('ephp:geo:importCountry'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Import Tag', 'bg=white;fg=black');

        $countryClass = $this->getContainer()->get('doctrine')->getEntityNamespace('EphpGeoBundle') . '\\Country';
        $geoClass = $this->getContainer()->get('doctrine')->getEntityNamespace('EphpGeoBundle') . '\\GeoNames';
        $em = $this->getEntityManager('default');

        $_geo = $em->getRepository($geoClass);


        $bundle = $this->getContainer()->get('kernel')->getBundle('EphpGeoBundle');
        $bundle_namespace = get_class($bundle);
        echo "\n\n" . $bundle_namespace;

        $path = $bundle->getPath();
        $sep = $path{0} == '/' ? '/' : '\\';
        $file = $path . "{$sep}Resources{$sep}fixture{$sep}country.csv";
        echo "\n";
        if (($handle = fopen($file, "r")) !== false) {
            $colonne = fgetcsv($handle, 1000, "\t");
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
                $country = new Country();
                $save = false;
                foreach ($colonne as $i => $colonna) {
                    $setter = "set{$colonna}";
                    if($colonna == 'Geonameid' && $data[$i]) {
                        $save = true;
                        $country->$setter($_geo->find($data[$i]));
                    } else {
                        $country->$setter($data[$i]);
                    }
                }
                if ($save) {
                    $em->persist($country);
                    $em->flush();
                }
            }
            fclose($handle);
        }
        $dialog->writeSection($output, $countryClass);
        ;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        return $em;
    }

    /**
     * 
     * @return \Ephp\PortletBundle\Command\Helper\DialogHelper
     */
    protected function getDialogHelper() {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Ephp\TagBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }

}
