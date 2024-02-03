<?php

namespace App\Command;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Clears all data and inserts users and creates conversations between them',
)]
class SeedDataCommand extends Command
{
    private EntityManagerInterface $em;

    private UserServiceInterface $userService;

    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserServiceInterface $userService, UserRepository $userRepository)
    {
        parent::__construct();

        $this->em = $em;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('plain_password', InputArgument::OPTIONAL, 'Single password for every user (will be hashed in db)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pass = $input->getArgument('plain_password');

        if (!$pass) {
            $io->error('Missing password argument');
            exit(1);
        }

        $this->em->beginTransaction();
        $this->clearDatabase(); 
        $this->createUsers($pass);
        $this->createConversationsWithParticipants();
        $this->em->flush();
        $this->em->commit();

        $io->success('Users created!');

        return Command::SUCCESS;
    }

    private function clearDatabase()
    {
        foreach ([Message::class, Conversation::class, Participant::class, User::class] as $ent) {
            $query = $this->em->createQuery("DELETE FROM $ent");
            $query->execute();
        }

        // restart auto increments in hacky way (following code only for postgre)
        $sequences = ['user_id_seq', 'conversation_id_seq', 'message_id_seq', 'participant_id_seq'];
        foreach ($sequences as $sequence) {
            $this->em->getConnection()->executeQuery("ALTER SEQUENCE $sequence RESTART WITH 1");
        }
    }

    private function createUsers(string $pass)
    {
        foreach ($this->getUsers() as $userData) {
            [$usr, $display, $avatar] = $userData;
            
            $user = new User();
            $user->setUsername($usr);
            $user->setDisplayName($display);
            $user->setAvatarFileName($avatar);

            $this->userService->persistNewUser($user, $pass, false);
        }
    }

    private function createConversationsWithParticipants()
    {
        $chatter = [
            [1, 4],     // johny & kerry
            [6, 5],     // v & jackie
            [6, 2],     // v & panam
            [6, 3]      // v & judy
        ];

        foreach ($chatter as $pair) {
            [$user1, $user2] = $pair;
            $conv = new Conversation();
            $part1 = new Participant();
            $part2 = new Participant();

            $this->em->persist($conv);

            $part1->setAppUser($this->userRepository->find($user1));
            $part1->setConversation($conv);
            $part2->setAppUser($this->userRepository->find($user2));
            $part2->setConversation($conv);

            $this->em->persist($part1);
            $this->em->persist($part2);
        }
    }

    private function getUsers(): iterable
    {
        yield ['johny', 'Johny Silverhand', 'robot-01.png'];
        yield ['panam', 'Panam Palmer', 'robot-02.png'];
        yield ['judy', 'Judy Álvarez', 'robot-03.png'];
        yield ['kerry', 'Kerry Eurodyne', 'robot-01.png'];
        yield ['jackie', 'Jackie Welles', 'robot-02.png'];
        yield ['v', 'V', 'robot-03.png'];
    }
}
