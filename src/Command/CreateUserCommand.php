<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use function PHPSTORM_META\argumentsSet;

#[AsCommand(
  name: 'app:create-user',
  description: 'Creates a new user.',
  hidden: false,
  aliases: ['app:add-user'],
)]
class CreateUserCommand extends Command
{

  private EntityManagerInterface $em;
  private DepartmentRepository $departmentRepository;

  public function __construct(EntityManagerInterface $em, DepartmentRepository $departmentRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->departmentRepository = $departmentRepository;
    }

  protected function configure(): void
  {
      $this->addArgument('first_name', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('last_name', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('age', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('status', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('email', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('telegram', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('address', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('department', InputArgument::REQUIRED, 'The username of the user.');
      $this->addArgument('icon', InputArgument::OPTIONAL, 'The username of the user.');
  }


  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    if (!$output instanceof ConsoleOutputInterface) {
      throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
    }

    $output->writeln([
      'Создание пользователя',
      '======================'
    ]);

    $sections1 = $output->section();
    $sections1->writeln("Проверка введённых данных");

    sleep(2);

    $sections1->overwrite("Все данные в порядке");

    
    $user = new User();

    $user->setFirstName($input->getArgument('first_name'));
    $user->setLastName($input->getArgument('last_name'));
    $user->setAge($input->getArgument('age'));
    $user->setStatus($input->getArgument('status'));
    $user->setTelegram($input->getArgument('telegram'));
    $user->setEmail($input->getArgument('email'));
    $user->setAddress($input->getArgument('address'));
    $user->setDepartment($this->departmentRepository->find($input->getArgument('department')));

    $icon = $input->getArgument('icon');
    if($icon){
      $user->setIcon($icon);
    }

    
    $this->em->persist($user);
        $this->em->flush();

    $sections1->overwrite("Пользователь добавлен");
    return Command::SUCCESS;

  }

}