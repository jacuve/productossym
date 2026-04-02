<?php

namespace App\Controller\Api;

use App\DTO\RegistroDTO;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = RegistroDTO::fromArray($data);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json([
                'errors' => $errorMessages,
            ], 400);
        }

        $existingUser = $this->usuarioRepository->findByEmail($dto->email);
        if ($existingUser) {
            return $this->json([
                'errors' => ['email' => 'Este email ya está registrado'],
            ], 400);
        }

        $user = new Usuario();
        $user->setEmail($dto->email);
        $user->setNombre($dto->nombre);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $dto->password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Usuario registrado correctamente',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'nombre' => $user->getNombre(),
                'roles' => $user->getRoles(),
            ],
        ], 201);
    }
}
