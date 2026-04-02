<?php

namespace App\Command;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crea un usuario administrador',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email del administrador')
            ->addArgument('password', InputArgument::REQUIRED, 'Contraseña del administrador')
            ->addArgument('nombre', InputArgument::REQUIRED, 'Nombre del administrador');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $nombre = $input->getArgument('nombre');

        $user = new Usuario();
        $user->setEmail($email);
        $user->setNombre($nombre);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Administrador "%s" creado exitosamente.', $email));

        return Command::SUCCESS;
    }
}
