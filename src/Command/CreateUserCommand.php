<?php


namespace App\Command;


use App\Entity\User;
use App\Entity\ValueObject\PasswordEncoded;
use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    /**
     * @var UserService
     */
    private $userService;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the user to create.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user to create.')
            ->addArgument('password', InputArgument::REQUIRED, 'Plain password of the user to create.')
            ->addArgument('role', InputArgument::REQUIRED, 'Role for the user (ROLE_ADMIN or ROLE_USER)', User::ROLE_USER)
        ;
    }

    /**
     * {@inheritdoc}
     * @throws \App\Exception\InvalidRoleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = (array) $input->getArgument('role');

        $output->writeln([
            'Create user',
            '===========',
            ''
        ]);

        $password = new PasswordEncoded($password);

        $user = new User(
            $username,
            $email,
            $password,
            $roles
        );

        $this->userService->create($user);
    }

}