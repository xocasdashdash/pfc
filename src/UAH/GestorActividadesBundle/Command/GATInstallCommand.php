<?php

namespace UAH\GestorActividadesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use UAH\GestorActividadesBundle\Entity\User;

/**
 * Description of GATInstallCommand
 *
 * @author xokas
 */
class GATInstallCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $description = <<<EOT
                Comando que instala un usuario superadministrador para la aplicación web Gestor de Actividades Transversales. Tiene diferentes parametros para poder forzar recuperar la aplicación si un superadministrador ha perdido el acceso
EOT;
        $this->setName('gat:install')
                ->setDescription($description)
                ->addArgument('identity', InputArgument::REQUIRED, 'Id del usuario que se va crear (Ej: javier.palacios)')
                ->addArgument('domain', InputArgument::REQUIRED, 'Dominio del usuario que se va crear (Ej: uah.es)')
                ->addOption('force', 'f', \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL, 'Si pasamos este parametro y hay un usuario superadmin creado. Añade otro', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userIdentity = $input->getArgument('identity');
        $userDomain = $input->getArgument('domain');
        $force = $input->getOption('force');

        $container = $this->getContainer();
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $container->get('doctrine.orm.entity_manager');


        /* @var $userRepo \UAH\GestorActividadesBundle\Repository\UserRepository */
        $userRepo = $em->getRepository('UAHGestorActividadesBundle:User');
        $superAdminUsers = $userRepo->getSuperAdminUsers();
        $superAdminRole = $em->getRepository('UAHGestorActividadesBundle:Role')->getSuperAdmin();

        if (count($superAdminUsers) !== 0) {
            $userString = implode(",", array_map(function(User $e) {
                        return "UAH: " . $e->getUahName() . "|EMAIL: " . $e->getEmail();
                    }, $superAdminUsers));
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion("Ya existen superadministradores ({$userString}). ¿Los borramos?", false);
            $delete = $helper->ask($input, $output, $question);
            if ($delete) {
                foreach ($superAdminUsers as $user) {
                    $roles = $user->getUserRoles();
                    foreach ($roles as $role) {
                        if ($role === $superAdminRole) {
                            $output->writeln("Borrando el role de superadmin de {$user->getEmail()}");
                            $role->removeUser($user);
                            $user->removeRole($role);
                            $em->persist($role);
                            $em->persist($user);
                        }
                    }
                }
            } else {
                $output->writeln('Respetando los usuarios actuales');
            }
        }
        /* @var $userRepo \Doctrine\ORM\EntityRepository */
        $defaultPermitRepo = $em->getRepository('UAHGestorActividadesBundle:DefaultPermit');
        $identity = "http://yo.rediris.es/soy/{$userIdentity}@{$userDomain}/";
        //Actualizo los permisos. Solo dejo el último añadido
        $defaultPermit = $defaultPermitRepo->findOneBy(
                array('id_usuldap' => urldecode($identity)));
        if ($defaultPermit) {
            $default_permit_roles = $defaultPermit->getRoles();
            foreach ($default_permit_roles as $default_permit_role) {
                $defaultPermit->removeRole($default_permit_role);
            }
        } else {
            $defaultPermit = new \UAH\GestorActividadesBundle\Entity\DefaultPermit();
            $defaultPermit->setIdUsuldap($identity);
        }
        $defaultPermit->addRole($superAdminRole);
        $em->persist($defaultPermit);
        try {
            $em->flush();
            $salida = "Todo guardado perfectamente";
        } catch (\Doctrine\ORM\ORMException $exception) {
            $salida = "No se han podido guardar los datos ";
            if ($output->getVerbosity() >= \Symfony\Component\Console\Output\Output::VERBOSITY_NORMAL) {
                $message = sprintf(
                        '%s: %s (uncaught exception) at %s line %s while running console command `%s`', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $this->getName()
                );
                $output->writeln($message);
            }
        }
        $output->writeln($salida);
    }

}